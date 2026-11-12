<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Enums\FeeType;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class PaymentController extends Controller
{
    public function index(Request $request, PaymentGateway $gateway): View
    {
        Gate::authorize('viewAny', Payment::class);

        $payments = $request->user()
            ->payments()
            ->orderBy('status')
            ->orderBy('due_date')
            ->orderByDesc('id')
            ->get();

        $pendingMonthly = $payments->filter(
            fn (Payment $payment): bool => $payment->isPending() && $payment->fee_type === FeeType::Monthly
        );

        $pendingAnnual = $payments->filter(
            fn (Payment $payment): bool => $payment->isPending() && $payment->fee_type === FeeType::Annual
        );

        $paidPayments = $payments->filter(
            fn (Payment $payment): bool => $payment->isPaid()
        )->sortByDesc('paid_at');

        $totalPendingCents = $payments
            ->filter(fn (Payment $payment): bool => $payment->isPending())
            ->sum('amount_cents');

        return view('payments.index', [
            'pendingMonthly' => $pendingMonthly,
            'pendingAnnual' => $pendingAnnual,
            'paidPayments' => $paidPayments,
            'totalPendingFormatted' => number_format($totalPendingCents / 100, 2, ',', '.').' €',
            'stripeConfigured' => $gateway->isConfigured(),
        ]);
    }

    public function checkout(Request $request, Payment $payment, PaymentGateway $gateway): RedirectResponse
    {
        Gate::authorize('pay', $payment);

        if (! $gateway->isConfigured()) {
            return redirect()
                ->route('payments.index')
                ->with('error', 'La pasarela Stripe aún no está configurada en el servidor. Añade STRIPE_KEY y STRIPE_SECRET en el archivo .env para activar los pagos.');
        }

        $successUrl = route('payments.success', $payment).'?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('payments.cancel', $payment);

        try {
            $session = $gateway->createCheckoutSession($payment, $request->user(), $successUrl, $cancelUrl);

            $payment->update([
                'stripe_session_id' => $session['id'],
            ]);

            return redirect()->away($session['url']);
        } catch (Throwable $exception) {
            Log::error('Error creando sesión de Stripe Checkout', [
                'payment_id' => $payment->id,
                'user_id' => $request->user()->id,
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('payments.index')
                ->with('error', 'No se pudo iniciar el pago con Stripe en este momento. Inténtalo de nuevo en unos minutos.');
        }
    }

    public function success(Request $request, Payment $payment, PaymentGateway $gateway): RedirectResponse
    {
        Gate::authorize('view', $payment);

        if ($payment->isPaid()) {
            return redirect()
                ->route('payments.index')
                ->with('status', 'Este pago ya figuraba como completado.');
        }

        $sessionId = $request->string('session_id')->trim()->toString();

        if ($sessionId === '') {
            return redirect()
                ->route('payments.index')
                ->with('error', 'No se recibió el identificador de la sesión de Stripe.');
        }

        if ($payment->stripe_session_id !== null && $payment->stripe_session_id !== $sessionId) {
            return redirect()
                ->route('payments.index')
                ->with('error', 'La sesión de pago no coincide con este recibo.');
        }

        try {
            $session = $gateway->retrieveCheckoutSession($sessionId);
        } catch (Throwable $exception) {
            Log::warning('Error verificando sesión de Stripe Checkout', [
                'payment_id' => $payment->id,
                'session_id' => $sessionId,
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('payments.index')
                ->with('error', 'No se pudo verificar el estado del pago con Stripe.');
        }

        $metadataPaymentId = (int) ($session['metadata']['payment_id'] ?? $session['client_reference_id'] ?? 0);
        $metadataUserId = (int) ($session['metadata']['user_id'] ?? 0);
        $isSessionPaid = ($session['payment_status'] ?? null) === 'paid';

        if (! $isSessionPaid || $metadataPaymentId !== $payment->id || $metadataUserId !== $request->user()->id) {
            return redirect()
                ->route('payments.index')
                ->with('error', 'El pago todavía no figura como confirmado por Stripe.');
        }

        DB::transaction(function () use ($payment, $sessionId, $session): void {
            $locked = Payment::query()->whereKey($payment->id)->lockForUpdate()->firstOrFail();

            if ($locked->isPaid()) {
                return;
            }

            $locked->update([
                'status' => PaymentStatus::Paid,
                'stripe_session_id' => $sessionId,
                'stripe_payment_intent_id' => isset($session['payment_intent']) ? (string) $session['payment_intent'] : null,
                'paid_at' => now(),
            ]);
        });

        return redirect()
            ->route('payments.index')
            ->with('status', "¡Pago confirmado! Hemos registrado el ingreso de «{$payment->concept}».");
    }

    public function cancel(Payment $payment): RedirectResponse
    {
        Gate::authorize('view', $payment);

        return redirect()
            ->route('payments.index')
            ->with('info', 'Has cancelado el proceso de pago en Stripe. No se ha realizado ningún cargo.');
    }
}

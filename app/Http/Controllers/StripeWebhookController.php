<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, PaymentGateway $gateway): JsonResponse
    {
        $secret = (string) config('services.stripe.webhook_secret');
        $signatureHeader = (string) $request->header('Stripe-Signature', '');

        try {
            $event = $gateway->verifyWebhookSignature($request->getContent(), $signatureHeader, $secret);
        } catch (Throwable $exception) {
            Log::warning('Webhook de Stripe rechazado', ['reason' => $exception->getMessage()]);

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if (($event['type'] ?? null) === 'checkout.session.completed') {
            $session = $event['data']['object'] ?? [];
            $paymentId = (int) ($session['metadata']['payment_id'] ?? $session['client_reference_id'] ?? 0);

            if ($paymentId > 0 && ($session['payment_status'] ?? null) === 'paid') {
                DB::transaction(function () use ($paymentId, $session): void {
                    $payment = Payment::query()->whereKey($paymentId)->lockForUpdate()->first();

                    if ($payment === null || $payment->isPaid()) {
                        return;
                    }

                    $payment->update([
                        'status' => PaymentStatus::Paid,
                        'stripe_session_id' => isset($session['id']) ? (string) $session['id'] : $payment->stripe_session_id,
                        'stripe_payment_intent_id' => isset($session['payment_intent']) ? (string) $session['payment_intent'] : null,
                        'paid_at' => now(),
                    ]);
                });
            }
        }

        return response()->json(['received' => true]);
    }
}

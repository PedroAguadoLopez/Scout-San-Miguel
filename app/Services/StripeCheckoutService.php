<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class StripeCheckoutService implements PaymentGateway
{
    private const API_BASE = 'https://api.stripe.com/v1';

    public function isConfigured(): bool
    {
        return filled(config('services.stripe.secret'));
    }

    /**
     * @return array{id: string, url: string}
     *
     * @throws RequestException
     */
    public function createCheckoutSession(
        Payment $payment,
        User $user,
        string $successUrl,
        string $cancelUrl,
    ): array {
        $secret = (string) config('services.stripe.secret');

        if ($secret === '') {
            throw new RuntimeException('Stripe no está configurado. Añade STRIPE_SECRET en el archivo .env.');
        }

        $response = Http::asForm()
            ->withToken($secret)
            ->timeout(15)
            ->post(self::API_BASE.'/checkout/sessions', [
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'customer_email' => $user->email,
                'client_reference_id' => (string) $payment->id,
                'metadata' => [
                    'payment_id' => (string) $payment->id,
                    'user_id' => (string) $user->id,
                    'fee_type' => $payment->fee_type->value,
                ],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($payment->currency),
                            'unit_amount' => $payment->amount_cents,
                            'product_data' => [
                                'name' => $payment->concept,
                                'description' => $payment->period_label.' · Grupo Scout San Miguel (Algeciras)',
                            ],
                        ],
                        'quantity' => 1,
                    ],
                ],
            ])
            ->throw();

        $data = $response->json();

        if (! is_array($data) || empty($data['id']) || empty($data['url'])) {
            throw new RuntimeException('Respuesta inesperada al crear la sesión de pago con Stripe.');
        }

        return [
            'id' => (string) $data['id'],
            'url' => (string) $data['url'],
        ];
    }

    /**
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    public function retrieveCheckoutSession(string $sessionId): array
    {
        $secret = (string) config('services.stripe.secret');

        if ($secret === '') {
            throw new RuntimeException('Stripe no está configurado. Añade STRIPE_SECRET en el archivo .env.');
        }

        $response = Http::withToken($secret)
            ->timeout(15)
            ->get(self::API_BASE.'/checkout/sessions/'.urlencode($sessionId))
            ->throw();

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException('No se pudo verificar la sesión de Stripe.');
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function verifyWebhookSignature(string $payload, string $signatureHeader, string $secret): array
    {
        if ($secret === '' || $signatureHeader === '') {
            throw new RuntimeException('Firma de webhook de Stripe ausente o sin secreto configurado.');
        }

        $timestamp = null;
        $signatures = [];

        foreach (explode(',', $signatureHeader) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, '');

            if ($key === 't') {
                $timestamp = $value;
            } elseif ($key === 'v1' && $value !== '') {
                $signatures[] = $value;
            }
        }

        if ($timestamp === null || ! ctype_digit($timestamp) || $signatures === []) {
            throw new RuntimeException('Cabecera Stripe-Signature inválida.');
        }

        if (abs(time() - (int) $timestamp) > 300) {
            throw new RuntimeException('La marca temporal del webhook de Stripe ha expirado.');
        }

        $expectedSignature = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);
        $valid = false;

        foreach ($signatures as $signature) {
            if (hash_equals($expectedSignature, $signature)) {
                $valid = true;
                break;
            }
        }

        if (! $valid) {
            throw new RuntimeException('La firma del webhook de Stripe no es válida.');
        }

        $decoded = json_decode($payload, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('El cuerpo del webhook de Stripe no es JSON válido.');
        }

        return $decoded;
    }
}

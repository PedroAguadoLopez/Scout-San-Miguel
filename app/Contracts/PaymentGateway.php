<?php

namespace App\Contracts;

use App\Models\Payment;
use App\Models\User;

interface PaymentGateway
{
    public function isConfigured(): bool;

    /**
     * Create a hosted checkout session for a pending payment.
     *
     * @return array{id: string, url: string}
     */
    public function createCheckoutSession(
        Payment $payment,
        User $user,
        string $successUrl,
        string $cancelUrl,
    ): array;

    /**
     * Retrieve a checkout session from the payment provider.
     *
     * @return array<string, mixed>
     */
    public function retrieveCheckoutSession(string $sessionId): array;

    /**
     * Verify a webhook signature and return the decoded event payload.
     *
     * @return array<string, mixed>
     */
    public function verifyWebhookSignature(string $payload, string $signatureHeader, string $secret): array;
}

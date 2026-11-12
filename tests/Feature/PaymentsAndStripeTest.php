<?php

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('un menor de edad no puede acceder a la sección de pagos ni iniciar un checkout', function (): void {
    $minor = User::factory()->minor()->create();
    $adultPayment = Payment::factory()->create();

    $this->actingAs($minor)
        ->get(route('payments.index'))
        ->assertForbidden();

    $this->actingAs($minor)
        ->post(route('payments.checkout', $adultPayment))
        ->assertForbidden();
});

test('un mayor de edad solo ve sus propios pagos y no puede acceder a los de otro usuario', function (): void {
    $adult = User::factory()->adult()->create();
    $otherAdult = User::factory()->adult()->create();

    $myMonthly = Payment::factory()->monthly()->for($adult)->create([
        'concept' => 'Mi cuota mensual noviembre',
    ]);
    $myAnnual = Payment::factory()->annual()->for($adult)->create([
        'concept' => 'Mi cuota anual 2026',
    ]);
    $otherPayment = Payment::factory()->monthly()->for($otherAdult)->create([
        'concept' => 'Cuota privada de Carlos',
    ]);

    $this->actingAs($adult)
        ->get(route('payments.index'))
        ->assertSuccessful()
        ->assertSee('Mi cuota mensual noviembre')
        ->assertSee('Mi cuota anual 2026')
        ->assertDontSee('Cuota privada de Carlos');

    $this->actingAs($adult)
        ->post(route('payments.checkout', $otherPayment))
        ->assertForbidden();

    $this->actingAs($adult)
        ->get(route('payments.success', ['payment' => $otherPayment, 'session_id' => 'cs_test_other']))
        ->assertForbidden();
});

test('un mayor de edad puede iniciar un pago con Stripe Checkout y completarlo al volver', function (): void {
    config()->set('services.stripe.secret', 'sk_test_mock_secret');

    $adult = User::factory()->adult()->create();
    $payment = Payment::factory()->annual()->for($adult)->create();

    Http::fake([
        'https://api.stripe.com/v1/checkout/sessions' => Http::response([
            'id' => 'cs_test_123456',
            'url' => 'https://checkout.stripe.com/c/pay/cs_test_123456',
        ], 200),
        'https://api.stripe.com/v1/checkout/sessions/cs_test_123456' => Http::response([
            'id' => 'cs_test_123456',
            'payment_status' => 'paid',
            'payment_intent' => 'pi_test_999',
            'metadata' => [
                'payment_id' => (string) $payment->id,
                'user_id' => (string) $adult->id,
            ],
        ], 200),
    ]);

    $checkoutResponse = $this->actingAs($adult)
        ->post(route('payments.checkout', $payment));

    $checkoutResponse->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_123456');

    $payment->refresh();
    expect($payment->stripe_session_id)->toBe('cs_test_123456');
    expect($payment->status)->toBe(PaymentStatus::Pending);

    $successResponse = $this->actingAs($adult)
        ->get(route('payments.success', ['payment' => $payment, 'session_id' => 'cs_test_123456']));

    $successResponse->assertRedirect(route('payments.index'));

    $payment->refresh();
    expect($payment->status)->toBe(PaymentStatus::Paid);
    expect($payment->stripe_payment_intent_id)->toBe('pi_test_999');
    expect($payment->paid_at)->not->toBeNull();
});

test('el webhook de Stripe verifica la firma y marca el pago como completado de forma idempotente', function (): void {
    $webhookSecret = 'whsec_test_secret_san_miguel';
    config()->set('services.stripe.webhook_secret', $webhookSecret);

    $payment = Payment::factory()->monthly()->create();

    $payload = json_encode([
        'id' => 'evt_test_1',
        'type' => 'checkout.session.completed',
        'data' => [
            'object' => [
                'id' => 'cs_test_webhook_1',
                'payment_status' => 'paid',
                'payment_intent' => 'pi_webhook_1',
                'metadata' => [
                    'payment_id' => (string) $payment->id,
                ],
            ],
        ],
    ], JSON_THROW_ON_ERROR);

    $timestamp = time();
    $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", $webhookSecret);
    $header = "t={$timestamp},v1={$signature}";

    $this->postJson(route('stripe.webhook'), json_decode($payload, true), [
        'Stripe-Signature' => 't=123,v1=invalid',
    ])->assertStatus(400);

    $this->call('POST', route('stripe.webhook'), [], [], [], [
        'HTTP_STRIPE_SIGNATURE' => $header,
        'CONTENT_TYPE' => 'application/json',
    ], $payload)->assertOk();

    $payment->refresh();
    expect($payment->status)->toBe(PaymentStatus::Paid);
    expect($payment->stripe_payment_intent_id)->toBe('pi_webhook_1');
});

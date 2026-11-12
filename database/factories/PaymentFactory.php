<?php

namespace Database\Factories;

use App\Enums\FeeType;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->adult(),
            'concept' => 'Cuota mensual de grupo',
            'description' => 'Cuota mensual para actividades y material de ronda.',
            'fee_type' => FeeType::Monthly,
            'period_label' => 'Noviembre 2026',
            'amount_cents' => 1500,
            'currency' => 'eur',
            'due_date' => now()->addDays(15)->toDateString(),
            'status' => PaymentStatus::Pending,
            'stripe_session_id' => null,
            'stripe_payment_intent_id' => null,
            'paid_at' => null,
        ];
    }

    public function monthly(): static
    {
        return $this->state(fn (array $attributes): array => [
            'fee_type' => FeeType::Monthly,
            'amount_cents' => 1500,
        ]);
    }

    public function annual(): static
    {
        return $this->state(fn (array $attributes): array => [
            'concept' => 'Cuota anual y seguro federativo',
            'description' => 'Inscripción de ronda solar y seguro scout anual.',
            'fee_type' => FeeType::Annual,
            'period_label' => 'Ronda 2026/2027',
            'amount_cents' => 6500,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => PaymentStatus::Paid,
            'stripe_session_id' => 'cs_test_'.fake()->regexify('[A-Za-z0-9]{16}'),
            'stripe_payment_intent_id' => 'pi_test_'.fake()->regexify('[A-Za-z0-9]{16}'),
            'paid_at' => now()->subDays(5),
        ]);
    }
}

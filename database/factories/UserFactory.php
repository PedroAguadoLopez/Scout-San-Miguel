<?php

namespace Database\Factories;

use App\Enums\ScoutSection;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'birth_date' => now()->subYears(25)->toDateString(),
            'role' => UserRole::Adult,
            'scout_section' => ScoutSection::Grupo,
            'phone' => fake()->optional()->phoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function adult(): static
    {
        return $this->state(fn (array $attributes): array => [
            'birth_date' => now()->subYears(30)->toDateString(),
            'role' => UserRole::Adult,
        ]);
    }

    public function minor(): static
    {
        return $this->state(fn (array $attributes): array => [
            'birth_date' => now()->subYears(13)->toDateString(),
            'role' => UserRole::Minor,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'birth_date' => now()->subYears(28)->toDateString(),
            'role' => UserRole::Admin,
            'scout_section' => ScoutSection::Kraal,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}

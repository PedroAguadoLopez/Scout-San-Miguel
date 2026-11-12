<?php

namespace Database\Factories;

use App\Models\Camp;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Camp>
 */
class CampFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = 'Campamento '.fake()->words(3, true);
        $year = fake()->numberBetween(2022, 2026);
        $startDate = fake()->dateTimeBetween("{$year}-07-01", "{$year}-08-10");
        $endDate = (clone $startDate)->modify('+10 days');

        return [
            'title' => ucfirst($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'location' => fake()->city().', Cádiz',
            'year' => $year,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'summary' => fake()->sentence(16),
            'description' => fake()->paragraphs(3, true),
            'cover_image' => '/images/camps/grazalema-1.svg',
            'is_featured' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_featured' => true,
        ]);
    }
}

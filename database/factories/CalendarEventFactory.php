<?php

namespace Database\Factories;

use App\Enums\ScoutSection;
use App\Models\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CalendarEvent>
 */
class CalendarEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('now', '+2 months');
        $endsAt = (clone $startsAt)->modify('+3 hours');

        return [
            'title' => 'Reunión Scout '.fake()->words(2, true),
            'description' => fake()->sentence(14),
            'location' => 'Local Scout San Miguel, Algeciras',
            'scout_section' => fake()->randomElement(ScoutSection::cases()),
            'event_type' => fake()->randomElement(['reunion', 'acampada', 'excursion', 'campamento']),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'all_day' => false,
        ];
    }
}

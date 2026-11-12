<?php

namespace Database\Factories;

use App\Models\Camp;
use App\Models\CampPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CampPhoto>
 */
class CampPhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'camp_id' => Camp::factory(),
            'image_path' => '/images/camps/grazalema-1.svg',
            'caption' => fake()->sentence(8),
            'sort_order' => 1,
        ];
    }
}

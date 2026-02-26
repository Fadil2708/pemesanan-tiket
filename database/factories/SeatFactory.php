<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'studio_id' => \App\Models\Studio::factory(),
            'seat_number' => fake()->unique()->regexify('[A-Z][0-9]{1,2}'),
            'seat_type' => fake()->randomElement(['regular', 'vip', 'couple']),
        ];
    }
}

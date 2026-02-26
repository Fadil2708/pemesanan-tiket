<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Showtime>
 */
class ShowtimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = fake()->time('H:i');
        $duration = fake()->numberBetween(90, 180);
        $endTime = date('H:i', strtotime($startTime) + $duration * 60);

        return [
            'film_id' => \App\Models\Film::inRandomOrder()->first()->id ?? 1,
            'studio_id' => \App\Models\Studio::inRandomOrder()->first()->id ?? 1,
            'show_date' => fake()->dateTimeBetween('now', '+1 month'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => fake()->numberBetween(30000, 100000),
        ];
    }
}

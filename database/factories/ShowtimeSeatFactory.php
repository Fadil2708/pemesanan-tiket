<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShowtimeSeat>
 */
class ShowtimeSeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'showtime_id' => \App\Models\Showtime::factory(),
            'seat_id' => \App\Models\Seat::factory(),
            'status' => 'available',
            'locked_at' => null,
            'locked_by' => null,
        ];
    }
}

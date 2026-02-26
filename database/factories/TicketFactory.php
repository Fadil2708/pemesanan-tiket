<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::inRandomOrder()->first()->id ?? 1,
            'seat_id' => \App\Models\Seat::inRandomOrder()->first()->id ?? 1,
            'price' => fake()->numberBetween(30000, 100000),
            'qr_code' => fake()->uuid(),
        ];
    }
}

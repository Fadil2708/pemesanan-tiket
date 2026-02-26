<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id ?? 1,
            'showtime_id' => \App\Models\Showtime::inRandomOrder()->first()->id ?? 1,
            'booking_code' => fake()->unique()->regexify('[A-Z0-9]{8}'),
            'total_price' => fake()->numberBetween(30000, 300000),
            'status' => fake()->randomElement(['pending', 'paid', 'canceled']),
            'payment_method' => fake()->randomElement(['manual', 'credit_card', 'bank_transfer']),
            'expires_at' => fake()->dateTimeBetween('now', '+1 hour'),
        ];
    }
}

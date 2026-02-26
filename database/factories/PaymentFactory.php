<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
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
            'order_id' => \App\Models\Order::inRandomOrder()->first()->id ?? 1,
            'payment_reference' => fake()->unique()->regexify('[A-Z0-9]{10}'),
            'payment_status' => fake()->randomElement(['pending', 'success', 'failed']),
            'paid_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Studio>
 */
class StudioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Studio '.fake()->unique()->numberBetween(1, 10),
            'type' => fake()->randomElement(['2D', '3D', 'IMAX', 'Dolby']),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'poster' => fake()->imageUrl(300, 450, 'movies'),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween(90, 180),
            'age_rating' => fake()->randomElement(['SU', '13+', '17+', '21+']),
            'release_date' => fake()->dateTimeBetween('-1 year', '+1 month'),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Film;
use Illuminate\Database\Seeder;

class FilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Film::create([
            'title' => 'Avengers Endgame',
            'description' => 'Superhero action movie.',
            'duration' => 180,
            'age_rating' => '13+',
            'release_date' => '2019-04-24',
        ]);

        Film::create([
            'title' => 'Interstellar',
            'description' => 'Space exploration sci-fi movie.',
            'duration' => 169,
            'age_rating' => '13+',
            'release_date' => '2014-11-07',
        ]);
    }

}

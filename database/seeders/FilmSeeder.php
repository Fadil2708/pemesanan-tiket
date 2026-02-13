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
            'description' => 'Film superhero Marvel',
            'duration' => 180,
            'age_rating' => 'R13',
            'release_date' => '2019-04-24'
        ]);

        Film::create([
            'title' => 'Interstellar',
            'description' => 'Sci-fi tentang luar angkasa',
            'duration' => 169,
            'age_rating' => 'SU',
            'release_date' => '2014-11-07'
        ]);
    }

}

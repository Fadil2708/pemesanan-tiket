<?php

namespace Database\Seeders;

use App\Models\Showtime;
use Illuminate\Database\Seeder;
use App\Models\Film;
use App\Models\Studio;

class ShowtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studio = Studio::first();
        $film1 = Film::first();
        $film2 = Film::skip(1)->first();

        Showtime::create([
            'film_id' => $film1->id,
            'studio_id' => $studio->id,
            'show_date' => now()->toDateString(),
            'start_time' => '18:00',
            'end_time' => '21:00',
            'price' => 50000
        ]);

        Showtime::create([
            'film_id' => $film2->id,
            'studio_id' => $studio->id,
            'show_date' => now()->toDateString(),
            'start_time' => '21:30',
            'end_time' => '23:59',
            'price' => 60000
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Showtime;
use Illuminate\Database\Seeder;

class ShowtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Showtime::create([
            'film_id' => 1,
            'studio_id' => 1,
            'show_date' => '2026-02-15',
            'start_time' => '19:00:00',
            'end_time' => '21:00:00',
            'price' => 50000
        ]);

        Showtime::create([
            'film_id' => 2,
            'studio_id' => 2,
            'show_date' => '2026-02-15',
            'start_time' => '16:00:00',
            'end_time' => '18:00:00',
            'price' => 45000
        ]);
    }

}

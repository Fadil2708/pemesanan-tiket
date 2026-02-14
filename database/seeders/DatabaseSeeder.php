<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $this->call([
            CategorySeeder::class,
            FilmSeeder::class,
            StudioSeeder::class,
            SeatSeeder::class,
            ShowtimeSeeder::class,
            ShowtimeSeatSeeder::class,
            UserSeeder::class,
        ]);
    }
}

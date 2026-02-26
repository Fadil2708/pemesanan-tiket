<?php

namespace Database\Seeders;

use App\Models\Film;
use App\Models\Showtime;
use App\Models\Studio;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ShowtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $films = Film::all();
        $studios = Studio::all();

        if ($films->isEmpty() || $studios->isEmpty()) {
            $this->command->error('❌ Films or Studios not found. Please seed them first!');
            return;
        }

        $showtimes = [];

        // Generate showtimes for the next 7 days
        for ($day = 0; $day < 7; $day++) {
            $showDate = Carbon::today()->addDays($day);

            // Each day, show 2-3 films per studio
            foreach ($studios as $studio) {
                // Randomly select 2-3 films for this studio on this day
                $selectedFilms = $films->random(rand(2, 3));

                $currentTime = Carbon::parse('10:00:00'); // Start from 10 AM

                foreach ($selectedFilms as $film) {
                    // Create 2-3 showtimes per film per day
                    $showtimesCount = rand(2, 3);

                    for ($i = 0; $i < $showtimesCount; $i++) {
                        // Check if there's enough time left in the day
                        if ($currentTime->copy()->addMinutes($film->duration + 30)->hour >= 23) {
                            break;
                        }

                        $startTime = $currentTime->copy();
                        $endTime = $startTime->copy()->addMinutes($film->duration);

                        // Add 30 minutes break between showtimes
                        $currentTime = $endTime->copy()->addMinutes(30);

                        // Vary price based on studio type and time
                        $basePrice = 35000;
                        if ($studio->type === 'IMAX') {
                            $basePrice = 75000;
                        } elseif ($studio->type === '3D') {
                            $basePrice = 50000;
                        } elseif ($studio->type === 'Dolby') {
                            $basePrice = 60000;
                        }

                        // Evening shows (after 5 PM) are more expensive
                        if ($startTime->hour >= 17) {
                            $basePrice += 10000;
                        }

                        // Weekend shows are more expensive
                        if ($showDate->isWeekend()) {
                            $basePrice += 5000;
                        }

                        $showtimes[] = [
                            'film_id' => $film->id,
                            'studio_id' => $studio->id,
                            'show_date' => $showDate->format('Y-m-d'),
                            'start_time' => $startTime->format('H:i:s'),
                            'end_time' => $endTime->format('H:i:s'),
                            'price' => $basePrice,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        // Insert all showtimes
        Showtime::insert($showtimes);

        $this->command->info('✅ Showtimes seeded successfully! (' . count($showtimes) . ' showtimes created)');
    }
}

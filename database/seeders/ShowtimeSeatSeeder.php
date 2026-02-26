<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShowtimeSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $showtimes = \App\Models\Showtime::all();

        foreach ($showtimes as $showtime) {
            $seats = \App\Models\Seat::where('studio_id', $showtime->studio_id)->get();

            foreach ($seats as $seat) {
                \App\Models\ShowtimeSeat::create([
                    'showtime_id' => $showtime->id,
                    'seat_id' => $seat->id,
                    'status' => 'available',
                    'locked_at' => null,
                    'locked_by' => null,
                ]);
            }
        }
    }
}

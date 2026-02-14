<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShowtimeSeat;
use App\Models\Showtime;
use App\Models\Seat;

class ShowtimeSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $showtimes = Showtime::all();
        $seats = Seat::all();

        foreach ($showtimes as $showtime) {
            foreach ($seats as $seat) {

                ShowtimeSeat::updateOrCreate(
                    [
                        'showtime_id' => $showtime->id,
                        'seat_id' => $seat->id,
                    ],
                    [
                        'status' => 'available',
                        'locked_at' => null
                    ]
                );
            }
        }
    }
}

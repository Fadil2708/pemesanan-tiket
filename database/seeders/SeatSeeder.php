<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Studio;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studio = Studio::first();

        foreach (range('A','E') as $row) {
            for ($i = 1; $i <= 5; $i++) {
                Seat::create([
                    'studio_id' => $studio->id,
                    'seat_number' => $row.$i,
                    'seat_type' => 'regular'
                ]);
            }
        }
    }
}

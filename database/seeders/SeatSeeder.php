<?php

namespace Database\Seeders;

use App\Models\Seat;
use App\Models\Studio;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            $seats = [];

            // Generate 8 rows (A-H) × 10 columns (1-10) = 80 seats per studio
            $rows = range('A', 'H');
            $columns = range(1, 10);

            foreach ($rows as $row) {
                foreach ($columns as $col) {
                    $seatNumber = $row . $col;

                    // Determine seat type based on position
                    $seatType = 'regular';

                    // Front 2 rows (A, B) = VIP
                    if (in_array($row, ['A', 'B'])) {
                        $seatType = 'vip';
                    }

                    // Middle rows, columns 4-7 = Couple seats
                    if (in_array($row, ['D', 'E']) && in_array($col, [4, 5, 6, 7])) {
                        $seatType = 'couple';
                    }

                    $seats[] = [
                        'studio_id' => $studio->id,
                        'seat_number' => $seatNumber,
                        'seat_type' => $seatType,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Insert seats for this studio
            Seat::insert($seats);
        }

        $this->command->info('✅ Seats seeded successfully! (' . Seat::count() . ' seats created)');
    }
}

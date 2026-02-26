<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = \App\Models\Order::all();

        foreach ($orders as $order) {
            // Create 1-3 tickets per order
            $numTickets = rand(1, 3);
            for ($i = 0; $i < $numTickets; $i++) {
                \App\Models\Ticket::factory()->create([
                    'order_id' => $order->id,
                    'seat_id' => \App\Models\Seat::inRandomOrder()->first()->id,
                    'price' => $order->showtime->price,
                ]);
            }
        }
    }
}

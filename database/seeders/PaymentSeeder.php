<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = \App\Models\Order::all();

        foreach ($orders as $order) {
            \App\Models\Payment::factory()->create([
                'order_id' => $order->id,
            ]);
        }
    }
}

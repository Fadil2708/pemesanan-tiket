<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Showtime;
use App\Models\ShowtimeSeat;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'customer')->get();
        $showtimes = Showtime::where('show_date', '>=', Carbon::today())->get();

        if ($users->isEmpty() || $showtimes->isEmpty()) {
            $this->command->error('❌ Users or Showtimes not found. Please seed them first!');
            return;
        }

        // Create sample orders with different statuses
        $orderStatuses = ['paid', 'paid', 'paid', 'pending', 'canceled', 'failed'];

        foreach ($users as $user) {
            // Each user has 1-2 orders
            $orderCount = rand(1, 2);

            for ($i = 0; $i < $orderCount; $i++) {
                // Get random showtime (prefer future showtimes)
                $showtime = $showtimes->random();

                // Get available or booked seats for this showtime
                $showtimeSeats = ShowtimeSeat::where('showtime_id', $showtime->id)
                    ->with('seat')
                    ->get();

                if ($showtimeSeats->isEmpty()) {
                    continue;
                }

                // Select 1-3 random seats
                $selectedSeats = $showtimeSeats->random(rand(1, 3));

                // Calculate total price
                $totalPrice = $showtime->price * $selectedSeats->count();

                // Determine status - ensure we have at least 1 pending order
                if ($i === 0 && $user->id === $users->first()->id) {
                    $status = 'pending'; // First order of first user is always pending
                } else {
                    $status = $orderStatuses[array_rand($orderStatuses)];
                }

                // Create booking code
                $bookingCode = strtoupper(Str::random(8));

                // Create order
                $order = Order::create([
                    'user_id' => $user->id,
                    'showtime_id' => $showtime->id,
                    'booking_code' => $bookingCode,
                    'total_price' => $totalPrice,
                    'status' => $status,
                    'payment_method' => 'midtrans',
                    'expires_at' => $status === 'pending' 
                        ? Carbon::now()->addMinutes(10) 
                        : Carbon::now()->subMinutes(30),
                ]);

                // Create tickets for each seat
                foreach ($selectedSeats as $showtimeSeat) {
                    Ticket::create([
                        'order_id' => $order->id,
                        'seat_id' => $showtimeSeat->seat_id,
                        'price' => $showtime->price,
                        'qr_code' => Str::uuid(),
                    ]);

                    // If order is paid, mark seat as booked
                    if ($status === 'paid') {
                        $showtimeSeat->update([
                            'status' => 'booked',
                            'locked_at' => null,
                            'locked_by' => null,
                        ]);
                    }
                }

                // Create payment record
                $paymentStatus = match($status) {
                    'paid' => 'success',
                    'pending' => 'pending',
                    default => 'failed',
                };

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'midtrans',
                    'payment_status' => $paymentStatus,
                    'payment_reference' => $paymentStatus === 'success' ? 'MIDTRANS-' . strtoupper(Str::random(10)) : null,
                    'amount' => $totalPrice,
                    'paid_at' => $paymentStatus === 'success' ? Carbon::now() : null,
                ]);
            }
        }

        $this->command->info('✅ Orders seeded successfully!');
        $this->command->info('   Total Orders: ' . Order::count());
        $this->command->info('   Paid: ' . Order::where('status', 'paid')->count());
        $this->command->info('   Pending: ' . Order::where('status', 'pending')->count());
        $this->command->info('   Canceled: ' . Order::where('status', 'canceled')->count());
        $this->command->info('   Failed: ' . Order::where('status', 'failed')->count());
    }
}

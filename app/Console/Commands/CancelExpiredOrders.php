<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\ShowtimeSeat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingExpiredMail;


class CancelExpiredOrders extends Command
{
    // ⬇️ DI SINI TEMPATNYA
    protected $signature = 'cancel:expired-orders';

    protected $description = 'Cancel expired pending orders automatically';

    public function handle()
    {
        $expiredOrders = Order::where('status', 'pending')
            ->where('expires_at', '<', Carbon::now())
            ->get();

        foreach ($expiredOrders as $order) {

            foreach ($order->tickets as $ticket) {

                ShowtimeSeat::where('showtime_id', $order->showtime_id)
                    ->where('seat_id', $ticket->seat_id)
                    ->update([
                        'status' => 'available',
                        'locked_at' => null
                    ]);
            }

            $order->update([
                'status' => 'canceled'
            ]);

            // 🔥 Kirim Email
            Mail::to($order->user->email)
                ->send(new BookingExpiredMail($order));
        }

        $this->info('Expired orders processed successfully.');

        return 0;
    }
}

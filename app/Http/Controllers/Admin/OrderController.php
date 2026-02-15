<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShowtimeSeat;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user','showtime.film'])
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'showtime.film',
            'showtime.studio',
            'tickets.seat'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        DB::transaction(function () use ($order) {

            // 1️⃣ Update status order
            $order->update([
                'status' => 'canceled'
            ]);

            // 2️⃣ Ambil semua ticket
            foreach ($order->tickets as $ticket) {

                // 3️⃣ Update showtime_seat jadi available
                ShowtimeSeat::where('showtime_id', $order->showtime_id)
                    ->where('seat_id', $ticket->seat_id)
                    ->update([
                        'status' => 'available',
                        'locked_at' => null
                    ]);
            }

        });

        return redirect()->route('orders.index')
            ->with('success','Order dibatalkan & kursi dikembalikan');
    }
}


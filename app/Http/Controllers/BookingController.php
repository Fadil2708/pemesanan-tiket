<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ShowtimeSeat;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function lockSeat($showtimeSeatId)
    {
        return DB::transaction(function () use ($showtimeSeatId) {

            $seat = ShowtimeSeat::where('id', $showtimeSeatId)
                ->lockForUpdate()
                ->first();

            if (!$seat) {
                return response()->json(['message' => 'Seat not found'], 404);
            }

            // Cek kalau sudah booked
            if ($seat->status === 'booked') {
                return response()->json(['message' => 'Seat already booked'], 400);
            }

            // Cek kalau masih locked dan belum expired
            if ($seat->status === 'locked' &&
                $seat->locked_at &&
                Carbon::parse($seat->locked_at)->addMinutes(5)->isFuture()) {

                return response()->json(['message' => 'Seat still locked'], 400);
            }

            // Lock kursi
            $seat->update([
                'status' => 'locked',
                'locked_at' => now()
            ]);

            return response()->json([
                'message' => 'Seat locked successfully',
                'expires_at' => now()->addMinutes(5)
            ]);
        });
    }
    public function checkout(Request $request)
{
    $request->validate([
        'showtime_seat_ids' => 'required|array'
    ]);

    return DB::transaction(function () use ($request) {

        $user = Auth::user();

        $seats = ShowtimeSeat::whereIn('id', $request->showtime_seat_ids)
            ->lockForUpdate()
            ->get();

        if ($seats->count() == 0) {
            return back()->with('error', 'No seats found.');
        }

        foreach ($seats as $seat) {

            if ($seat->status !== 'locked') {
                return back()->with('error', 'Seat not locked.');
            }

            if (!$seat->locked_at ||
                Carbon::parse($seat->locked_at)->addMinutes(5)->isPast()) {
                return back()->with('error', 'Seat lock expired.');
            }
        }

        $showtime = $seats->first()->showtime;
        $totalPrice = $showtime->price * $seats->count();

        $order = Order::create([
            'user_id' => $user->id,
            'showtime_id' => $showtime->id,
            'booking_code' => strtoupper(Str::random(8)),
            'total_price' => $totalPrice,
            'status' => 'paid',
            'payment_method' => 'manual'
        ]);

        foreach ($seats as $seat) {

            Ticket::create([
                'order_id' => $order->id,
                'seat_id' => $seat->seat_id,
                'price' => $showtime->price,
                'qr_code' => Str::uuid()
            ]);

            $seat->update([
                'status' => 'booked',
                'locked_at' => null
            ]);
        }

        return redirect('/dashboard')
            ->with('success', 'Checkout berhasil! Booking Code: '.$order->booking_code);
    });
}

}

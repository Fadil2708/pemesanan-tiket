<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ShowtimeSeat;
use App\Models\Ticket;
use App\Services\BookingService;
use App\Services\MidtransPaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    protected $bookingService;

    protected $midtransPaymentService;

    public function __construct(BookingService $bookingService, MidtransPaymentService $midtransPaymentService)
    {
        $this->bookingService = $bookingService;
        $this->midtransPaymentService = $midtransPaymentService;
    }

    public function lockSeat(Request $request, $showtimeSeatId)
    {
        // Validasi input dari route parameter
        if (! $showtimeSeatId || ! is_numeric($showtimeSeatId)) {
            return response()->json(['message' => 'Invalid seat ID'], 400);
        }

        return DB::transaction(function () use ($showtimeSeatId) {
            $userId = Auth::id();
            $now = Carbon::now();
            $expiryTime = $now->copy()->addMinutes(5);

            // Lock row untuk mencegah race condition
            $showtimeSeat = ShowtimeSeat::where('id', $showtimeSeatId)
                ->lockForUpdate()
                ->first();

            if (! $showtimeSeat) {
                return response()->json(['message' => 'Seat not found'], 404);
            }

            // Cek jika sudah booked
            if ($showtimeSeat->status === 'booked') {
                return response()->json(['message' => 'Seat already booked'], 400);
            }

            // Jika status locked
            if ($showtimeSeat->status === 'locked') {
                // Cek apakah lock sudah expired (5 menit)
                $isExpired = $showtimeSeat->locked_at &&
                             Carbon::parse($showtimeSeat->locked_at)->addMinutes(5)->isPast();

                if ($isExpired) {
                    // Release expired lock
                    $showtimeSeat->update([
                        'status' => 'available',
                        'locked_at' => null,
                        'locked_by' => null,
                    ]);
                    // Lanjut ke lock di bawah
                } elseif ($showtimeSeat->locked_by === $userId) {
                    // User's own lock - extend
                    $showtimeSeat->update(['locked_at' => $now]);

                    return response()->json([
                        'message' => 'Seat lock extended',
                        'expires_at' => $expiryTime,
                    ]);
                } else {
                    // Locked by another user
                    return response()->json(['message' => 'Seat still locked by another user'], 400);
                }
            }

            // Lock the seat (available atau setelah release expired)
            if ($showtimeSeat->status === 'available') {
                $showtimeSeat->update([
                    'status' => 'locked',
                    'locked_at' => $now,
                    'locked_by' => $userId,
                ]);

                return response()->json([
                    'message' => 'Seat locked successfully',
                    'expires_at' => $expiryTime,
                ]);
            }

            // Fallback - seharusnya tidak tercapai
            return response()->json(['message' => 'Unable to lock seat'], 500);
        });
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'showtime_seat_ids' => 'required|array|min:1',
            'showtime_seat_ids.*' => 'required|integer|exists:showtime_seats,id',
        ]);

        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $now = Carbon::now();

            $seats = ShowtimeSeat::whereIn('id', $request->showtime_seat_ids)
                ->lockForUpdate()
                ->get();

            if ($seats->count() == 0) {
                return back()->with('error', 'No seats found.');
            }

            // Validasi semua kursi belong to the same showtime
            $showtimeId = $seats->first()->showtime_id;
            $differentShowtime = $seats->filter(fn($seat) => $seat->showtime_id !== $showtimeId)->count();
            
            if ($differentShowtime > 0) {
                return back()->with('error', 'Cannot book seats from different showtimes.');
            }

            $showtime = $seats->first()->showtime;

            // Validasi showtime masih akan datang
            $showDateTime = Carbon::parse($showtime->show_date . ' ' . $showtime->start_time);
            if ($showDateTime->isPast()) {
                return back()->with('error', 'Showtime sudah lewat.');
            }

            foreach ($seats as $seat) {
                // Prevent double booking
                if ($seat->status === 'booked') {
                    return back()->with('error', 'Seat ' . $seat->seat->seat_number . ' already booked.');
                }

                // Check ownership and expiration
                if ($seat->status !== 'locked' || $seat->locked_by !== $user->id) {
                    return back()->with('error', 'Seat ' . $seat->seat->seat_number . ' not locked by you.');
                }

                // Check if lock expired during checkout
                if (! $seat->locked_at || Carbon::parse($seat->locked_at)->addMinutes(5)->isPast()) {
                    return back()->with('error', 'Seat lock expired. Please try again.');
                }
            }

            $totalPrice = $showtime->price * $seats->count();

            $order = Order::create([
                'user_id' => $user->id,
                'showtime_id' => $showtime->id,
                'booking_code' => strtoupper(Str::random(8)),
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_method' => 'midtrans',
                'expires_at' => Carbon::now()->addMinutes(10), // NEW 10-minute timer for payment
            ]);

            // Buat tickets untuk setiap kursi
            foreach ($seats as $seat) {
                Ticket::create([
                    'order_id' => $order->id,
                    'seat_id' => $seat->seat_id,
                    'price' => $showtime->price,
                    'qr_code' => Str::uuid(),
                ]);

                // Update status showtime_seat menjadi booked
                $seat->update([
                    'status' => 'booked',
                    'locked_at' => null,
                    'locked_by' => null,
                ]);
            }

            // Buat record payment
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'midtrans',
                'payment_status' => 'pending',
                'amount' => $totalPrice,
            ]);

            // Buat SNAP Token dan arahkan ke halaman pembayaran
            // Jika Midtrans belum dikonfigurasi, tampilkan pesan sukses
            try {
                $snapToken = $this->midtransPaymentService->createSnapToken($order);

                return redirect("https://app.sandbox.midtrans.com/snap/v1/pay?token={$snapToken}");
            } catch (\Exception $e) {
                // Jika Midtrans tidak terkonfigurasi, anggap pembayaran berhasil untuk testing
                $order->update(['status' => 'pending']);

                return redirect('/my-orders/'.$order->id)
                    ->with('success', 'Order berhasil dibuat! Booking Code: '.$order->booking_code);
            }
        });
    }

    /**
     * Check if selected seats are still locked and valid
     */
    public function checkSeatsStatus(Request $request)
    {
        $request->validate([
            'showtime_seat_ids' => 'required|array',
            'showtime_seat_ids.*' => 'required|integer|exists:showtime_seats,id',
        ]);

        $userId = Auth::id();
        $now = Carbon::now();

        $seats = ShowtimeSeat::whereIn('id', $request->showtime_seat_ids)->get();

        $invalidSeats = [];
        $validSeats = [];
        $totalTimeRemaining = 0;

        foreach ($seats as $seat) {
            // Check if seat is locked by current user
            if ($seat->status !== 'locked' || $seat->locked_by !== $userId) {
                $invalidSeats[] = [
                    'seat_id' => $seat->id,
                    'seat_number' => $seat->seat->seat_number,
                    'reason' => 'Seat not locked by you (Status: ' . $seat->status . ', Locked by: ' . $seat->locked_by . ')',
                ];
                continue;
            }

            // Calculate expiration time
            $lockedAt = Carbon::parse($seat->locked_at);
            $expiresAt = $lockedAt->copy()->addMinutes(5);
            $timeRemaining = $now->diffInSeconds($expiresAt, false); // false = allow negative

            // Check if lock is expired (give 10 seconds grace period)
            if ($timeRemaining < -10) {
                $invalidSeats[] = [
                    'seat_id' => $seat->id,
                    'seat_number' => $seat->seat->seat_number,
                    'reason' => 'Lock expired (' . $timeRemaining . 's ago)',
                ];
                continue;
            }

            // Seat is valid (even if time is low or slightly negative)
            $validSeats[] = [
                'seat_id' => $seat->id,
                'seat_number' => $seat->seat->seat_number,
                'expires_at' => $expiresAt->toIso8601String(),
                'expires_in_seconds' => max(0, $timeRemaining), // Don't return negative
                'locked_at' => $lockedAt->toIso8601String(),
            ];
            
            $totalTimeRemaining += $timeRemaining;
        }

        // Calculate average time remaining for valid seats
        $avgTimeRemaining = count($validSeats) > 0 ? floor($totalTimeRemaining / count($validSeats)) : 0;

        return response()->json([
            'valid' => count($invalidSeats) === 0,
            'valid_seats' => $validSeats,
            'invalid_seats' => $invalidSeats,
            'time_remaining' => $avgTimeRemaining,
            'debug' => [
                'server_time' => $now->toIso8601String(),
                'total_seats' => $seats->count(),
                'valid_count' => count($validSeats),
                'invalid_count' => count($invalidSeats),
            ],
        ]);
    }
}

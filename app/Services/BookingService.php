<?php

namespace App\Services;

use App\Mail\BookingExpiredMail;
use App\Models\Order;
use App\Models\ShowtimeSeat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingService
{
    /**
     * Release expired locked seats for a specific showtime
     * Reusable for targeted cleanup
     */
    public function releaseExpiredLocksForShowtime(int $showtimeId): int
    {
        return ShowtimeSeat::where('showtime_id', $showtimeId)
            ->where('status', 'locked')
            ->whereNotNull('locked_at')
            ->where('locked_at', '<=', Carbon::now()->subMinutes(5))
            ->update([
                'status' => 'available',
                'locked_at' => null,
                'locked_by' => null,
            ]);
    }

    /**
     * Release all expired locked seats system-wide
     * Main reusable method for global cleanup
     */
    public function releaseAllExpiredLocks(): int
    {
        return ShowtimeSeat::where('status', 'locked')
            ->whereNotNull('locked_at')
            ->where('locked_at', '<=', Carbon::now()->subMinutes(5))
            ->update([
                'status' => 'available',
                'locked_at' => null,
                'locked_by' => null,
            ]);
    }

    /**
     * Release expired locks with transaction safety
     * More robust for concurrent operations
     */
    public function releaseExpiredLocksSafely(): int
    {
        return DB::transaction(function () {
            return ShowtimeSeat::where('status', 'locked')
                ->whereNotNull('locked_at')
                ->where('locked_at', '<=', Carbon::now()->subMinutes(5))
                ->update([
                    'status' => 'available',
                    'locked_at' => null,
                    'locked_by' => null,
                ]);
        });
    }

    /**
     * Release expired locks for specific user
     * Useful for user-specific cleanup
     */
    public function releaseExpiredLocksForUser(int $userId): int
    {
        return ShowtimeSeat::where('locked_by', $userId)
            ->where('status', 'locked')
            ->whereNotNull('locked_at')
            ->where('locked_at', '<=', Carbon::now()->subMinutes(5))
            ->update([
                'status' => 'available',
                'locked_at' => null,
                'locked_by' => null,
            ]);
    }

    /**
     * Cancel expired pending orders and release their seats
     */
    public function cancelExpiredOrders(): int
    {
        $expiredOrders = Order::where('status', 'pending')
            ->where('expires_at', '<', Carbon::now())
            ->with(['tickets', 'user'])
            ->get();

        $count = 0;

        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order) {
                // Collect seat updates for bulk operation
                $seatUpdates = [];
                foreach ($order->tickets as $ticket) {
                    $seatUpdates[] = [
                        'showtime_id' => $order->showtime_id,
                        'seat_id' => $ticket->seat_id,
                    ];
                }

                // Bulk update seats using query builder
                if (! empty($seatUpdates)) {
                    foreach ($seatUpdates as $seat) {
                        DB::table('showtime_seats')
                            ->where($seat)
                            ->update([
                                'status' => 'available',
                                'locked_at' => null,
                                'locked_by' => null,
                            ]);
                    }
                }

                // Cancel order
                $order->update(['status' => 'canceled']);

                // Send notification email
                try {
                    Mail::to($order->user->email)->send(new BookingExpiredMail($order));
                } catch (\Exception $e) {
                    // Log error but don't fail the process
                    Log::error('Failed to send booking expired email: '.$e->getMessage());
                }
            });

            $count++;
        }

        return $count;
    }
}

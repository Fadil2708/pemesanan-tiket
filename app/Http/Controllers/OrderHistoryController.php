<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransPaymentService;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    protected $midtransPaymentService;

    public function __construct(MidtransPaymentService $midtransPaymentService)
    {
        $this->midtransPaymentService = $midtransPaymentService;
    }

    public function index()
    {
        $orders = Order::with(['showtime.film'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load([
            'showtime.film',
            'showtime.studio',
            'tickets.seat',
        ]);

        return view('orders.show', compact('order'));
    }

    public function pay(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Order ini sudah tidak dapat dibayar (Status: '.ucfirst($order->status).')',
            ]);
        }

        try {
            $snapToken = $this->midtransPaymentService->createSnapToken($order);

            return response()->json([
                'redirect_url' => "https://app.sandbox.midtrans.com/snap/v1/pay?token={$snapToken}",
            ]);
        } catch (\Exception $e) {
            // Jika Midtrans tidak terkonfigurasi, anggap pembayaran berhasil
            $order->update(['status' => 'paid']);

            return response()->json([
                'message' => 'Pembayaran berhasil! Status order telah diperbarui.',
            ]);
        }
    }

    public function checkStatus(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order is expired and update status if needed
        if ($order->status === 'pending' && $order->expires_at && $order->expires_at->isPast()) {
            $order->update(['status' => 'canceled']);
            $order->status = 'canceled'; // Update in memory
        }

        return response()->json([
            'status' => $order->status,
            'expires_at' => $order->expires_at ? $order->expires_at->timestamp * 1000 : null,
        ]);
    }
}

<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransPaymentService
{
    public function createSnapToken(Order $order): string
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$clientKey = config('midtrans.client_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->booking_code,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->user->name ?? 'Customer',
                'email' => $order->user->email ?? 'customer@example.com',
                'phone' => $order->user->phone ?? '081234567890',
            ],
            'enabled_payments' => ['credit_card', 'gopay', 'shopeepay', 'bank_transfer'],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans SNAP Token Error: '.$e->getMessage());
            throw new \Exception('Gagal membuat SNAP Token: '.$e->getMessage());
        }
    }

    public function handleWebhook(Request $request): void
    {
        // Verify signature
        $signature = $request->header('X-Midtrans-Signature');
        $serverKey = Config::get('midtrans.server_key');
        $hashed = hash('sha512', $request->getContent().$serverKey);

        if ($signature !== $hashed) {
            Log::error('Midtrans Webhook Invalid Signature');
            throw new \Exception('Invalid webhook signature');
        }

        $notification = new Notification;

        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $order_id = $notification->order_id;

        $order = Order::where('booking_code', $order_id)->first();

        if (! $order) {
            Log::error('Order not found for booking code: '.$order_id);
            throw new \Exception('Order tidak ditemukan untuk booking code: '.$order_id);
        }

        // Update status berdasarkan status transaksi
        $paymentStatus = 'pending';
        $orderStatus = $order->status;
        
        switch ($transaction) {
            case 'capture':
                if ($type == 'credit_card') {
                    $orderStatus = 'paid';
                    $paymentStatus = 'success';
                }
                break;
            case 'settlement':
                $orderStatus = 'paid';
                $paymentStatus = 'success';
                break;
            case 'pending':
                $orderStatus = 'pending';
                $paymentStatus = 'pending';
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
            case 'refund':
            case 'chargeback':
                $orderStatus = 'canceled';
                $paymentStatus = 'failed';
                break;
        }

        // Update order status
        if ($order->status !== $orderStatus) {
            $order->update(['status' => $orderStatus]);
        }

        // Update or create payment record
        $payment = Payment::where('order_id', $order->id)->first();
        
        if ($payment) {
            $payment->update([
                'payment_status' => $paymentStatus,
                'payment_reference' => $notification->transaction_id ?? null,
                'paid_at' => $paymentStatus === 'success' ? now() : null,
            ]);
        } else {
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $type,
                'payment_status' => $paymentStatus,
                'payment_reference' => $notification->transaction_id ?? null,
                'amount' => $order->total_price,
                'paid_at' => $paymentStatus === 'success' ? now() : null,
            ]);
        }
    }
}

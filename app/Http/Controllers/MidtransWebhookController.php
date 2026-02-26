<?php

namespace App\Http\Controllers;

use App\Services\MidtransPaymentService;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    protected $midtransPaymentService;

    public function __construct(MidtransPaymentService $midtransPaymentService)
    {
        $this->midtransPaymentService = $midtransPaymentService;
    }

    public function handle(Request $request)
    {
        try {
            $this->midtransPaymentService->handleWebhook();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Midtrans Webhook Error: '.$e->getMessage());

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}

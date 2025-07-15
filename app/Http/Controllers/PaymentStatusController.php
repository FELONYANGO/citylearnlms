<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\JsonResponse;

class PaymentStatusController extends Controller
{
    /**
     * Get the current status of a payment
     *
     * @param Payment $payment
     * @return JsonResponse
     */
    public function checkStatus(Payment $payment): JsonResponse
    {
        return response()->json([
            'status' => $payment->status,
            'message' => match ($payment->status) {
                Payment::STATUS_COMPLETED => 'Payment successful',
                Payment::STATUS_FAILED => 'Payment failed',
                default => 'Payment pending'
            }
        ]);
    }
}

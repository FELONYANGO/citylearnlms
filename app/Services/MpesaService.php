<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Techlup\PaymentGateway\Mpesa\StkPush;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    protected $config;

    public function __construct()
    {
        $this->config = [
            'CONSUMER_KEY' => config('services.mpesa.consumer_key'),
            'CONSUMER_SECRET' => config('services.mpesa.consumer_secret'),
            'PASS_KEY' => config('services.mpesa.pass_key'),
            'SANDBOX' => config('services.mpesa.sandbox', true),
        ];
    }

    public function initiateSTKPush(Order $order, string $phone)
    {
        // Check for pending payment
        if ($order->payments()->where('status', 'pending')->exists()) {
            throw new \Exception('A payment is already in progress for this order.');
        }

        try {
            // Format phone number
            $phone = preg_replace('/^(?:\+?254|0)?/', '254', $phone);

            // Initialize STK Push
            $stk = new StkPush($this->config);
            $response = $stk->setCallbackUrl(route('mpesa.callback'))
                ->setAmount((string)$order->total_amount)
                ->setPhone($phone)
                ->setPartyB(config('services.mpesa.shortcode'))
                ->setShortCode(config('services.mpesa.shortcode'))
                ->setReference($order->id) // Order ID as reference
                ->setRemarks("Payment for Order #{$order->id}")
                ->tillRequestPush();

            if ($response->ResponseCode !== "0") {
                throw new \Exception($response->ResponseDescription);
            }

            // Create payment record
            $payment = Payment::create([
                'user_id' => $order->user_id,
                'amount' => $order->total_amount,
                'method' => 'mpesa',
                'reference' => $response->CheckoutRequestID, // Use CheckoutRequestID as reference
                'paid_for' => $order->items->first()->orderable_type === 'App\Models\Course' ? 'course' : 'program',
                'paid_id' => $order->items->first()->orderable_id,
                'status' => 'pending',
                'meta_data' => json_encode([
                    'merchant_request_id' => $response->MerchantRequestID,
                    'checkout_request_id' => $response->CheckoutRequestID,
                    'phone_number' => $phone,
                    'order_id' => $order->id
                ])
            ]);

            return [
                'success' => true,
                'message' => 'Please check your phone to complete payment',
                'data' => $response
            ];
        } catch (\Exception $e) {
            Log::error('MPesa STK Push failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to initiate payment: ' . $e->getMessage()
            ];
        }
    }

    public function handleCallback($callbackData)
    {
        try {
            // Get the payment using CheckoutRequestID from the reference
            $payment = Payment::where('reference', $callbackData->CheckoutRequestID)->firstOrFail();
            $meta = json_decode($payment->meta_data, true);

            if ($callbackData->status === 0) {
                // Payment successful
                $payment->update([
                    'status' => 'confirmed',
                    'paid_at' => now(),
                    'meta_data' => json_encode(array_merge($meta, [
                        'mpesa_receipt' => $callbackData->MpesaReceiptNumber,
                        'transaction_date' => $callbackData->TransactionDate,
                        'phone_number' => $callbackData->PhoneNumber
                    ]))
                ]);

                // Get and update the order
                $order = Order::findOrFail($meta['order_id']);
                $order->update(['status' => 'completed']);

                // Process the successful payment
                app(OrderService::class)->processSuccessfulPayment($order, [
                    'payment_method' => 'mpesa',
                    'transaction_id' => $callbackData->MpesaReceiptNumber
                ]);

                return true;
            } else {
                // Payment failed
                $payment->update([
                    'status' => 'failed',
                    'meta_data' => json_encode(array_merge($meta, [
                        'result_code' => $callbackData->status,
                        'result_desc' => $callbackData->ResultDesc
                    ]))
                ]);

                return false;
            }
        } catch (\Exception $e) {
            Log::error('MPesa callback processing failed', [
                'error' => $e->getMessage(),
                'data' => $callbackData
            ]);
            return false;
        }
    }
}

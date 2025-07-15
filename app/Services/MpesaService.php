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

        if ($order->payment()->where('status', 'pending')->exists()) {
            throw new \Exception('A payment is already in progress for this order.');
        }
        //  dd($phone);
        try {
            // Format phone number

            $stk = new StkPush($this->config);
            //dd all the data placed int the response below to check if it is working

            $response = $stk->setCallbackUrl(route('mpesa.callback'))
                ->setAmount((string)ceil($order->total_amount))
                ->setPhone($phone)
                ->setPartyB(config('services.mpesa.shortcode'))
                ->setShortCode(config('services.mpesa.shortcode'))
                ->setReference($order->id)
                ->setRemarks("Payment for Order #{$order->id}")
                ->tillRequestPush();

            if ($response->ResponseCode !== "0") {
                throw new \Exception($response->ResponseDescription);
            }

            Log::info('MPesa STK Push successful', [
                'order_id' => $order->id,
                'response' => $response
            ]);

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'currency' => 'KES',
                'payment_method' => Payment::METHOD_MPESA,
                'status' => Payment::STATUS_PENDING,
                'phone_number' => $phone,
                'transaction_reference' => $response->CheckoutRequestID,
                'meta_data' => json_encode([
                    'merchant_request_id' => $response->MerchantRequestID,
                    'checkout_request_id' => $response->CheckoutRequestID,
                    'phone_number' => $phone,
                    'order_id' => $order->id
                ])
            ]);
            Log::info('MPesa STK Push successful', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'response' => $response
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

            // Get the order and its purchasable item (course/program)
            $order = Order::with('items.orderable')->findOrFail($meta['order_id']);
            $purchasable = $order->items->first()->orderable;
            $expectedAmount = $purchasable->price ?? $purchasable->total_fee;

            if ($callbackData->status === 0) {
                $paidAmount = collect($callbackData->CallbackMetadata->Item)
                    ->where('Name', 'Amount')
                    ->first()->Value ?? 0;

                // Verify the amount paid matches the course/program price
                if ($paidAmount != $expectedAmount) {
                    Log::error('Payment amount mismatch', [
                        'payment_id' => $payment->id,
                        'order_id' => $order->id,
                        'purchasable_type' => get_class($purchasable),
                        'purchasable_id' => $purchasable->id,
                        'expected_amount' => $expectedAmount,
                        'paid_amount' => $paidAmount
                    ]);

                    $payment->update([
                        'status' => Payment::STATUS_FAILED,
                        'meta_data' => json_encode(array_merge($meta, [
                            'error' => 'Amount mismatch',
                            'expected_amount' => $expectedAmount,
                            'paid_amount' => $paidAmount,
                            'purchasable_type' => get_class($purchasable),
                            'purchasable_id' => $purchasable->id
                        ]))
                    ]);

                    return false;
                }

                // Payment successful
                $payment->update([
                    'status' => Payment::STATUS_COMPLETED,
                    'paid_at' => now(),
                    'meta_data' => json_encode(array_merge($meta, [
                        'mpesa_receipt' => $callbackData->MpesaReceiptNumber,
                        'transaction_date' => $callbackData->TransactionDate,
                        'phone_number' => $callbackData->PhoneNumber,
                        'paid_amount' => $paidAmount,
                        'purchasable_type' => get_class($purchasable),
                        'purchasable_id' => $purchasable->id
                    ]))
                ]);

                // Update order status
                $order->update(['status' => 'completed']);

                // Process the successful payment
                app(OrderService::class)->processSuccessfulPayment($order, [
                    'payment_method' => Payment::METHOD_MPESA,
                    'transaction_id' => $callbackData->MpesaReceiptNumber
                ]);

                return true;
            } else {
                // Payment failed
                $payment->update([
                    'status' => Payment::STATUS_FAILED,
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

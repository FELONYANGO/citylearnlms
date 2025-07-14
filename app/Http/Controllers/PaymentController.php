<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Show payment processing page
     */
    public function process(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this order.');
        }

        if ($order->status === 'completed') {
            return redirect()->route('payments.success', $order);
        }

        return view('payments.process', compact('order'));
    }

    /**
     * Show payment processing status page (after STK push initiated)
     */
    public function processing(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this order.');
        }

        if ($order->status === 'completed') {
            return redirect()->route('payments.success', $order);
        }

        return view('payments.processing', compact('order'));
    }

    /**
     * Initiate payment with payment provider
     */
    public function initiate(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this order.');
        }

        if ($order->status !== 'pending') {
            return redirect()->route('payments.process', $order)
                ->with('error', 'This order has already been processed.');
        }

        $request->validate([
            'phone' => 'required',
            'terms' => 'required|accepted'
        ]);

        try {
            // Format and validate phone number
            $phone = preg_replace('/^(?:\+?254|0)?/', '254', $request->phone);
            if (!preg_match('/^254\d{9}$/', $phone)) {
                Log::error('Invalid phone number format for order ID: ' . $order->id, ['phone' => $phone]);
                return redirect()->back()
                    ->withErrors(['phone' => 'Please enter a valid Kenyan phone number'])
                    ->withInput();
            }

            $result = app(MpesaService::class)->initiateSTKPush($order, $phone);

            if (!$result['success']) {
                return redirect()->back()
                    ->with('error', $result['message']);
            }

            return redirect()->route('payments.processing', $order)
                ->with('success', 'Payment initiated. Please check your phone to complete the payment.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to initiate payment. Please try again.');
        }
    }

    /**
     * Show success page after successful payment
     */
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this order.');
        }

        if ($order->status !== 'completed') {
            return redirect()->route('payments.process', $order);
        }

        $purchasable = $order->items->first()->orderable;
        $type = $purchasable instanceof \App\Models\Course ? 'course' : 'program';

        return view('payments.success', [
            'order' => $order,
            'purchasable' => $purchasable,
            'type' => $type
        ]);
    }

    /**
     * Handle cancelled payment
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this order.');
        }

        $order->update(['status' => 'cancelled']);

        return view('payments.cancelled', compact('order'));
    }
}

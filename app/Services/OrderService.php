<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Course;
use App\Models\TrainingProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Create a new order for a purchasable item
     */
    public function createOrder(User $user, Model $purchasable, string $currency = 'KES'): Order
    {
        return DB::transaction(function () use ($user, $purchasable, $currency) {
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $purchasable->getPurchaseAmount(),
                'currency' => $currency,
                'status' => 'pending'
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'orderable_type' => get_class($purchasable),
                'orderable_id' => $purchasable->id,
                'price' => $purchasable->getPurchaseAmount(),
                'currency' => $currency
            ]);

            return $order->load('items');
        });
    }

    /**
     * Process a successful payment for an order
     */
    public function processSuccessfulPayment(Order $order, array $paymentData): void
    {
        DB::transaction(function () use ($order, $paymentData) {
            // Update order status
            $order->update(['status' => 'completed']);

            // Create payment record
            $order->payments()->create([
                'amount' => $order->total_amount,
                'currency' => $order->currency,
                'payment_method' => $paymentData['payment_method'] ?? 'unknown',
                'transaction_id' => $paymentData['transaction_id'] ?? null,
                'status' => 'completed'
            ]);

            // Process each order item
            foreach ($order->items as $item) {
                $this->processOrderItem($item);
            }
        });
    }

    /**
     * Process individual order items after successful payment
     */
    private function processOrderItem(OrderItem $item): void
    {
        $orderable = $item->orderable;

        if ($orderable instanceof Course) {
            $orderable->enrollments()->create([
                'user_id' => $item->order->user_id,
                'status' => 'active',
                'enrolled_at' => now()
            ]);
        } elseif ($orderable instanceof TrainingProgram) {
            $orderable->enrollments()->create([
                'user_id' => $item->order->user_id,
                'status' => 'active',
                'enrolled_at' => now()
            ]);
        }
    }
}

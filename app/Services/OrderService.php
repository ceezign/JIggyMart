<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Pure order-record concerns: creating the order + line items from an
 * already-validated cart snapshot, and transitioning order status.
 * Inventory and payment side effects are handled by CheckoutService,
 * which composes this with InventoryService/PaymentService inside one
 * DB transaction.
 */
class OrderService
{
    public function createOrder(User $user, Collection $cartItems, Address $address, array $totals): Order
    {
        $order = Order::create([
            'user_id' => $user->id,
            'shipping_address_id' => $address->id,
            'subtotal' => $totals['subtotal'],
            'discount_total' => $totals['discount_total'],
            'shipping_total' => $totals['shipping_total'],
            'tax_total' => $totals['tax_total'],
            'grand_total' => $totals['grand_total'],
            'currency' => 'NGN',
            'status' => Order::STATUS_PENDING,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'seller_id' => $item->product->seller_id,
                'product_variant_id' => $item->product_variant_id,
                'product_name' => $item->product->name,
                'product_sku' => $item->variant->sku ?? $item->product->sku,
                'unit_price' => $item->price_snapshot,
                'quantity' => $item->quantity,
                'line_total' => $item->line_total,
            ]);
        }

        return $order->load('items');
    }

    public function markPaid(Order $order): Order
    {
        $order->update(['status' => Order::STATUS_PAID]);

        return $order;
    }

    public function markProcessing(Order $order): Order
    {
        $order->update(['status' => Order::STATUS_PROCESSING]);

        return $order;
    }

    public function markFailed(Order $order): Order
    {
        $order->update(['status' => Order::STATUS_CANCELLED]);

        return $order;
    }

    public function markShipped(Order $order): Order
    {
        $order->update(['status' => Order::STATUS_SHIPPED]);

        return $order;
    }

    public function markDelivered(Order $order): Order
    {
        $order->update(['status' => Order::STATUS_DELIVERED]);

        return $order;
    }

    public function cancel(Order $order): Order
    {
        $order->update(['status' => Order::STATUS_CANCELLED]);

        return $order;
    }

    public function refund(Order $order): Order
    {
        $order->update(['status' => Order::STATUS_REFUNDED]);

        return $order;
    }
}

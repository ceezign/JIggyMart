<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Events\PaymentFailed as PaymentFailedEvent;
use App\Events\PaymentSuccessful;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvalidCartException;
use App\Exceptions\PaymentFailedException;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * The single entry point for turning a cart into a paid order.
 *
 * All totals are recomputed here from live product prices — nothing from
 * the client (cart snapshot prices, shipping, tax, "total") is trusted.
 * Order creation, inventory decrement, and payment happen inside one DB
 * transaction so the system never ends up with an order that has no
 * matching inventory reservation, or inventory reserved with no order.
 */
class CheckoutService
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService,
        private readonly InventoryService $inventoryService,
        private readonly PaymentService $paymentService,
    ) {
    }

    /**
     * @throws InvalidCartException
     * @throws InsufficientStockException
     * @throws PaymentFailedException
     */
    public function checkout(User $user, int $addressId, string $paymentMethod = 'mock'): Order
    {
        $cart = $this->cartService->getOrCreateCart($user);
        $cart->load('items.product', 'items.variant');

        if ($cart->items->isEmpty()) {
            throw new InvalidCartException('Your cart is empty.');
        }

        $address = Address::where('user_id', $user->id)->findOrFail($addressId);

        // Re-validate stock and re-price every line from the live product row
        // before anything is persisted.
        foreach ($cart->items as $item) {
            $product = $item->product()->lockForUpdate()->first();
            $available = $item->variant ? $item->variant->fresh()->stock_quantity : $product->stock_quantity;

            if ($product->status !== \App\Models\Product::STATUS_ACTIVE || $available < $item->quantity) {
                throw new InsufficientStockException($product->name, $available);
            }

            $livePrice = (float) $product->effective_price + (float) ($item->variant->price_adjustment ?? 0);
            $item->price_snapshot = $livePrice;
        }

        $totals = $this->calculateTotals($cart->items);

        $order = DB::transaction(function () use ($user, $cart, $address, $totals) {
            $order = $this->orderService->createOrder($user, $cart->items, $address, $totals);

            foreach ($cart->items as $item) {
                $this->inventoryService->reserve($item->product_id, $item->quantity, $item->product_variant_id);
            }

            return $order;
        });

        event(new OrderPlaced($order));

        try {
            $transaction = $this->paymentService->initiate($order, $paymentMethod);
            $transaction = $this->paymentService->charge($transaction);

            DB::transaction(function () use ($order) {
                $this->orderService->markPaid($order);
            });

            $this->cartService->clear($cart);

            event(new PaymentSuccessful($order, $transaction));

            return $order->fresh(['items', 'transaction']);
        } catch (PaymentFailedException $e) {
            // Payment failed after inventory was reserved for it: release the
            // stock and mark the order cancelled rather than leaving it stuck
            // "pending" against inventory nobody can buy.
            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    $this->inventoryService->release($item->product_id, $item->quantity, $item->product_variant_id);
                }
                $this->orderService->markFailed($order);
            });

            event(new PaymentFailedEvent($order));

            Log::warning('Checkout payment failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);

            throw $e;
        }
    }

    private function calculateTotals($cartItems): array
    {
        $subtotal = round((float) $cartItems->sum(fn ($i) => $i->price_snapshot * $i->quantity), 2);

        $shippingTotal = $subtotal > 0 ? 2000.00 : 0.0; // flat-rate shipping; swap for a real shipping engine later
        $taxRate = 0.0; // no VAT modeled by default; adjust per jurisdiction
        $taxTotal = round($subtotal * $taxRate, 2);
        $discountTotal = 0.0; // reserved for coupon/promo logic

        $grandTotal = round($subtotal + $shippingTotal + $taxTotal - $discountTotal, 2);

        return [
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'shipping_total' => $shippingTotal,
            'tax_total' => $taxTotal,
            'grand_total' => $grandTotal,
        ];
    }
}

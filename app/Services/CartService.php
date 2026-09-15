<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;

/**
 * Handles all cart mutations. Stock is always re-checked against the live
 * product row; the frontend never gets to decide what's in stock.
 */
class CartService
{
    public function getOrCreateCart(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function addItem(User $user, Product $product, int $quantity, ?int $variantId = null): CartItem
    {
        if ($quantity < 1) {
            $quantity = 1;
        }

        $variant = $variantId ? ProductVariant::where('product_id', $product->id)->findOrFail($variantId) : null;
        $availableStock = $variant ? $variant->stock_quantity : $product->stock_quantity;

        if (! $product->is_in_stock && ! $variant) {
            throw new InsufficientStockException($product->name, $availableStock);
        }

        $cart = $this->getOrCreateCart($user);

        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
        ]);

        $newQuantity = ($item->exists ? $item->quantity : 0) + $quantity;

        if ($newQuantity > $availableStock) {
            throw new InsufficientStockException($product->name, $availableStock);
        }

        $price = (float) $product->effective_price + (float) ($variant->price_adjustment ?? 0);

        $item->quantity = $newQuantity;
        $item->price_snapshot = $price;
        $item->save();

        return $item;
    }

    public function updateQuantity(User $user, CartItem $item, int $quantity): CartItem
    {
        $this->assertOwnership($user, $item);

        $availableStock = $item->variant ? $item->variant->stock_quantity : $item->product->stock_quantity;

        if ($quantity < 1) {
            $item->delete();

            return $item;
        }

        if ($quantity > $availableStock) {
            throw new InsufficientStockException($item->product->name, $availableStock);
        }

        $item->update(['quantity' => $quantity]);

        return $item;
    }

    public function removeItem(User $user, CartItem $item): void
    {
        $this->assertOwnership($user, $item);
        $item->delete();
    }

    public function clear(Cart $cart): void
    {
        $cart->items()->delete();
    }

    public function totals(Cart $cart): array
    {
        $items = $cart->items()->with(['product', 'variant'])->get();

        $subtotal = $items->sum(fn (CartItem $item) => $item->line_total);

        return [
            'items' => $items,
            'subtotal' => round($subtotal, 2),
            'item_count' => $items->sum('quantity'),
        ];
    }

    private function assertOwnership(User $user, CartItem $item): void
    {
        if ($item->cart->user_id !== $user->id) {
            abort(403, 'This cart item does not belong to you.');
        }
    }
}

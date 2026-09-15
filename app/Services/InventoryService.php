<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

/**
 * Every stock mutation goes through here so it can be locked and audited
 * consistently. Always call within an existing DB transaction (see
 * CheckoutService/OrderService) to avoid partial updates.
 */
class InventoryService
{
    /**
     * Lock the product row and decrement stock. Throws if insufficient.
     */
    public function reserve(int $productId, int $quantity, ?int $variantId = null): void
    {
        $product = Product::where('id', $productId)->lockForUpdate()->first();

        if (! $product) {
            throw new InsufficientStockException('Unknown product', 0);
        }

        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)->lockForUpdate()->first();

            if (! $variant || $variant->stock_quantity < $quantity) {
                throw new InsufficientStockException($product->name, $variant->stock_quantity ?? 0);
            }

            $variant->decrement('stock_quantity', $quantity);
        }

        if ($product->stock_quantity < $quantity) {
            throw new InsufficientStockException($product->name, $product->stock_quantity);
        }

        $product->decrement('stock_quantity', $quantity);
        $product->increment('sales_count', $quantity);

        $product->refresh();

        if ($product->stock_quantity <= 0 && $product->status === Product::STATUS_ACTIVE) {
            $product->update(['status' => Product::STATUS_OUT_OF_STOCK]);
        }
    }

    /**
     * Restore stock, e.g. on cancellation/refund.
     */
    public function release(int $productId, int $quantity, ?int $variantId = null): void
    {
        DB::transaction(function () use ($productId, $quantity, $variantId) {
            $product = Product::where('id', $productId)->lockForUpdate()->first();

            if (! $product) {
                return;
            }

            $product->increment('stock_quantity', $quantity);

            if ($variantId) {
                ProductVariant::where('id', $variantId)->lockForUpdate()->increment('stock_quantity', $quantity);
            }

            if ($product->status === Product::STATUS_OUT_OF_STOCK && $product->fresh()->stock_quantity > 0) {
                $product->update(['status' => Product::STATUS_ACTIVE]);
            }
        });
    }

    public function isAvailable(Product $product, int $quantity, ?ProductVariant $variant = null): bool
    {
        $available = $variant ? $variant->stock_quantity : $product->stock_quantity;

        return $product->status === Product::STATUS_ACTIVE && $available >= $quantity;
    }
}

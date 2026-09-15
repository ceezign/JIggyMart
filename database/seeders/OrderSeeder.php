<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))->get();
        $products = Product::where('status', Product::STATUS_ACTIVE)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($customers->random(min(10, $customers->count())) as $customer) {
            $address = Address::firstOrCreate(
                ['user_id' => $customer->id, 'label' => 'Home'],
                [
                    'full_name' => $customer->name,
                    'phone' => '0800'.random_int(1000000, 9999999),
                    'line1' => fake()->streetAddress(),
                    'city' => fake()->city(),
                    'state' => 'Lagos',
                    'country' => 'Nigeria',
                    'is_default' => true,
                ]
            );

            $orderProducts = $products->random(random_int(1, 3));
            $subtotal = 0;

            $order = Order::create([
                'order_number' => 'ORD-'.strtoupper(\Illuminate\Support\Str::random(10)),
                'user_id' => $customer->id,
                'shipping_address_id' => $address->id,
                'subtotal' => 0,
                'shipping_total' => 2000,
                'grand_total' => 0,
                'status' => fake()->randomElement([Order::STATUS_DELIVERED, Order::STATUS_PAID, Order::STATUS_SHIPPED, Order::STATUS_PENDING]),
            ]);

            foreach ($orderProducts as $product) {
                $qty = random_int(1, 3);
                $lineTotal = (float) $product->effective_price * $qty;
                $subtotal += $lineTotal;

                $item = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'seller_id' => $product->seller_id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'unit_price' => $product->effective_price,
                    'quantity' => $qty,
                    'line_total' => $lineTotal,
                ]);

                if ($order->status === Order::STATUS_DELIVERED && fake()->boolean(70)) {
                    Review::create([
                        'product_id' => $product->id,
                        'user_id' => $customer->id,
                        'order_item_id' => $item->id,
                        'rating' => random_int(3, 5),
                        'comment' => fake()->sentence(12),
                        'is_verified_purchase' => true,
                        'status' => 'approved',
                    ]);
                }
            }

            $order->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + 2000,
            ]);

            if (in_array($order->status, [Order::STATUS_PAID, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED], true)) {
                Transaction::create([
                    'reference' => 'TXN-'.strtoupper(\Illuminate\Support\Str::random(12)),
                    'order_id' => $order->id,
                    'user_id' => $customer->id,
                    'amount' => $order->grand_total,
                    'currency' => 'NGN',
                    'payment_method' => 'mock',
                    'status' => Transaction::STATUS_SUCCESSFUL,
                    'gateway_reference' => 'MOCK-'.strtoupper(\Illuminate\Support\Str::random(10)),
                ]);
            }
        }

        // Recalculate rating aggregates after seeding reviews.
        foreach (Product::has('reviews')->get() as $product) {
            $stats = $product->reviews()->selectRaw('AVG(rating) as avg_rating, COUNT(*) as cnt')->first();
            $product->update([
                'average_rating' => round($stats->avg_rating ?? 0, 2),
                'reviews_count' => $stats->cnt ?? 0,
            ]);
        }
    }
}

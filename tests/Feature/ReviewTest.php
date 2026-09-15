<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_customer_can_review_a_product_from_a_delivered_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => Order::STATUS_DELIVERED]);
        $orderItem = OrderItem::create([
            'order_id' => $order->id, 'product_id' => $product->id, 'seller_id' => $product->seller_id,
            'product_name' => $product->name, 'product_sku' => $product->sku, 'unit_price' => $product->price,
            'quantity' => 1, 'line_total' => $product->price,
        ]);

        $response = $this->actingAs($user)->post('/reviews', [
            'order_item_id' => $orderItem->id,
            'rating' => 5,
            'comment' => 'Excellent product!',
        ]);

        $this->assertDatabaseHas('reviews', ['product_id' => $product->id, 'user_id' => $user->id, 'rating' => 5]);

        $product->refresh();
        $this->assertEquals(5.00, (float) $product->average_rating);
        $this->assertEquals(1, $product->reviews_count);
    }

    public function test_a_customer_cannot_review_a_product_from_an_undelivered_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => Order::STATUS_PENDING]);
        $orderItem = OrderItem::create([
            'order_id' => $order->id, 'product_id' => $product->id, 'seller_id' => $product->seller_id,
            'product_name' => $product->name, 'product_sku' => $product->sku, 'unit_price' => $product->price,
            'quantity' => 1, 'line_total' => $product->price,
        ]);

        $response = $this->actingAs($user)->post('/reviews', [
            'order_item_id' => $orderItem->id,
            'rating' => 5,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('reviews', ['product_id' => $product->id, 'user_id' => $user->id]);
    }

    public function test_a_customer_cannot_review_someone_elses_order(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create(['user_id' => $owner->id, 'status' => Order::STATUS_DELIVERED]);
        $orderItem = OrderItem::create([
            'order_id' => $order->id, 'product_id' => $product->id, 'seller_id' => $product->seller_id,
            'product_name' => $product->name, 'product_sku' => $product->sku, 'unit_price' => $product->price,
            'quantity' => 1, 'line_total' => $product->price,
        ]);

        $response = $this->actingAs($intruder)->post('/reviews', [
            'order_item_id' => $orderItem->id,
            'rating' => 1,
        ]);

        $response->assertForbidden();
    }
}

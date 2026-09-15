<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The most important test file in the suite: verifies the full
 * cart -> order -> inventory -> payment pipeline behaves correctly,
 * including the failure/rollback path.
 */
class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function setupCartFor(User $user, Product $product, int $qty = 2): CartItem
    {
        $cart = Cart::create(['user_id' => $user->id]);

        return CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $qty,
            'price_snapshot' => $product->price,
        ]);
    }

    public function test_a_successful_checkout_creates_an_order_reduces_stock_and_clears_the_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 5000, 'stock_quantity' => 10, 'status' => Product::STATUS_ACTIVE]);
        $this->setupCartFor($user, $product, 3);

        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/checkout', [
            'address_id' => $address->id,
            'payment_method' => 'mock',
        ]);

        $order = Order::where('user_id', $user->id)->first();

        $this->assertNotNull($order);
        $this->assertEquals(Order::STATUS_PAID, $order->status);
        $this->assertEquals(15000, $order->subtotal);

        $product->refresh();
        $this->assertEquals(7, $product->stock_quantity);

        $this->assertDatabaseHas('transactions', ['order_id' => $order->id, 'status' => Transaction::STATUS_SUCCESSFUL]);
        $this->assertEquals(0, CartItem::where('cart_id', $user->cart->id)->count());
    }

    public function test_checkout_fails_when_stock_is_insufficient_and_no_order_is_created(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 5000, 'stock_quantity' => 1, 'status' => Product::STATUS_ACTIVE]);
        $this->setupCartFor($user, $product, 5);

        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/checkout', [
            'address_id' => $address->id,
        ]);

        $response->assertSessionHasErrors('stock');
        $this->assertEquals(0, Order::where('user_id', $user->id)->count());

        $product->refresh();
        $this->assertEquals(1, $product->stock_quantity);
    }

    public function test_checkout_with_an_empty_cart_is_rejected(): void
    {
        $user = User::factory()->create();
        Cart::create(['user_id' => $user->id]);
        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/checkout', ['address_id' => $address->id]);

        $response->assertSessionHasErrors('cart');
        $this->assertEquals(0, Order::count());
    }

    public function test_checkout_never_trusts_a_client_supplied_price(): void
    {
        // Simulate a stale cart snapshot with a manipulated (too-low) price;
        // CheckoutService must re-price from the live product row regardless.
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100000, 'stock_quantity' => 5, 'status' => Product::STATUS_ACTIVE]);

        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price_snapshot' => 1, // tampered value
        ]);

        $address = Address::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->post('/checkout', ['address_id' => $address->id]);

        $order = Order::where('user_id', $user->id)->firstOrFail();

        $this->assertEquals(100000, $order->subtotal);
    }
}

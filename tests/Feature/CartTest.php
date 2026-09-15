<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_add_a_product_to_their_cart(): void
    {
        $user = User::factory()->create();
        Cart::create(['user_id' => $user->id]);
        $product = Product::factory()->create(['stock_quantity' => 5, 'status' => Product::STATUS_ACTIVE]);

        $response = $this->actingAs($user)->post('/cart', ['product_id' => $product->id, 'quantity' => 2]);

        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 2]);
    }

    public function test_cannot_add_more_than_available_stock(): void
    {
        $user = User::factory()->create();
        Cart::create(['user_id' => $user->id]);
        $product = Product::factory()->create(['stock_quantity' => 2, 'status' => Product::STATUS_ACTIVE]);

        $response = $this->actingAs($user)->post('/cart', ['product_id' => $product->id, 'quantity' => 5]);

        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id, 'quantity' => 5]);
    }

    public function test_a_user_can_update_cart_item_quantity(): void
    {
        $user = User::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);
        $product = Product::factory()->create(['stock_quantity' => 10, 'status' => Product::STATUS_ACTIVE]);

        $item = \App\Models\CartItem::create([
            'cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1, 'price_snapshot' => $product->price,
        ]);

        $this->actingAs($user)->patch("/cart/{$item->id}", ['quantity' => 4]);

        $this->assertDatabaseHas('cart_items', ['id' => $item->id, 'quantity' => 4]);
    }

    public function test_a_user_cannot_modify_another_users_cart_item(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $cart = Cart::create(['user_id' => $owner->id]);
        $product = Product::factory()->create(['stock_quantity' => 10, 'status' => Product::STATUS_ACTIVE]);

        $item = \App\Models\CartItem::create([
            'cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1, 'price_snapshot' => $product->price,
        ]);

        $response = $this->actingAs($intruder)->patch("/cart/{$item->id}", ['quantity' => 4]);

        $response->assertForbidden();
    }
}

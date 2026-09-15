<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_totals_sum_line_totals_correctly(): void
    {
        $user = User::factory()->create();
        $service = app(CartService::class);
        $cart = $service->getOrCreateCart($user);

        $productA = Product::factory()->create(['price' => 1000, 'discount_price' => null, 'stock_quantity' => 10, 'status' => Product::STATUS_ACTIVE]);
        $productB = Product::factory()->create(['price' => 2500, 'discount_price' => null, 'stock_quantity' => 10, 'status' => Product::STATUS_ACTIVE]);

        $service->addItem($user, $productA, 2);
        $service->addItem($user, $productB, 1);

        $totals = $service->totals($cart);

        $this->assertEquals(4500, $totals['subtotal']);
        $this->assertEquals(3, $totals['item_count']);
    }
}

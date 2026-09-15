<?php

namespace Tests\Feature;

use App\Exceptions\InsufficientStockException;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_reserving_stock_decrements_quantity_and_sales_count(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10, 'sales_count' => 0, 'status' => Product::STATUS_ACTIVE]);

        app(InventoryService::class)->reserve($product->id, 3);

        $product->refresh();
        $this->assertEquals(7, $product->stock_quantity);
        $this->assertEquals(3, $product->sales_count);
    }

    public function test_reserving_more_than_available_throws(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 2, 'status' => Product::STATUS_ACTIVE]);

        $this->expectException(InsufficientStockException::class);

        app(InventoryService::class)->reserve($product->id, 5);
    }

    public function test_product_is_marked_out_of_stock_when_it_hits_zero(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 2, 'status' => Product::STATUS_ACTIVE]);

        app(InventoryService::class)->reserve($product->id, 2);

        $product->refresh();
        $this->assertEquals(Product::STATUS_OUT_OF_STOCK, $product->status);
    }

    public function test_releasing_stock_restores_it_and_reactivates_the_product(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 0, 'status' => Product::STATUS_OUT_OF_STOCK]);

        app(InventoryService::class)->release($product->id, 5);

        $product->refresh();
        $this->assertEquals(5, $product->stock_quantity);
        $this->assertEquals(Product::STATUS_ACTIVE, $product->status);
    }
}

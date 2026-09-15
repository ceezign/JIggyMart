<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_matches_product_name(): void
    {
        Product::factory()->create(['name' => 'Wireless Bluetooth Headphones', 'status' => Product::STATUS_ACTIVE]);
        Product::factory()->create(['name' => 'Kitchen Blender', 'status' => Product::STATUS_ACTIVE]);

        $response = $this->get('/products?search=headphones');

        $response->assertSee('Wireless Bluetooth Headphones');
        $response->assertDontSee('Kitchen Blender');
    }

    public function test_filter_by_category_only_returns_matching_products(): void
    {
        $electronics = Category::factory()->create(['name' => 'Electronics']);
        $fashion = Category::factory()->create(['name' => 'Fashion']);

        Product::factory()->create(['name' => 'Smartphone X', 'category_id' => $electronics->id, 'status' => Product::STATUS_ACTIVE]);
        Product::factory()->create(['name' => 'Denim Jacket', 'category_id' => $fashion->id, 'status' => Product::STATUS_ACTIVE]);

        $response = $this->get("/products?category={$electronics->id}");

        $response->assertSee('Smartphone X');
        $response->assertDontSee('Denim Jacket');
    }

    public function test_filter_by_price_range(): void
    {
        Product::factory()->create(['name' => 'Cheap Item', 'price' => 1000, 'status' => Product::STATUS_ACTIVE]);
        Product::factory()->create(['name' => 'Expensive Item', 'price' => 900000, 'status' => Product::STATUS_ACTIVE]);

        $response = $this->get('/products?min_price=500&max_price=5000');

        $response->assertSee('Cheap Item');
        $response->assertDontSee('Expensive Item');
    }
}

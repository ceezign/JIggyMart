<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function makeSeller(): User
    {
        Role::firstOrCreate(['name' => Role::SELLER], ['label' => 'Seller']);
        $seller = User::factory()->seller()->create();
        $seller->roles()->attach(Role::where('name', Role::SELLER)->first());

        return $seller;
    }

    public function test_a_seller_can_create_a_product(): void
    {
        $seller = $this->makeSeller();
        $category = Category::factory()->create();

        $response = $this->actingAs($seller)->post('/seller/products', [
            'name' => 'Test Widget',
            'description' => 'A great widget.',
            'sku' => 'WIDGET-001',
            'category_id' => $category->id,
            'price' => 1000,
            'stock_quantity' => 10,
            'condition' => 'new',
        ]);

        $this->assertDatabaseHas('products', ['sku' => 'WIDGET-001', 'seller_id' => $seller->id]);
    }

    public function test_a_seller_cannot_update_another_sellers_product(): void
    {
        $sellerA = $this->makeSeller();
        $sellerB = $this->makeSeller();

        $product = Product::factory()->create(['seller_id' => $sellerA->id]);

        $response = $this->actingAs($sellerB)->put("/seller/products/{$product->id}", [
            'name' => 'Hijacked Name',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('products', ['id' => $product->id, 'name' => 'Hijacked Name']);
    }

    public function test_a_non_seller_cannot_create_a_product(): void
    {
        $customer = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($customer)->post('/seller/products', [
            'name' => 'Test Widget',
            'description' => 'A great widget.',
            'sku' => 'WIDGET-002',
            'category_id' => $category->id,
            'price' => 1000,
            'stock_quantity' => 10,
            'condition' => 'new',
        ]);

        $response->assertForbidden();
    }
}

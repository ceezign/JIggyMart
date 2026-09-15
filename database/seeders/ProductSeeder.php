<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::whereHas('roles', fn ($q) => $q->where('name', 'seller'))
            ->where('seller_status', 'approved')->get();
        $categories = Category::whereNotNull('parent_id')->get();

        if ($sellers->isEmpty() || $categories->isEmpty()) {
            return;
        }

        Product::factory()
            ->count(60)
            ->recycle($sellers)
            ->recycle($categories)
            ->create()
            ->each(function (Product $product) {
                // Seed placeholder image rows so listing/detail pages have
                // something to render; swap /images/placeholder.png for real
                // uploads via the seller dashboard.
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => 'seed/placeholder.png',
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            });

        // A few guaranteed out-of-stock products so filtering/status logic is exercised.
        Product::factory()->count(5)->outOfStock()->recycle($sellers)->recycle($categories)->create();
    }
}

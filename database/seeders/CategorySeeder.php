<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            'Electronics' => ['Phones', 'Computers', 'Accessories'],
            'Fashion' => ['Menswear', 'Womenswear', 'Shoes'],
            'Home & Kitchen' => ['Furniture', 'Appliances'],
            'Beauty' => ['Skincare', 'Makeup'],
            'Sports' => ['Fitness Equipment', 'Outdoor'],
            'Books' => [],
        ];

        foreach ($tree as $parentName => $children) {
            $parent = Category::firstOrCreate(
                ['slug' => Str::slug($parentName)],
                ['name' => $parentName, 'is_active' => true]
            );

            foreach ($children as $childName) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($parentName.'-'.$childName)],
                    ['name' => $childName, 'parent_id' => $parent->id, 'is_active' => true]
                );
            }
        }
    }
}

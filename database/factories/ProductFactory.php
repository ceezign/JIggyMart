<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = ucfirst(fake()->words(3, true));
        $price = fake()->randomFloat(2, 2000, 500000);

        return [
            'seller_id' => User::factory()->seller(),
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(6),
            'description' => fake()->paragraphs(3, true),
            'brand' => fake()->company(),
            'sku' => strtoupper(Str::random(10)),
            'price' => $price,
            'discount_price' => fake()->boolean(30) ? round($price * 0.85, 2) : null,
            'stock_quantity' => fake()->numberBetween(0, 200),
            'condition' => fake()->randomElement(['new', 'used', 'refurbished']),
            'status' => Product::STATUS_ACTIVE,
            'average_rating' => fake()->randomFloat(2, 3, 5),
            'reviews_count' => fake()->numberBetween(0, 40),
            'sales_count' => fake()->numberBetween(0, 300),
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock_quantity' => 0, 'status' => Product::STATUS_OUT_OF_STOCK]);
    }
}

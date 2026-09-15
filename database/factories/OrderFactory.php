<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 5000, 300000);
        $shipping = 2000;

        return [
            'order_number' => 'ORD-'.strtoupper(Str::random(10)),
            'user_id' => User::factory(),
            'subtotal' => $subtotal,
            'discount_total' => 0,
            'shipping_total' => $shipping,
            'tax_total' => 0,
            'grand_total' => $subtotal + $shipping,
            'currency' => 'NGN',
            'status' => fake()->randomElement(['pending', 'paid', 'shipped', 'delivered', 'cancelled']),
        ];
    }
}

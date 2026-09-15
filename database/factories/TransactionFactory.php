<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'reference' => 'TXN-'.strtoupper(Str::random(12)),
            'amount' => fake()->randomFloat(2, 5000, 300000),
            'currency' => 'NGN',
            'payment_method' => 'mock',
            'status' => 'successful',
            'gateway_reference' => 'MOCK-'.strtoupper(Str::random(10)),
        ];
    }
}

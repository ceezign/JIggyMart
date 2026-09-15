<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_customer_can_cancel_a_pending_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => Order::STATUS_PENDING]);

        $response = $this->actingAs($user)->post("/orders/{$order->id}/cancel");

        $order->refresh();
        $this->assertEquals(Order::STATUS_CANCELLED, $order->status);
    }

    public function test_a_customer_cannot_cancel_a_delivered_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => Order::STATUS_DELIVERED]);

        $response = $this->actingAs($user)->post("/orders/{$order->id}/cancel");

        $response->assertForbidden();
    }

    public function test_a_customer_cannot_view_another_customers_order(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($intruder)->get("/orders/{$order->id}");

        $response->assertForbidden();
    }
}

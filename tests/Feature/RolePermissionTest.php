<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_customer_cannot_access_the_admin_dashboard(): void
    {
        $customer = User::factory()->create();
        Role::create(['name' => Role::CUSTOMER, 'label' => 'Customer']);
        $customer->roles()->attach(Role::where('name', Role::CUSTOMER)->first());

        $response = $this->actingAs($customer)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_a_customer_cannot_access_the_seller_dashboard(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get('/seller/dashboard');

        $response->assertForbidden();
    }

    public function test_an_admin_can_access_the_admin_dashboard(): void
    {
        $admin = User::factory()->create();
        Role::create(['name' => Role::ADMIN, 'label' => 'Administrator']);
        $admin->roles()->attach(Role::where('name', Role::ADMIN)->first());

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_a_pending_seller_cannot_access_seller_dashboard_until_approved(): void
    {
        $user = User::factory()->create(['seller_status' => 'pending']);
        Role::create(['name' => Role::SELLER, 'label' => 'Seller']);
        $user->roles()->attach(Role::where('name', Role::SELLER)->first());

        $response = $this->actingAs($user)->get('/seller/dashboard');

        $response->assertForbidden();

        $user->update(['seller_status' => 'approved']);

        $response = $this->actingAs($user)->get('/seller/dashboard');
        $response->assertOk();
    }
}

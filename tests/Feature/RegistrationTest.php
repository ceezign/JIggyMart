<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_customer_can_register(): void
    {
        Role::create(['name' => Role::CUSTOMER, 'label' => 'Customer']);

        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
        $this->assertDatabaseHas('carts', []);

        $user = \App\Models\User::where('email', 'jane@example.com')->first();
        $this->assertTrue($user->hasRole('customer'));
    }

    public function test_registration_requires_matching_password_confirmation(): void
    {
        Role::create(['name' => Role::CUSTOMER, 'label' => 'Customer']);

        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }
}

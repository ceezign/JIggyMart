<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class JwtAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_via_the_api_and_receive_a_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'API User',
            'email' => 'api@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'user']);
    }

    public function test_a_user_can_login_via_the_api_and_receive_a_token(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);

        $response = $this->postJson('/api/auth/login', ['email' => $user->email, 'password' => 'secret123']);

        $response->assertStatus(200)->assertJsonStructure(['access_token']);
    }

    public function test_login_fails_with_bad_credentials(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);

        $response = $this->postJson('/api/auth/login', ['email' => $user->email, 'password' => 'wrong']);

        $response->assertStatus(401);
    }

    public function test_protected_endpoints_require_a_valid_token(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }

    public function test_a_valid_token_can_access_protected_endpoints(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);

        $login = $this->postJson('/api/auth/login', ['email' => $user->email, 'password' => 'secret123']);
        $token = $login->json('access_token');

        $response = $this->getJson('/api/auth/me', ['Authorization' => "Bearer {$token}"]);

        $response->assertStatus(200)->assertJson(['email' => $user->email]);
    }
}

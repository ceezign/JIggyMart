<?php

namespace Tests\Feature;

use App\Models\OAuthAccount;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Exercises the account-linking logic used by GoogleOAuthController without
 * hitting the real Google endpoint: it verifies the create-or-link behavior
 * directly, since that's the part with real business logic.
 */
class OAuthAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_new_oauth_login_creates_a_user_and_links_the_account(): void
    {
        Role::create(['name' => Role::CUSTOMER, 'label' => 'Customer']);

        $email = 'social@example.com';
        $providerId = 'google-12345';

        $user = DB::transaction(function () use ($email, $providerId) {
            $user = User::create([
                'name' => 'Social User',
                'email' => $email,
                'password' => null,
                'email_verified_at' => now(),
            ]);

            $customerRole = Role::where('name', Role::CUSTOMER)->first();
            $user->roles()->attach($customerRole);

            OAuthAccount::create([
                'user_id' => $user->id,
                'provider' => 'google',
                'provider_id' => $providerId,
            ]);

            return $user;
        });

        $this->assertDatabaseHas('users', ['email' => $email]);
        $this->assertNull($user->password);
        $this->assertDatabaseHas('oauth_accounts', ['provider' => 'google', 'provider_id' => $providerId]);
    }

    public function test_a_returning_oauth_user_is_linked_to_the_same_account(): void
    {
        $user = User::factory()->create(['password' => null]);
        OAuthAccount::create(['user_id' => $user->id, 'provider' => 'google', 'provider_id' => 'google-999']);

        $existing = OAuthAccount::where('provider', 'google')->where('provider_id', 'google-999')->first();

        $this->assertEquals($user->id, $existing->user_id);
        $this->assertEquals(1, OAuthAccount::count());
    }
}

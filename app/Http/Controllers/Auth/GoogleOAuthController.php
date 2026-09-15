<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\OAuthAccount;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 * Google OAuth via Laravel Socialite.
 *
 * Flow: look up an existing oauth_accounts row for (provider, provider_id).
 * If found, log that user in. Otherwise, look up (or create) a user by
 * email and link a new oauth_accounts row to it. OAuth accounts never
 * receive a local password.
 */
class GoogleOAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = DB::transaction(function () use ($googleUser) {
            $oauthAccount = OAuthAccount::where('provider', 'google')
                ->where('provider_id', $googleUser->getId())
                ->first();

            if ($oauthAccount) {
                $oauthAccount->update([
                    'access_token' => $googleUser->token,
                    'refresh_token' => $googleUser->refreshToken,
                    'avatar' => $googleUser->getAvatar(),
                ]);

                return $oauthAccount->user;
            }

            $user = User::where('email', $googleUser->getEmail())->first();

            if (! $user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                    'email' => $googleUser->getEmail(),
                    'password' => null, // OAuth-only account: no local password is ever stored.
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(), // Google has already verified this address.
                ]);

                $customerRole = Role::firstOrCreate(['name' => Role::CUSTOMER], ['label' => 'Customer']);
                $user->roles()->attach($customerRole);

                Cart::create(['user_id' => $user->id]);
            }

            OAuthAccount::create([
                'user_id' => $user->id,
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'access_token' => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
            ]);

            return $user;
        });

        Auth::login($user, true);

        return redirect()->route('dashboard.customer')->with('status', 'Signed in with Google.');
    }
}

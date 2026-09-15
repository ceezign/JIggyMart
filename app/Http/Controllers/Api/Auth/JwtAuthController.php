<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Stateless JWT authentication for API clients (mobile apps, third-party
 * integrations). This is intentionally separate from the web session guard
 * used by Blade pages — API clients never receive or need a session cookie.
 */
class JwtAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $customerRole = Role::firstOrCreate(['name' => Role::CUSTOMER], ['label' => 'Customer']);
        $user->roles()->attach($customerRole);
        Cart::create(['user_id' => $user->id]);

        $token = JWTAuth::fromUser($user);

        return $this->tokenResponse($token, $user);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return $this->tokenResponse($token, auth('api')->user());
    }

    public function me(Request $request)
    {
        return new UserResource($request->user()->load('roles'));
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not refresh token'], 401);
        }

        return $this->tokenResponse($newToken, auth('api')->user());
    }

    public function logout()
    {
        JWTAuth::parseToken()->invalidate();

        return response()->json(['message' => 'Logged out successfully']);
    }

    private function tokenResponse(string $token, User $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => new UserResource($user->load('roles')),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterCustomerRequest;
use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterCustomerRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        $customerRole = Role::firstOrCreate(['name' => Role::CUSTOMER], ['label' => 'Customer']);
        $user->roles()->attach($customerRole);

        Cart::create(['user_id' => $user->id]);

        event(new Registered($user));

        $user->notify(new WelcomeNotification());

        Auth::login($user);

        return redirect()->route('dashboard.customer')->with('status', 'Welcome to JiggyMart!');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', Role::ADMIN)->first();
        $sellerRole = Role::where('name', Role::SELLER)->first();
        $customerRole = Role::where('name', Role::CUSTOMER)->first();

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@jiggymart.test'],
            [
                'name' => 'JiggyMart Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        Cart::firstOrCreate(['user_id' => $admin->id]);

        // Sellers
        User::factory()->count(5)->seller()->create()->each(function (User $seller) use ($sellerRole) {
            $seller->roles()->syncWithoutDetaching([$sellerRole->id]);
            Cart::firstOrCreate(['user_id' => $seller->id]);
        });

        // A known demo seller for easy login
        $demoSeller = User::firstOrCreate(
            ['email' => 'seller@jiggymart.test'],
            [
                'name' => 'Demo Seller',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'store_name' => 'Demo Electronics Store',
                'store_description' => 'Quality gadgets at fair prices.',
                'seller_status' => 'approved',
            ]
        );
        $demoSeller->roles()->syncWithoutDetaching([$sellerRole->id]);
        Cart::firstOrCreate(['user_id' => $demoSeller->id]);

        // Customers
        User::factory()->count(15)->create()->each(function (User $customer) use ($customerRole) {
            $customer->roles()->syncWithoutDetaching([$customerRole->id]);
            Cart::firstOrCreate(['user_id' => $customer->id]);
        });

        $demoCustomer = User::firstOrCreate(
            ['email' => 'customer@jiggymart.test'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $demoCustomer->roles()->syncWithoutDetaching([$customerRole->id]);
        Cart::firstOrCreate(['user_id' => $demoCustomer->id]);
        \App\Models\Address::firstOrCreate(
            ['user_id' => $demoCustomer->id, 'label' => 'Home'],
            [
                'full_name' => 'Demo Customer',
                'phone' => '08012345678',
                'line1' => '12 Admiralty Way',
                'city' => 'Lekki',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'is_default' => true,
            ]
        );
    }
}

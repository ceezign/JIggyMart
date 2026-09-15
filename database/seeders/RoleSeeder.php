<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => Role::CUSTOMER], ['label' => 'Customer']);
        Role::firstOrCreate(['name' => Role::SELLER], ['label' => 'Seller']);
        Role::firstOrCreate(['name' => Role::ADMIN], ['label' => 'Administrator']);
    }
}

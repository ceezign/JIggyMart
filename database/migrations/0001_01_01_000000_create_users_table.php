<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(); // nullable: OAuth-only accounts have no password
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'suspended', 'pending'])->default('active');
            // Seller-specific fields (kept on users for simplicity; a user can hold the seller role)
            $table->string('store_name')->nullable();
            $table->text('store_description')->nullable();
            $table->enum('seller_status', ['none', 'pending', 'approved', 'rejected'])->default('none')->index();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

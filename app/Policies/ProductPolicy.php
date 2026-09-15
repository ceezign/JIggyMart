<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Product $product): bool
    {
        if ($product->status === Product::STATUS_ACTIVE || $product->status === Product::STATUS_OUT_OF_STOCK) {
            return true;
        }

        return $user && ($user->id === $product->seller_id || $user->isAdmin());
    }

    public function create(User $user): bool
    {
        return $user->isSeller();
    }

    public function update(User $user, Product $product): bool
    {
        return $user->isAdmin() || $user->id === $product->seller_id;
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->isAdmin() || $user->id === $product->seller_id;
    }

    public function manageInventory(User $user, Product $product): bool
    {
        return $user->isAdmin() || $user->id === $product->seller_id;
    }
}

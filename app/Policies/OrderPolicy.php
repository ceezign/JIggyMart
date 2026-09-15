<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        if ($user->isAdmin() || $user->id === $order->user_id) {
            return true;
        }

        // Sellers may view an order only if it contains one of their products.
        return $order->items()->where('seller_id', $user->id)->exists();
    }

    public function cancel(User $user, Order $order): bool
    {
        return ($user->id === $order->user_id || $user->isAdmin())
            && in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PROCESSING], true);
    }

    public function updateStatus(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $order->items()->where('seller_id', $user->id)->exists();
    }
}

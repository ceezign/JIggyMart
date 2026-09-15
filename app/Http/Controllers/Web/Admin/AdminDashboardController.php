<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_sellers' => User::whereHas('roles', fn ($q) => $q->where('name', 'seller'))->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_transactions' => Transaction::count(),
            'total_revenue' => Transaction::where('status', Transaction::STATUS_SUCCESSFUL)->sum('amount'),
            'pending_orders' => Order::whereIn('status', [Order::STATUS_PENDING, Order::STATUS_PROCESSING])->count(),
            'failed_transactions' => Transaction::where('status', Transaction::STATUS_FAILED)->count(),
        ];

        $recentUsers = User::latest()->limit(5)->get();
        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        return view('dashboard.admin.index', compact('stats', 'recentUsers', 'recentOrders'));
    }
}

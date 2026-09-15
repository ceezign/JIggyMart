<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = $user->orders();

        $stats = [
            'total_orders' => (clone $orders)->count(),
            'pending_orders' => (clone $orders)->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_PROCESSING])->count(),
            'completed_orders' => (clone $orders)->where('status', Order::STATUS_DELIVERED)->count(),
            'total_spending' => (clone $orders)->where('status', '!=', Order::STATUS_CANCELLED)->sum('grand_total'),
        ];

        $recentOrders = $user->orders()->latest()->limit(5)->get();
        $recentTransactions = $user->transactions()->latest()->limit(5)->get();

        return view('dashboard.customer.index', compact('stats', 'recentOrders', 'recentTransactions'));
    }

    public function profile(Request $request)
    {
        return view('dashboard.customer.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $request->user()->update($data);

        return back()->with('status', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $request->user()->update(['password' => bcrypt($request->password)]);

        return back()->with('status', 'Password updated.');
    }

    public function transactions(Request $request)
    {
        $transactions = $request->user()->transactions()->with('order')->latest()->paginate(15);

        return view('dashboard.customer.transactions', compact('transactions'));
    }
}

<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"))
            ->latest()->paginate(20);

        return view('dashboard.admin.users', compact('users'));
    }

    public function suspend(User $user)
    {
        $user->update(['status' => 'suspended']);

        return back()->with('status', 'User suspended.');
    }

    public function reinstate(User $user)
    {
        $user->update(['status' => 'active']);

        return back()->with('status', 'User reinstated.');
    }

    public function sellers(Request $request)
    {
        $sellers = User::whereHas('roles', fn ($q) => $q->where('name', 'seller'))
            ->when($request->status, fn ($q, $s) => $q->where('seller_status', $s))
            ->latest()->paginate(20);

        return view('dashboard.admin.sellers', compact('sellers'));
    }

    public function approveSeller(User $user)
    {
        $user->update(['seller_status' => 'approved']);

        return back()->with('status', 'Seller approved.');
    }

    public function rejectSeller(User $user)
    {
        $user->update(['seller_status' => 'rejected']);

        return back()->with('status', 'Seller application rejected.');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterSellerRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SellerRegistrationController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Already an approved seller, or has a pending application: nothing to do here.
        if ($user->hasRole('seller') && $user->seller_status !== 'rejected') {
            return redirect()->route('dashboard.customer');
        }

        return view('auth.register-seller');
    }

    public function store(RegisterSellerRequest $request): RedirectResponse
    {
        $user = $request->user();

        $sellerRole = Role::firstOrCreate(['name' => Role::SELLER], ['label' => 'Seller']);

        if (! $user->hasRole('seller')) {
            $user->roles()->attach($sellerRole);
        }

        $user->update([
            'store_name' => $request->store_name,
            'store_description' => $request->store_description,
            // Sellers require manual (or automated) approval before they can list products.
            'seller_status' => 'pending',
        ]);

        // Redirect to the customer dashboard, NOT the seller dashboard: the
        // seller area is gated behind an "approved" status, so sending a
        // freshly-applied (still "pending") user straight there would 403
        // them immediately after they submit.
        return redirect()->route('dashboard.customer')
            ->with('status', 'Your seller application has been submitted! Please wait for admin confirmation — we\'ll notify you once it\'s reviewed.');
    }
}

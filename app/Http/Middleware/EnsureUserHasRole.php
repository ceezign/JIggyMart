<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Usage: ->middleware('role:admin') or ->middleware('role:admin,seller')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        $sellerStatus = null;

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                if ($role === 'seller' && $user->seller_status !== 'approved') {
                    $sellerStatus = $user->seller_status;

                    continue;
                }

                return $next($request);
            }
        }

        if ($sellerStatus === 'pending') {
            abort(403, 'Your seller application is still awaiting admin approval. You\'ll get access to the Seller Hub as soon as it\'s confirmed.');
        }

        if ($sellerStatus === 'rejected') {
            abort(403, 'Your seller application was not approved. Please contact support or re-apply from the "Become a Seller" page.');
        }

        abort(403, 'You do not have permission to access this resource.');
    }
}

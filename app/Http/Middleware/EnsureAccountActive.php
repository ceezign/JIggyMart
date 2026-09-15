<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status === 'suspended') {
            auth()->guard('web')->logout();

            abort(403, 'Your account has been suspended. Contact support for assistance.');
        }

        return $next($request);
    }
}

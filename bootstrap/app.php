<?php

use App\Http\Middleware\EnsureAccountActive;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'account.active' => EnsureAccountActive::class,
        ]);

        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Render (and most PaaS hosts) terminate SSL at their edge and forward
        // plain HTTP to the container, signalling the original scheme via the
        // X-Forwarded-Proto header. Without trusting that header, Laravel
        // thinks every request is HTTP and generates http:// URLs for
        // asset(), url(), form actions, etc. — which the browser then blocks
        // as mixed content on an https:// page. '*' trusts the immediate
        // proxy only (Render's edge), not arbitrary third parties.
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

<?php

namespace App\Providers;

use App\Listeners\SendOrderNotifications;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Policies\AddressPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Belt-and-suspenders alongside trustProxies() in bootstrap/app.php:
        // force every generated URL to https:// in production so asset(),
        // url(), and form actions never emit an http:// link that a browser
        // would block as mixed content, even if a proxy header is ever lost.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(Address::class, AddressPolicy::class);

        // Admins bypass all ability checks.
        Gate::before(fn ($user, $ability) => $user->isAdmin() ? true : null);

        Event::subscribe(SendOrderNotifications::class);
    }
}

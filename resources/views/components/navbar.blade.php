<nav class="navbar navbar-expand-lg navbar-dark navbar-jm sticky-top py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Jiggy<span class="brand-dot">Mart</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <form class="d-flex mx-auto my-2 my-lg-0" style="max-width: 480px; width: 100%;" action="{{ route('products.index') }}" method="GET">
                <input class="form-control me-0" type="search" name="search" placeholder="Search products, brands, categories…" value="{{ request('search') }}">
                <button class="btn btn-accent btn-search" type="submit">Search</button>
            </form>
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                @auth
                    @php($u = auth()->user())
                    <li class="nav-item"><a class="nav-link" href="{{ route('wishlist.index') }}">♥ Wishlist</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">🛍 Cart</a></li>

                    @if ($u->isAdmin())
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.admin') }}">Admin</a></li>
                    @endif

                    {{-- Seller area: a user can be BOTH a customer and a seller at once.
                         These links are additive to the customer links above, never a
                         replacement for them. --}}
                    @if ($u->hasRole('seller') && $u->seller_status === 'approved')
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.seller') }}">Seller Hub</a></li>
                    @elseif ($u->hasRole('seller') && $u->seller_status === 'pending')
                        <li class="nav-item"><span class="badge badge-pending">Seller: Pending Approval</span></li>
                    @elseif ($u->hasRole('seller') && $u->seller_status === 'rejected')
                        <li class="nav-item"><a class="nav-link" href="{{ route('seller.register') }}">Seller application rejected — Re-apply</a></li>
                    @elseif (! $u->isAdmin())
                        {{-- Only shown to accounts that have never applied to sell. --}}
                        <li class="nav-item"><a class="nav-link" href="{{ route('seller.register') }}">Sell on JiggyMart</a></li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">{{ $u->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('dashboard.customer') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">My Orders</a></li>
                            <li><a class="dropdown-item" href="{{ route('addresses.index') }}">Addresses</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-accent btn-sm ms-lg-2" href="{{ route('register') }}">Sign Up</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

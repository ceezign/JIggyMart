@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <div class="hero-jm p-5 mb-5 text-center">
        <h1 class="display-5 fw-bold">Buy and sell anything on JiggyMart</h1>
        <p class="lead">A trusted multi-vendor marketplace connecting shoppers with independent sellers.</p>
        <a href="{{ route('products.index') }}" class="btn btn-accent btn-lg">Start Shopping</a>
        @if (! auth()->check() || (! auth()->user()->isAdmin() && ! auth()->user()->hasRole('seller')))
            <a href="{{ auth()->check() ? route('seller.register') : route('register') }}" class="btn btn-outline-light btn-lg">Sell on JiggyMart</a>
        @endif
    </div>

    <h4 class="mb-3">Shop by category</h4>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 mb-5">
        @foreach ($categories as $category)
            <div class="col">
                <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="fs-3 mb-1">🏷️</div>
                            <div class="text-dark fw-semibold">{{ $category->name }}</div>
                            <small class="text-muted">{{ $category->products_count }} items</small>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <h4 class="mb-3">Popular products</h4>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        @foreach ($featured as $product)
            <x-product-card :product="$product" :wishlist-ids="$wishlistIds" />
        @endforeach
    </div>

    <h4 class="mb-3">Newest arrivals</h4>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($newest as $product)
            <x-product-card :product="$product" :wishlist-ids="$wishlistIds" />
        @endforeach
    </div>
@endsection

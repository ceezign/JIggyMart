@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div class="row g-4">
    <div class="col-md-5">
        <img src="{{ $product->images->first()?->url ?? 'https://placehold.co/500x400?text=No+Image' }}"
             class="img-fluid rounded shadow-sm" alt="{{ $product->name }}"
             onerror="this.src='https://placehold.co/500x400?text=No+Image'">
        <div class="d-flex gap-2 mt-2">
            @foreach ($product->images->skip(1) as $img)
                <img src="{{ $img->url }}" style="width:60px;height:60px;object-fit:cover;" class="rounded border">
            @endforeach
        </div>
    </div>
    <div class="col-md-7">
        <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $product->category->name }}</span>
        <h2 class="mt-2">{{ $product->name }}</h2>
        <p class="text-muted">Sold by
            <strong>{{ $product->seller->store_name ?? $product->seller->name }}</strong> · Condition: {{ ucfirst($product->condition) }}</p>
        <div class="text-warning mb-2">
            {{ str_repeat('★', round($product->average_rating)) }}{{ str_repeat('☆', 5 - round($product->average_rating)) }}
            <span class="text-muted">({{ $product->reviews_count }} reviews)</span>
        </div>

        <div class="mb-3">
            @if ($product->discount_price)
                <span class="fs-3 text-danger fw-bold">₦{{ number_format($product->discount_price, 2) }}</span>
                <span class="fs-6 text-muted text-decoration-line-through ms-2">₦{{ number_format($product->price, 2) }}</span>
            @else
                <span class="fs-3 fw-bold">₦{{ number_format($product->price, 2) }}</span>
            @endif
        </div>

        <p>{{ $product->description }}</p>

        <p class="small text-muted">
            @if ($product->is_in_stock)
                <span class="text-success">In stock</span> — {{ $product->stock_quantity }} available
            @else
                <span class="text-danger">Out of stock</span>
            @endif
        </p>

        @auth
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('cart.store') }}" class="d-flex gap-2 align-items-center">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @if ($product->variants->isNotEmpty())
                        <select name="variant_id" class="form-select form-select-sm">
                            @foreach ($product->variants as $variant)
                                <option value="{{ $variant->id }}">{{ $variant->name }}: {{ $variant->value }}</option>
                            @endforeach
                        </select>
                    @endif
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="form-control form-control-sm" style="width:80px;">
                    <button class="btn btn-primary" type="submit" @disabled(!$product->is_in_stock)>Add to Cart</button>
                </form>
                <form method="POST" action="{{ route('wishlist.store', $product) }}">
                    @csrf
                    <button class="btn btn-outline-secondary" type="submit">{{ $isWishlisted ? '♥ In Wishlist' : '♡ Add to Wishlist' }}</button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">Login to purchase</a>
        @endauth
    </div>
</div>

<hr class="my-5">

<h4>Reviews</h4>
@forelse ($product->reviews as $review)
    <div class="border-bottom py-2">
        <strong>{{ $review->user->name }}</strong>
        <span class="text-warning">{{ str_repeat('★', $review->rating) }}</span>
        @if ($review->is_verified_purchase)<span class="badge bg-success-subtle text-success-emphasis">Verified Purchase</span>@endif
        <p class="mb-0">{{ $review->comment }}</p>
    </div>
@empty
    <p class="text-muted">No reviews yet.</p>
@endforelse

@if ($related->isNotEmpty())
    <h4 class="mt-5">Related products</h4>
    <div class="row row-cols-2 row-cols-md-4 g-4">
        @foreach ($related as $r)
            <x-product-card :product="$r" :wishlist-ids="$wishlistIds" />
        @endforeach
    </div>
@endif
@endsection

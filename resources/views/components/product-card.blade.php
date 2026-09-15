@props(['product', 'wishlistIds' => []])
<div class="col">
    <div class="card product-card h-100">
        @auth
            @php($isWishlisted = in_array($product->id, $wishlistIds))
            <form method="POST" action="{{ route('wishlist.store', $product) }}" onclick="event.stopPropagation()">
                @csrf
                <button type="submit" class="wishlist-heart {{ $isWishlisted ? 'is-active' : '' }}"
                        title="{{ $isWishlisted ? 'In your wishlist' : 'Add to wishlist' }}">
                    {{ $isWishlisted ? '♥' : '♡' }}
                </button>
            </form>
        @endauth

        <a href="{{ route('products.show', $product->slug) }}" class="product-link">
            <span class="product-media">
                <img src="{{ $product->images->first()?->url ?? 'https://placehold.co/400x300?text=No+Image' }}"
                     class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $product->name }}"
                     onerror="this.src='https://placehold.co/400x300?text=No+Image'">
            </span>
            <div class="card-body d-flex flex-column">
                <span class="category-pill mb-2 align-self-start">{{ $product->category->name ?? '' }}</span>
                <h6 class="card-title text-dark">{{ Str::limit($product->name, 45) }}</h6>
                <div class="mb-1">
                    @if ($product->discount_price)
                        <span class="price-discount">₦{{ number_format($product->discount_price, 2) }}</span>
                        <span class="price-strike small ms-1">₦{{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="price-tag">₦{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
                <div class="small rating-stars mb-2">
                    {{ str_repeat('★', round($product->average_rating)) }}{{ str_repeat('☆', 5 - round($product->average_rating)) }}
                    <span class="text-muted">({{ $product->reviews_count }})</span>
                </div>
            </div>
        </a>

        <div class="card-body pt-0 mt-auto">
            @if (!$product->is_in_stock)
                <span class="badge bg-danger">Out of stock</span>
            @else
                <form method="POST" action="{{ route('cart.store') }}" onclick="event.stopPropagation()">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button class="btn btn-sm btn-primary w-100" type="submit">Add to Cart</button>
                </form>
            @endif
        </div>
    </div>
</div>

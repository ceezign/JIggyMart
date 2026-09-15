@extends('layouts.app')
@section('title', 'Products')
@section('content')
<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card p-3">
            <h6 class="mb-3">Filters</h6>
            <form method="GET">
                @if (request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                <div class="mb-3">
                    <label class="form-label small">Category</label>
                    <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All categories</option>
                        @foreach ($categories as $cat)
                            <optgroup label="{{ $cat->name }}">
                                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>All {{ $cat->name }}</option>
                                @foreach ($cat->children as $child)
                                    <option value="{{ $child->id }}" @selected(request('category') == $child->id)>{{ $child->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Price range (₦)</label>
                    <div class="d-flex gap-2">
                        <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                        <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Condition</label>
                    <select name="condition" class="form-select form-select-sm">
                        <option value="">Any</option>
                        <option value="new" @selected(request('condition') == 'new')>New</option>
                        <option value="used" @selected(request('condition') == 'used')>Used</option>
                        <option value="refurbished" @selected(request('condition') == 'refurbished')>Refurbished</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Brand</label>
                    <select name="brand" class="form-select form-select-sm">
                        <option value="">Any</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand }}" @selected(request('brand') == $brand)>{{ $brand }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStock" @checked(request('in_stock'))>
                    <label class="form-check-label small" for="inStock">In stock only</label>
                </div>
                <button class="btn btn-primary btn-sm w-100" type="submit">Apply Filters</button>
                <a href="{{ route('products.index') }}" class="btn btn-link btn-sm w-100">Clear</a>
            </form>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="mb-0 text-muted">{{ $products->total() }} results @if(request('search')) for "{{ request('search') }}" @endif</p>
            <form method="GET" class="d-flex align-items-center gap-2">
                @foreach(request()->except('sort') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <label class="small text-muted mb-0">Sort:</label>
                <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                    <option value="newest" @selected(request('sort', 'newest') == 'newest')>Newest</option>
                    <option value="price_asc" @selected(request('sort') == 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected(request('sort') == 'price_desc')>Price: High to Low</option>
                    <option value="rating" @selected(request('sort') == 'rating')>Highest Rated</option>
                    <option value="popular" @selected(request('sort') == 'popular')>Most Popular</option>
                </select>
            </form>
        </div>

        <div class="row row-cols-2 row-cols-md-3 g-4">
            @forelse ($products as $product)
                <x-product-card :product="$product" :wishlist-ids="$wishlistIds" />
            @empty
                <p class="text-muted">No products matched your filters.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', $category->name)
@section('content')
<h2 class="mb-4">{{ $category->name }}</h2>
<div class="row row-cols-2 row-cols-md-4 g-4">
    @forelse ($products as $product)
        <x-product-card :product="$product" :wishlist-ids="$wishlistIds" />
    @empty
        <p class="text-muted">No products in this category yet.</p>
    @endforelse
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection

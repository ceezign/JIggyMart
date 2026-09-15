@extends('layouts.app')
@section('title', 'Wishlist')
@section('content')
<h2 class="mb-4">My Wishlist</h2>
<div class="row row-cols-2 row-cols-md-4 g-4">
    @forelse ($items as $item)
        <div class="col">
            <div class="card h-100">
                <img src="{{ $item->product->images->first()?->url ?? 'https://placehold.co/300' }}" class="card-img-top" style="height:160px;object-fit:cover;">
                <div class="card-body">
                    <h6>{{ $item->product->name }}</h6>
                    <p class="fw-bold">₦{{ number_format($item->product->effective_price, 2) }}</p>
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('wishlist.move', $item) }}">
                            @csrf
                            <button class="btn btn-sm btn-primary">Move to Cart</button>
                        </form>
                        <form method="POST" action="{{ route('wishlist.destroy', $item) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Your wishlist is empty.</p>
    @endforelse
</div>
@endsection

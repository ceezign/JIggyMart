@extends('layouts.app')
@section('title', 'My Products')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>My Products</h2>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary">+ Add Product</a>
</div>
<table class="table align-middle">
    <thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach ($products as $product)
        <tr>
            <td><img src="{{ $product->images->first()?->url ?? 'https://placehold.co/50' }}" style="width:50px;height:50px;object-fit:cover;" class="rounded"></td>
            <td>{{ $product->name }}</td>
            <td>₦{{ number_format($product->price, 2) }}</td>
            <td>{{ $product->stock_quantity }}</td>
            <td><span class="badge bg-secondary-subtle text-secondary-emphasis text-capitalize">{{ str_replace('_',' ',$product->status) }}</span></td>
            <td>
                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                <form method="POST" action="{{ route('seller.products.destroy', $product) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $products->links() }}
@endsection

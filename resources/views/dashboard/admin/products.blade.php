@extends('layouts.app')
@section('title', 'Manage Products')
@section('content')
<h2 class="mb-4">Products</h2>
<table class="table">
    <thead><tr><th>Name</th><th>Seller</th><th>Category</th><th>Price</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach ($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->seller->store_name ?? $product->seller->name }}</td>
            <td>{{ $product->category->name }}</td>
            <td>₦{{ number_format($product->price, 2) }}</td>
            <td>
                <form method="POST" action="{{ route('admin.products.status', $product) }}" class="d-flex gap-1">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach (['draft','pending','active','out_of_stock','suspended','archived'] as $status)
                            <option value="{{ $status }}" @selected($product->status == $status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>
                        @endforeach
                    </select>
                </form>
            </td>
            <td>
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $products->links() }}
@endsection

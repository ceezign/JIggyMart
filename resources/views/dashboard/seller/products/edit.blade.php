@extends('layouts.app')
@section('title', 'Edit Product')
@section('content')
<h2 class="mb-4">Edit Product</h2>
<div class="card p-4" style="max-width: 700px;">
    <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('dashboard.seller.products._form', ['product' => $product])
        <button class="btn btn-primary mt-2">Update Product</button>
    </form>

    @if ($product->images->isNotEmpty())
        <hr>
        <h6>Existing Images</h6>
        <div class="d-flex gap-2 flex-wrap">
            @foreach ($product->images as $img)
                <div class="text-center">
                    <img src="{{ $img->url }}" style="width:80px;height:80px;object-fit:cover;" class="rounded border">
                    <form method="POST" action="{{ route('seller.products.images.destroy', [$product, $img]) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-link text-danger p-0">Remove</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

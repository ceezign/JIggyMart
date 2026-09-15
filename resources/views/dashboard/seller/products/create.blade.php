@extends('layouts.app')
@section('title', 'Add Product')
@section('content')
<h2 class="mb-4">Add Product</h2>
<div class="card p-4" style="max-width: 700px;">
    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data">
        @csrf
        @include('dashboard.seller.products._form', ['product' => null])
        <button class="btn btn-primary mt-2">Create Product</button>
    </form>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Seller Dashboard')
@section('content')
<h2 class="mb-1">Seller Hub</h2>
<p class="text-muted mb-4">{{ auth()->user()->store_name ?? auth()->user()->name }}
    @if (auth()->user()->seller_status !== 'approved')
        <span class="badge bg-warning-subtle text-warning-emphasis text-capitalize">{{ auth()->user()->seller_status }}</span>
    @endif
</p>

@if (auth()->user()->seller_status !== 'approved')
    <div class="alert alert-warning">Your seller account is <strong>{{ auth()->user()->seller_status }}</strong>. You can't publish listings until an administrator approves your application.</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold">{{ $stats['total_products'] }}</div><small class="text-muted">Products</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold">{{ $stats['active_listings'] }}</div><small class="text-muted">Active</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold">{{ $stats['out_of_stock'] }}</div><small class="text-muted">Out of Stock</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold">₦{{ number_format($stats['total_sales'], 0) }}</div><small class="text-muted">Revenue</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold">{{ $stats['pending_orders'] }}</div><small class="text-muted">Pending Orders</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold">{{ $stats['completed_orders'] }}</div><small class="text-muted">Delivered</small></div></div>
</div>

<div class="d-flex gap-2 mb-4">
    <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary btn-sm">My Products</a>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm">+ Add Product</a>
    <a href="{{ route('seller.orders') }}" class="btn btn-outline-secondary btn-sm">Orders</a>
    <a href="{{ route('seller.sales') }}" class="btn btn-outline-secondary btn-sm">Sales Analytics</a>
    <a href="{{ route('seller.profile') }}" class="btn btn-outline-secondary btn-sm">Store Profile</a>
</div>

<div class="card p-3">
    <h5>Recent Orders</h5>
    @forelse ($recentOrders as $order)
        <div class="d-flex justify-content-between border-bottom py-2">
            <span>{{ $order->order_number }}</span>
            <span class="text-capitalize">{{ $order->status }}</span>
        </div>
    @empty
        <p class="text-muted">No orders yet.</p>
    @endforelse
</div>
@endsection

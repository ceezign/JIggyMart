@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<h2 class="mb-4">Admin Dashboard</h2>
<div class="row g-3 mb-4">
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold">{{ $stats['total_users'] }}</div><small class="text-muted">Users</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold">{{ $stats['total_sellers'] }}</div><small class="text-muted">Sellers</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold">{{ $stats['total_products'] }}</div><small class="text-muted">Products</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold">{{ $stats['total_orders'] }}</div><small class="text-muted">Orders</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold">₦{{ number_format($stats['total_revenue'], 0) }}</div><small class="text-muted">Revenue</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold">{{ $stats['failed_transactions'] }}</div><small class="text-muted">Failed Txns</small></div></div>
</div>

<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Users</a>
    <a href="{{ route('admin.sellers.index') }}" class="btn btn-outline-secondary btn-sm">Sellers</a>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">Products</a>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">Categories</a>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">Orders</a>
    <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary btn-sm">Transactions</a>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm">Reviews</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Recent Users</h5>
            @foreach ($recentUsers as $u)
                <div class="d-flex justify-content-between border-bottom py-2"><span>{{ $u->name }}</span><span class="text-muted">{{ $u->email }}</span></div>
            @endforeach
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Recent Orders</h5>
            @foreach ($recentOrders as $order)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a>
                    <span class="text-capitalize">{{ $order->status }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

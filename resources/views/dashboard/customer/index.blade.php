@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h2 class="mb-1">Welcome back, {{ auth()->user()->name }}!</h2>
<p class="text-muted mb-4">Here's what's happening with your account.</p>

@if (auth()->user()->hasRole('seller'))
    @if (auth()->user()->seller_status === 'pending')
        <div class="alert alert-warning">Your seller application for <strong>{{ auth()->user()->store_name }}</strong> is awaiting admin approval. We'll let you know as soon as it's reviewed.</div>
    @elseif (auth()->user()->seller_status === 'rejected')
        <div class="alert alert-danger">Your seller application was not approved. <a href="{{ route('seller.register') }}">Re-apply here</a>.</div>
    @endif
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold">{{ $stats['total_orders'] }}</div><small class="text-muted">Total Orders</small></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold">{{ $stats['pending_orders'] }}</div><small class="text-muted">Pending Orders</small></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold">{{ $stats['completed_orders'] }}</div><small class="text-muted">Completed Orders</small></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold">₦{{ number_format($stats['total_spending'], 2) }}</div><small class="text-muted">Total Spending</small></div></div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Recent Orders</h5>
            @forelse ($recentOrders as $order)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                    <span class="text-capitalize">{{ $order->status }}</span>
                </div>
            @empty
                <p class="text-muted">No orders yet.</p>
            @endforelse
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Recent Transactions</h5>
            @forelse ($recentTransactions as $txn)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span>{{ $txn->reference }}</span>
                    <span>₦{{ number_format($txn->amount, 2) }} · <span class="text-capitalize">{{ $txn->status }}</span></span>
                </div>
            @empty
                <p class="text-muted">No transactions yet.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <a href="{{ route('dashboard.profile') }}" class="btn btn-outline-secondary btn-sm">Profile</a>
    <a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary btn-sm">Addresses</a>
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">Orders</a>
    <a href="{{ route('dashboard.transactions') }}" class="btn btn-outline-secondary btn-sm">Transactions</a>
    <a href="{{ route('wishlist.index') }}" class="btn btn-outline-secondary btn-sm">Wishlist</a>
</div>
@endsection

@extends('layouts.app')
@section('title', 'My Orders')
@section('content')
<h2 class="mb-4">My Orders</h2>
<div class="table-responsive">
<table class="table">
    <thead><tr><th>Order #</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr></thead>
    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('M d, Y') }}</td>
                <td>{{ $order->items->count() }}</td>
                <td>₦{{ number_format($order->grand_total, 2) }}</td>
                <td><span class="badge bg-info-subtle text-info-emphasis text-capitalize">{{ $order->status }}</span></td>
                <td><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
{{ $orders->links() }}
@endsection

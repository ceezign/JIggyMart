@extends('layouts.app')
@section('title', 'Manage Orders')
@section('content')
<h2 class="mb-4">Orders</h2>
<table class="table">
    <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach ($orders as $order)
        <tr>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->user->name }}</td>
            <td>₦{{ number_format($order->grand_total, 2) }}</td>
            <td class="text-capitalize">{{ $order->status }}</td>
            <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $orders->links() }}
@endsection

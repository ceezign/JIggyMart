@extends('layouts.app')
@section('title', 'Seller Orders')
@section('content')
<h2 class="mb-4">Orders Containing My Products</h2>
<table class="table">
    <thead><tr><th>Order #</th><th>Customer</th><th>Items (mine)</th><th>Status</th><th>Date</th></tr></thead>
    <tbody>
    @foreach ($orders as $order)
        <tr>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->user->name }}</td>
            <td>
                @foreach ($order->items->where('seller_id', auth()->id()) as $item)
                    <div>{{ $item->product_name }} × {{ $item->quantity }}</div>
                @endforeach
            </td>
            <td class="text-capitalize">{{ $order->status }}</td>
            <td>{{ $order->created_at->format('M d, Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $orders->links() }}
@endsection

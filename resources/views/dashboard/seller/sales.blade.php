@extends('layouts.app')
@section('title', 'Sales Analytics')
@section('content')
<h2 class="mb-4">Sales</h2>
<table class="table">
    <thead><tr><th>Order #</th><th>Product</th><th>Qty</th><th>Line Total</th><th>Date</th></tr></thead>
    <tbody>
    @foreach ($items as $item)
        <tr>
            <td>{{ $item->order->order_number }}</td>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>₦{{ number_format($item->line_total, 2) }}</td>
            <td>{{ $item->created_at->format('M d, Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $items->links() }}
@endsection

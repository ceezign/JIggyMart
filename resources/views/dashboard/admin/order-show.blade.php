@extends('layouts.app')
@section('title', 'Order '.$order->order_number)
@section('content')
<h2 class="mb-1">Order {{ $order->order_number }}</h2>
<p class="text-muted">Customer: {{ $order->user->name }} ({{ $order->user->email }})</p>

<div class="card p-3 mb-3">
    <h5>Update Status</h5>
    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="d-flex gap-2">
        @csrf @method('PATCH')
        <select name="status" class="form-select" style="max-width: 250px;">
            @foreach (['pending','processing','paid','shipped','delivered','cancelled','refunded'] as $status)
                <option value="{{ $status }}" @selected($order->status == $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary">Update</button>
    </form>
</div>

<table class="table">
    <thead><tr><th>Product</th><th>Seller</th><th>Qty</th><th>Total</th></tr></thead>
    <tbody>
    @foreach ($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->seller->store_name ?? $item->seller->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>₦{{ number_format($item->line_total, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@if ($order->transaction)
<div class="card p-3">
    <h5>Transaction</h5>
    <p class="mb-0">Reference: {{ $order->transaction->reference }} · Status: <span class="text-capitalize">{{ $order->transaction->status }}</span> · Amount: ₦{{ number_format($order->transaction->amount, 2) }}</p>
</div>
@endif
@endsection

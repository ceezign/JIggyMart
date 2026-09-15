@extends('layouts.app')
@section('title', 'Transactions')
@section('content')
<h2 class="mb-4">My Transactions</h2>
<table class="table">
    <thead><tr><th>Reference</th><th>Order</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
    <tbody>
        @foreach ($transactions as $txn)
            <tr>
                <td>{{ $txn->reference }}</td>
                <td><a href="{{ route('orders.show', $txn->order) }}">{{ $txn->order->order_number }}</a></td>
                <td>₦{{ number_format($txn->amount, 2) }}</td>
                <td class="text-capitalize">{{ $txn->payment_method }}</td>
                <td><span class="badge bg-info-subtle text-info-emphasis text-capitalize">{{ $txn->status }}</span></td>
                <td>{{ $txn->created_at->format('M d, Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $transactions->links() }}
@endsection

@extends('layouts.app')
@section('title', 'Manage Transactions')
@section('content')
<h2 class="mb-4">Transactions</h2>
<table class="table">
    <thead><tr><th>Reference</th><th>User</th><th>Order</th><th>Amount</th><th>Status</th></tr></thead>
    <tbody>
    @foreach ($transactions as $txn)
        <tr>
            <td>{{ $txn->reference }}</td>
            <td>{{ $txn->user->name }}</td>
            <td>{{ $txn->order->order_number }}</td>
            <td>₦{{ number_format($txn->amount, 2) }}</td>
            <td class="text-capitalize">{{ $txn->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $transactions->links() }}
@endsection

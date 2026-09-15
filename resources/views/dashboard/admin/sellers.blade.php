@extends('layouts.app')
@section('title', 'Manage Sellers')
@section('content')
<h2 class="mb-4">Sellers</h2>
<table class="table">
    <thead><tr><th>Store</th><th>Owner</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach ($sellers as $seller)
        <tr>
            <td>{{ $seller->store_name }}</td>
            <td>{{ $seller->name }} ({{ $seller->email }})</td>
            <td><span class="badge bg-secondary-subtle text-secondary-emphasis text-capitalize">{{ $seller->seller_status }}</span></td>
            <td>
                @if ($seller->seller_status === 'pending')
                    <form method="POST" action="{{ route('admin.sellers.approve', $seller) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-success">Approve</button></form>
                    <form method="POST" action="{{ route('admin.sellers.reject', $seller) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-danger">Reject</button></form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $sellers->links() }}
@endsection

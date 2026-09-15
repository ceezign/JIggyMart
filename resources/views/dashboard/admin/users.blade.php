@extends('layouts.app')
@section('title', 'Manage Users')
@section('content')
<h2 class="mb-4">Users</h2>
<table class="table">
    <thead><tr><th>Name</th><th>Email</th><th>Roles</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
            <td><span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $user->status }}</span></td>
            <td>
                @if ($user->status === 'active')
                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}">@csrf<button class="btn btn-sm btn-outline-danger">Suspend</button></form>
                @else
                    <form method="POST" action="{{ route('admin.users.reinstate', $user) }}">@csrf<button class="btn btn-sm btn-outline-success">Reinstate</button></form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $users->links() }}
@endsection

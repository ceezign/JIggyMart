@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<h2 class="mb-4">Profile</h2>
<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Account Details</h5>
            <form method="POST" action="{{ route('dashboard.profile.update') }}">
                @csrf @method('PATCH')
                <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="{{ $user->name }}"></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" value="{{ $user->email }}" disabled></div>
                <div class="mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ $user->phone }}"></div>
                <button class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Security</h5>
            <form method="POST" action="{{ route('dashboard.password.update') }}">
                @csrf @method('PATCH')
                <div class="mb-3"><label class="form-label">Current Password</label><input type="password" name="current_password" class="form-control"></div>
                <div class="mb-3"><label class="form-label">New Password</label><input type="password" name="password" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Confirm New Password</label><input type="password" name="password_confirmation" class="form-control"></div>
                <button class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection

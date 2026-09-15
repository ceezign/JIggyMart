@extends('layouts.app')
@section('title', 'Store Profile')
@section('content')
<h2 class="mb-4">Store Profile</h2>
<div class="card p-3" style="max-width: 500px;">
    <form method="POST" action="{{ route('seller.profile.update') }}">
        @csrf @method('PATCH')
        <div class="mb-3"><label class="form-label">Store Name</label><input type="text" name="store_name" class="form-control" value="{{ $user->store_name }}"></div>
        <div class="mb-3"><label class="form-label">Description</label><textarea name="store_description" class="form-control" rows="4">{{ $user->store_description }}</textarea></div>
        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection

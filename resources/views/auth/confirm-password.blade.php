@extends('layouts.app')
@section('title', 'Confirm Password')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="mb-3">Confirm Password</h3>
            <p class="text-muted">Please confirm your password before continuing.</p>
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Confirm</button>
            </form>
        </div>
    </div>
</div>
@endsection

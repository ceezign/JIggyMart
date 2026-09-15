@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="mb-3">Forgot your password?</h3>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Email Password Reset Link</button>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="mb-3">Login</h3>
            <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100 mb-3">Continue with Google</a>
            <div class="text-center text-muted mb-3">or</div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button class="btn btn-primary w-100" type="submit">Login</button>
            </form>
            <div class="d-flex justify-content-between mt-3 small">
                <a href="{{ route('password.request') }}">Forgot password?</a>
                <a href="{{ route('register') }}">Create an account</a>
            </div>
            <div class="alert alert-secondary small mt-3 mb-0">
                Demo accounts: <br>admin@jiggymart.test / seller@jiggymart.test / customer@jiggymart.test <br>Password: <code>password</code>
            </div>
        </div>
    </div>
</div>
@endsection

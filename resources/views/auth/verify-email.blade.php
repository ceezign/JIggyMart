@extends('layouts.app')
@section('title', 'Verify Email')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center">
        <div class="card p-4">
            <h3>Verify your email</h3>
            <p class="text-muted">We sent a verification link to your email address. Click it to activate your account.</p>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="btn btn-primary">Resend Verification Email</button>
            </form>
        </div>
    </div>
</div>
@endsection

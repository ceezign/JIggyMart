@extends('layouts.app')
@section('title', 'Become a Seller')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h3 class="mb-3">Become a Seller</h3>
            <p class="text-muted">Tell us about your store. Your application will be reviewed by our team before you can publish listings.</p>
            <form method="POST" action="{{ route('seller.register.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Store name</label>
                    <input type="text" name="store_name" class="form-control" required value="{{ old('store_name') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Store description</label>
                    <textarea name="store_description" class="form-control" rows="4">{{ old('store_description') }}</textarea>
                </div>
                <button class="btn btn-primary" type="submit">Submit Application</button>
            </form>
        </div>
    </div>
</div>
@endsection

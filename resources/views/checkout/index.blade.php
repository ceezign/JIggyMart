@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<h2 class="mb-4">Checkout</h2>
<div class="row g-4">
    <div class="col-md-7">
        <div class="card p-3 mb-3">
            <h5>Shipping Address</h5>
            @if ($addresses->isEmpty())
                <p class="text-muted">You have no saved addresses yet.</p>
            @else
                @foreach ($addresses as $address)
                    <div class="form-check border rounded p-2 mb-2">
                        <input class="form-check-input" type="radio" name="address_id" form="checkoutForm" value="{{ $address->id }}" id="addr{{ $address->id }}" @checked($loop->first)>
                        <label class="form-check-label" for="addr{{ $address->id }}">
                            <strong>{{ $address->full_name }}</strong> ({{ $address->label }})<br>
                            {{ $address->line1 }}, {{ $address->city }}, {{ $address->state }}, {{ $address->country }}<br>
                            {{ $address->phone }}
                        </label>
                    </div>
                @endforeach
            @endif
            <a href="{{ route('addresses.index') }}" class="btn btn-sm btn-outline-secondary mt-2 align-self-start">Manage Addresses</a>
        </div>

        <div class="card p-3">
            <h5>Payment Method</h5>
            <select name="payment_method" form="checkoutForm" class="form-select">
                <option value="mock">Mock Gateway (Development)</option>
                <option value="paystack">Paystack</option>
                <option value="card">Card</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card p-3">
            <h5>Order Summary</h5>
            @foreach ($totals['items'] as $item)
                <div class="d-flex justify-content-between small mb-1">
                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                    <span>₦{{ number_format($item->line_total, 2) }}</span>
                </div>
            @endforeach
            <hr>
            <div class="d-flex justify-content-between"><span>Subtotal</span><span>₦{{ number_format($totals['subtotal'], 2) }}</span></div>
            <div class="d-flex justify-content-between"><span>Shipping</span><span>₦2,000.00</span></div>
            <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span>₦{{ number_format($totals['subtotal'] + 2000, 2) }}</span></div>

            <form id="checkoutForm" method="POST" action="{{ route('checkout.store') }}" class="mt-3">
                @csrf
                <button class="btn btn-primary w-100" type="submit" @disabled($addresses->isEmpty())>Place Order & Pay</button>
            </form>
            <small class="text-muted d-block mt-2">Prices and stock are re-validated on the server; nothing here is trusted from the browser.</small>
        </div>
    </div>
</div>
@endsection

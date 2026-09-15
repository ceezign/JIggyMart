@extends('layouts.app')
@section('title', 'Your Cart')
@section('content')
<h2 class="mb-4">Your Cart</h2>

@if ($totals['items']->isEmpty())
    <div class="alert alert-info">Your cart is empty. <a href="{{ route('products.index') }}">Browse products</a>.</div>
@else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Line Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($totals['items'] as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $item->product->images->first()?->url ?? 'https://placehold.co/60' }}" style="width:50px;height:50px;object-fit:cover;" class="rounded">
                                <div>
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none">{{ $item->product->name }}</a>
                                    @if ($item->variant)<div class="small text-muted">{{ $item->variant->name }}: {{ $item->variant->value }}</div>@endif
                                </div>
                            </div>
                        </td>
                        <td>₦{{ number_format($item->price_snapshot, 2) }}</td>
                        <td style="width:140px;">
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="d-flex gap-1">
                                @csrf @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" class="form-control form-control-sm">
                                <button class="btn btn-sm btn-outline-secondary" type="submit">↻</button>
                            </form>
                        </td>
                        <td>₦{{ number_format($item->line_total, 2) }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.destroy', $item) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        <div class="card p-3" style="width: 320px;">
            <div class="d-flex justify-content-between"><span>Subtotal</span><strong>₦{{ number_format($totals['subtotal'], 2) }}</strong></div>
            <small class="text-muted">Shipping and taxes calculated at checkout.</small>
            <a href="{{ route('checkout.show') }}" class="btn btn-primary mt-3">Proceed to Checkout</a>
        </div>
    </div>
@endif
@endsection

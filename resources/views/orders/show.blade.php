@extends('layouts.app')
@section('title', 'Order '.$order->order_number)
@section('content')
<h2 class="mb-1">Order {{ $order->order_number }}</h2>
<p class="text-muted">Placed {{ $order->created_at->format('M d, Y h:ia') }} · Status:
    <span class="badge bg-info-subtle text-info-emphasis text-capitalize">{{ $order->status }}</span>
</p>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card p-3 mb-3">
            <h5>Items</h5>
            <table class="table">
                <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }} × ₦{{ number_format($item->unit_price, 2) }}</td>
                        <td>₦{{ number_format($item->line_total, 2) }}</td>
                        <td>
                            @if ($order->status === 'delivered' && !$item->review)
                                <a href="#reviewModal{{ $item->id }}" data-bs-toggle="modal" class="btn btn-sm btn-outline-secondary">Leave Review</a>
                                <div class="modal fade" id="reviewModal{{ $item->id }}">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('reviews.store') }}" class="modal-content p-3">
                                            @csrf
                                            <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                                            <h6>Review {{ $item->product_name }}</h6>
                                            <select name="rating" class="form-select mb-2">
                                                @for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }} Star{{ $i>1?'s':'' }}</option>@endfor
                                            </select>
                                            <textarea name="comment" class="form-control mb-2" placeholder="Share your experience"></textarea>
                                            <button class="btn btn-primary btn-sm">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @if ($order->shippingAddress)
        <div class="card p-3">
            <h5>Shipping Address</h5>
            <p class="mb-0">{{ $order->shippingAddress->full_name }}<br>
            {{ $order->shippingAddress->line1 }}, {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}<br>
            {{ $order->shippingAddress->phone }}</p>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h5>Summary</h5>
            <div class="d-flex justify-content-between"><span>Subtotal</span><span>₦{{ number_format($order->subtotal, 2) }}</span></div>
            <div class="d-flex justify-content-between"><span>Shipping</span><span>₦{{ number_format($order->shipping_total, 2) }}</span></div>
            <div class="d-flex justify-content-between"><span>Tax</span><span>₦{{ number_format($order->tax_total, 2) }}</span></div>
            <hr>
            <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>₦{{ number_format($order->grand_total, 2) }}</span></div>

            @if ($order->transaction)
                <hr>
                <p class="small text-muted mb-0">Transaction Ref: {{ $order->transaction->reference }}</p>
                <p class="small text-muted">Payment status: <span class="text-capitalize">{{ $order->transaction->status }}</span></p>
            @endif

            @can('cancel', $order)
                <form method="POST" action="{{ route('orders.cancel', $order) }}" class="mt-2">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Cancel this order?')">Cancel Order</button>
                </form>
            @endcan
        </div>
    </div>
</div>
@endsection

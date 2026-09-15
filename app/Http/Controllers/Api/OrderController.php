<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvalidCartException;
use App\Exceptions\PaymentFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly CheckoutService $checkoutService)
    {
    }

    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('items')->latest()->paginate(15);

        return OrderResource::collection($orders);
    }

    public function show(Request $request, \App\Models\Order $order)
    {
        $this->authorize('view', $order);

        return new OrderResource($order->load(['items', 'transaction']));
    }

    public function store(CheckoutRequest $request)
    {
        try {
            $order = $this->checkoutService->checkout($request->user(), $request->address_id, $request->payment_method ?? 'mock');

            return new OrderResource($order->load(['items', 'transaction']));
        } catch (InvalidCartException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (PaymentFailedException $e) {
            return response()->json(['message' => 'Payment failed. The order was cancelled and no charge was made.'], 402);
        }
    }
}

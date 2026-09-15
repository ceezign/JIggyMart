<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request->user());

        return new CartResource($this->cartService->totals($cart));
    }

    public function store(AddCartItemRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        try {
            $this->cartService->addItem($request->user(), $product, $request->quantity, $request->variant_id);
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $cart = $this->cartService->getOrCreateCart($request->user());

        return new CartResource($this->cartService->totals($cart));
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:999']);

        try {
            $this->cartService->updateQuantity($request->user(), $item, $request->quantity);
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $cart = $this->cartService->getOrCreateCart($request->user());

        return new CartResource($this->cartService->totals($cart));
    }

    public function destroy(Request $request, CartItem $item)
    {
        $this->cartService->removeItem($request->user(), $item);

        $cart = $this->cartService->getOrCreateCart($request->user());

        return new CartResource($this->cartService->totals($cart));
    }
}

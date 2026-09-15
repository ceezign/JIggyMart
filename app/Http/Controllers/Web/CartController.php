<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
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
        $totals = $this->cartService->totals($cart);

        return view('cart.index', compact('totals'));
    }

    public function store(AddCartItemRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        try {
            $this->cartService->addItem($request->user(), $product, $request->quantity, $request->variant_id);
        } catch (InsufficientStockException $e) {
            return back()->withErrors(['quantity' => $e->getMessage()]);
        }

        return back()->with('status', 'Added to cart.');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:999']]);

        try {
            $this->cartService->updateQuantity($request->user(), $item, $request->quantity);
        } catch (InsufficientStockException $e) {
            return back()->withErrors(['quantity' => $e->getMessage()]);
        }

        return back()->with('status', 'Cart updated.');
    }

    public function destroy(Request $request, CartItem $item)
    {
        $this->cartService->removeItem($request->user(), $item);

        return back()->with('status', 'Item removed.');
    }
}

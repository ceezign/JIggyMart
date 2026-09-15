<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvalidCartException;
use App\Exceptions\PaymentFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkoutService,
        private readonly CartService $cartService,
    ) {
    }

    public function show(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request->user());
        $totals = $this->cartService->totals($cart);
        $addresses = $request->user()->addresses()->orderByDesc('is_default')->get();

        return view('checkout.index', compact('totals', 'addresses'));
    }

    public function store(CheckoutRequest $request)
    {
        try {
            $order = $this->checkoutService->checkout(
                $request->user(),
                $request->address_id,
                $request->payment_method ?? 'mock'
            );

            return redirect()->route('orders.show', $order)->with('status', 'Order placed and paid successfully!');
        } catch (InvalidCartException $e) {
            return redirect()->route('cart.index')->withErrors(['cart' => $e->getMessage()]);
        } catch (InsufficientStockException $e) {
            return back()->withErrors(['stock' => $e->getMessage()]);
        } catch (PaymentFailedException $e) {
            return back()->withErrors(['payment' => 'Payment failed. Your order was cancelled and no charge was made.']);
        }
    }
}

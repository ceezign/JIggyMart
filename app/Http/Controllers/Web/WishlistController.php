<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->user()->wishlists()->with('product.images')->latest()->get();

        return view('dashboard.customer.wishlist', compact('items'));
    }

    public function store(Request $request, Product $product)
    {
        $existing = Wishlist::where('user_id', $request->user()->id)->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->delete();

            return back()->with('status', 'Removed from wishlist.');
        }

        Wishlist::create(['user_id' => $request->user()->id, 'product_id' => $product->id]);

        return back()->with('status', 'Added to wishlist.');
    }

    public function destroy(Request $request, Wishlist $wishlist)
    {
        abort_unless($wishlist->user_id === $request->user()->id, 403);
        $wishlist->delete();

        return back()->with('status', 'Removed from wishlist.');
    }

    public function moveToCart(Request $request, Wishlist $wishlist, CartService $cartService)
    {
        abort_unless($wishlist->user_id === $request->user()->id, 403);

        $cartService->addItem($request->user(), $wishlist->product, 1);
        $wishlist->delete();

        return back()->with('status', 'Moved to cart.');
    }
}

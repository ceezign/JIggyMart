<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->active()
            ->with(['images', 'category', 'seller'])
            ->search($request->string('search')->toString() ?: null)
            ->category($request->integer('category') ?: null)
            ->seller($request->integer('seller') ?: null)
            ->condition($request->string('condition')->toString() ?: null)
            ->brand($request->string('brand')->toString() ?: null)
            ->minRating($request->float('min_rating') ?: null)
            ->priceBetween($request->float('min_price') ?: null, $request->float('max_price') ?: null)
            ->when($request->boolean('in_stock'), fn ($q) => $q->available())
            ->sorted($request->string('sort')->toString() ?: null)
            ->paginate(12)
            ->withQueryString();

        $categories = Category::active()->topLevel()->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')])->orderBy('sort_order')->get();
        $brands = Product::active()->whereNotNull('brand')->distinct()->pluck('brand');
        $wishlistIds = auth()->check() ? auth()->user()->wishlists()->pluck('product_id')->all() : [];

        return view('products.index', compact('products', 'categories', 'brands', 'wishlistIds'));
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);

        $product->increment('views_count');
        $product->load(['images', 'variants', 'category', 'seller', 'reviews.user']);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $wishlistIds = auth()->check() ? auth()->user()->wishlists()->pluck('product_id')->all() : [];
        $isWishlisted = in_array($product->id, $wishlistIds);

        return view('products.show', compact('product', 'related', 'wishlistIds', 'isWishlisted'));
    }

    public function byCategory(Category $category, Request $request)
    {
        $products = Product::active()
            ->with(['images', 'category'])
            ->whereIn('category_id', $category->selfAndChildIds())
            ->sorted($request->string('sort')->toString() ?: null)
            ->paginate(12)
            ->withQueryString();

        $wishlistIds = auth()->check() ? auth()->user()->wishlists()->pluck('product_id')->all() : [];

        return view('products.category', compact('category', 'products', 'wishlistIds'));
    }
}

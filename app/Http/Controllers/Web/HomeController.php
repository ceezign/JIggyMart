<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::active()->available()->with(['images', 'category'])
            ->orderByDesc('sales_count')->limit(8)->get();

        $newest = Product::active()->with(['images', 'category'])
            ->orderByDesc('created_at')->limit(8)->get();

        $categories = Category::active()->topLevel()->withCount('products')->orderBy('sort_order')->get();

        $wishlistIds = auth()->check() ? auth()->user()->wishlists()->pluck('product_id')->all() : [];

        return view('home.index', compact('featured', 'newest', 'categories', 'wishlistIds'));
    }
}

<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['seller', 'category'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(20);

        return view('dashboard.admin.products', compact('products'));
    }

    public function updateStatus(Request $request, Product $product)
    {
        $request->validate(['status' => 'required|in:draft,pending,active,out_of_stock,suspended,archived']);
        $product->update(['status' => $request->status]);

        return back()->with('status', 'Product status updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('status', 'Product removed.');
    }
}

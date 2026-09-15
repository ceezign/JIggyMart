<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellerProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('seller_id', $request->user()->id)->with('images')->latest()->paginate(15);

        return view('dashboard.seller.products.index', compact('products'));
    }

    public function create()
    {
        $this->authorize('create', Product::class);
        $categories = Category::active()->get();

        return view('dashboard.seller.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            ...$request->safe()->except(['images']),
            'seller_id' => $request->user()->id,
            'status' => $request->status ?? Product::STATUS_PENDING,
        ]);

        $this->storeImages($product, $request);

        return redirect()->route('seller.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::active()->get();
        $product->load('images', 'variants');

        return view('dashboard.seller.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->safe()->except(['images']));
        $this->storeImages($product, $request);

        return redirect()->route('seller.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->images->each->delete();
        $product->delete();

        return back()->with('status', 'Product deleted.');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        $this->authorize('update', $product);
        abort_unless($image->product_id === $product->id, 404);
        $image->delete();

        return back()->with('status', 'Image removed.');
    }

    private function storeImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'is_primary' => ! $hasPrimary && $index === 0,
                'sort_order' => $product->images()->count(),
            ]);
        }
    }
}

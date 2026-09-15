<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
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
            ->sorted($request->string('sort')->toString() ?: null)
            ->paginate($request->integer('per_page', 15));

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);
        $product->load(['images', 'variants', 'category', 'seller']);

        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            ...$request->safe()->except(['images']),
            'seller_id' => $request->user()->id,
            'status' => Product::STATUS_PENDING,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        }

        return new ProductResource($product->load('images'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->safe()->except(['images']));

        return new ProductResource($product->fresh(['images']));
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();

        return response()->json(['message' => 'Product deleted'], 200);
    }
}

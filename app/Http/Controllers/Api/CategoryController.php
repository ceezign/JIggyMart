<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::active()->topLevel()->with('children')->orderBy('sort_order')->get();

        return CategoryResource::collection($categories);
    }

    public function products(Category $category)
    {
        $products = $category->products()->active()->with('images')->paginate(15);

        return ProductResource::collection($products);
    }
}

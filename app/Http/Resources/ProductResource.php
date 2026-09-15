<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'brand' => $this->brand,
            'sku' => $this->when($request->user()?->id === $this->seller_id || $request->user()?->isAdmin(), $this->sku),
            'price' => (float) $this->price,
            'discount_price' => $this->discount_price ? (float) $this->discount_price : null,
            'effective_price' => (float) $this->effective_price,
            'stock_quantity' => $this->stock_quantity,
            'condition' => $this->condition,
            'status' => $this->status,
            'average_rating' => (float) $this->average_rating,
            'reviews_count' => $this->reviews_count,
            'is_in_stock' => $this->is_in_stock,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'seller' => [
                'id' => $this->seller?->id,
                'store_name' => $this->seller?->store_name ?? $this->seller?->name,
            ],
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}

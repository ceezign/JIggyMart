<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request)
    {
        $orderItem = OrderItem::with('order')->findOrFail($request->order_item_id);

        // A customer may only review a product from an order that belongs to them
        // and has actually been delivered — this is what "verified purchase" means here.
        abort_unless($orderItem->order->user_id === $request->user()->id, 403);
        abort_unless($orderItem->order->status === \App\Models\Order::STATUS_DELIVERED, 422, 'You can only review delivered orders.');

        $review = Review::updateOrCreate(
            [
                'product_id' => $orderItem->product_id,
                'user_id' => $request->user()->id,
                'order_item_id' => $orderItem->id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_verified_purchase' => true,
                'status' => 'approved',
            ]
        );

        $this->recalculateRating($orderItem->product_id);

        return back()->with('status', 'Thanks for your review!');
    }

    public function destroy(Request $request, Review $review)
    {
        $this->authorize('delete', $review);
        $productId = $review->product_id;
        $review->delete();
        $this->recalculateRating($productId);

        return back()->with('status', 'Review deleted.');
    }

    private function recalculateRating(int $productId): void
    {
        $product = \App\Models\Product::find($productId);
        $stats = Review::approved()->where('product_id', $productId)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as cnt')->first();

        $product?->update([
            'average_rating' => round($stats->avg_rating ?? 0, 2),
            'reviews_count' => $stats->cnt ?? 0,
        ]);
    }
}

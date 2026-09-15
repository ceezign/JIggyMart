<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class SellerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user();

        $products = Product::where('seller_id', $seller->id);

        $stats = [
            'total_products' => (clone $products)->count(),
            'active_listings' => (clone $products)->where('status', Product::STATUS_ACTIVE)->count(),
            'out_of_stock' => (clone $products)->where('status', Product::STATUS_OUT_OF_STOCK)->count(),
            'total_sales' => OrderItem::where('seller_id', $seller->id)->sum('line_total'),
            'pending_orders' => Order::forSeller($seller->id)->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_PAID])->count(),
            'completed_orders' => Order::forSeller($seller->id)->where('status', Order::STATUS_DELIVERED)->count(),
        ];

        $recentOrders = Order::forSeller($seller->id)->with('items')->latest()->limit(5)->get();

        return view('dashboard.seller.index', compact('stats', 'recentOrders'));
    }

    public function orders(Request $request)
    {
        $orders = Order::forSeller($request->user()->id)->with('items')->latest()->paginate(15);

        return view('dashboard.seller.orders', compact('orders'));
    }

    public function sales(Request $request)
    {
        $items = OrderItem::where('seller_id', $request->user()->id)->with(['order', 'product'])->latest()->paginate(20);

        return view('dashboard.seller.sales', compact('items'));
    }

    public function profile(Request $request)
    {
        return view('dashboard.seller.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_description' => ['nullable', 'string', 'max:2000'],
        ]);

        $request->user()->update($data);

        return back()->with('status', 'Store profile updated.');
    }
}

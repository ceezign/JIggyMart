<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InventoryService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService, private readonly InventoryService $inventoryService)
    {
    }

    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(20);

        return view('dashboard.admin.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.seller', 'transaction', 'user']);

        return view('dashboard.admin.order-show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,processing,paid,shipped,delivered,cancelled,refunded']);

        if (in_array($request->status, ['cancelled', 'refunded'], true) && ! in_array($order->status, ['cancelled', 'refunded'], true)) {
            foreach ($order->items as $item) {
                $this->inventoryService->release($item->product_id, $item->quantity, $item->product_variant_id);
            }
        }

        $order->update(['status' => $request->status]);

        return back()->with('status', 'Order status updated.');
    }
}

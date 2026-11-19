<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with('user')
            ->withCount('items')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('code', 'like', '%'.$request->q.'%')
                        ->orWhere('customer_name', 'like', '%'.$request->q.'%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load('items', 'user'),
            'statuses' => Order::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
        ]);

        if ($data['status'] === 'dibatalkan' && $order->status !== 'dibatalkan') {
            foreach ($order->items as $item) {
                $item->menu?->increment('stock', $item->quantity);
            }
        }

        $order->update($data);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}

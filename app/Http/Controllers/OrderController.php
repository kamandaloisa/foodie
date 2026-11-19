<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->withCount('items')
            ->latest()
            ->paginate(8);

        return view('orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $items = Cart::items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        return view('orders.checkout', [
            'items' => $items,
            'total' => Cart::total(),
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request)
    {
        $items = Cart::items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $order = DB::transaction(function () use ($request, $items, $data) {
            $order = $request->user()->orders()->create([
                ...$data,
                'code' => 'FD-'.now()->format('ymd').'-'.Str::upper(Str::random(5)),
                'status' => 'pending',
                'total' => $items->sum('subtotal'),
            ]);

            foreach ($items as $item) {
                $menu = $item['menu']->fresh();

                if (! $menu || $menu->stock < $item['quantity']) {
                    abort(422, 'Stok '.$item['menu']->name.' tidak mencukupi.');
                }

                $order->items()->create([
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'price' => $menu->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                $menu->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        Cart::clear();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat. Kode: '.$order->code);
    }

    public function show(Request $request, Order $order)
    {
        $this->authorizeAccess($request, $order);

        return view('orders.show', [
            'order' => $order->load('items'),
        ]);
    }

    public function cancel(Request $request, Order $order)
    {
        $this->authorizeAccess($request, $order);

        if (! $order->isCancellable()) {
            return back()->with('error', 'Pesanan ini tidak bisa dibatalkan.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->menu?->increment('stock', $item->quantity);
            }

            $order->update(['status' => 'dibatalkan']);
        });

        return back()->with('success', 'Pesanan dibatalkan.');
    }

    private function authorizeAccess(Request $request, Order $order): void
    {
        $user = $request->user();

        abort_unless(
            $user instanceof User && ($user->isAdmin() || $order->user_id === $user->id),
            403
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Support\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index', [
            'items' => Cart::items(),
            'total' => Cart::total(),
        ]);
    }

    public function store(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        if (! $menu->isOrderable()) {
            return back()->with('error', 'Menu ini sedang tidak tersedia.');
        }

        Cart::add($menu, $data['quantity'] ?? 1);

        return back()->with('success', $menu->name.' ditambahkan ke keranjang.');
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        Cart::update($menu, $data['quantity']);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        Cart::remove($menu);

        return back()->with('success', $menu->name.' dihapus dari keranjang.');
    }
}

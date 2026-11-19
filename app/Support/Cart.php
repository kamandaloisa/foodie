<?php

namespace App\Support;

use App\Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class Cart
{
    private const KEY = 'cart';

    /**
     * @return array<int, int>
     */
    public static function contents(): array
    {
        return Session::get(self::KEY, []);
    }

    public static function add(Menu $menu, int $quantity = 1): void
    {
        $cart = self::contents();
        $current = $cart[$menu->id] ?? 0;
        $cart[$menu->id] = min($current + max(1, $quantity), $menu->stock);

        Session::put(self::KEY, $cart);
    }

    public static function update(Menu $menu, int $quantity): void
    {
        $cart = self::contents();

        if ($quantity <= 0) {
            unset($cart[$menu->id]);
        } else {
            $cart[$menu->id] = min($quantity, $menu->stock);
        }

        Session::put(self::KEY, $cart);
    }

    public static function remove(Menu $menu): void
    {
        $cart = self::contents();
        unset($cart[$menu->id]);
        Session::put(self::KEY, $cart);
    }

    public static function clear(): void
    {
        Session::forget(self::KEY);
    }

    public static function count(): int
    {
        return array_sum(self::contents());
    }

    /**
     * @return Collection<int, array{menu: Menu, quantity: int, subtotal: int}>
     */
    public static function items(): Collection
    {
        $cart = self::contents();

        if (empty($cart)) {
            return collect();
        }

        $menus = Menu::query()
            ->with('category')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        return collect($cart)
            ->map(function (int $quantity, int $menuId) use ($menus) {
                $menu = $menus->get($menuId);

                if (! $menu) {
                    return null;
                }

                $quantity = min($quantity, max($menu->stock, 0));

                if ($quantity <= 0) {
                    return null;
                }

                return [
                    'menu' => $menu,
                    'quantity' => $quantity,
                    'subtotal' => $menu->price * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public static function total(): int
    {
        return self::items()->sum('subtotal');
    }
}

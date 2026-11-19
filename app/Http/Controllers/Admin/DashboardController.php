<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $revenue = Order::query()
            ->where('status', 'selesai')
            ->sum('total');

        $recentOrders = Order::query()
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        $topMenus = Menu::query()
            ->withSum(['orderItems as sold' => function ($query) {
                $query->whereHas('order', fn ($q) => $q->where('status', '!=', 'dibatalkan'));
            }], 'quantity')
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => [
                'orders_today' => Order::whereDate('created_at', today())->count(),
                'orders_pending' => Order::where('status', 'pending')->count(),
                'revenue' => $revenue,
                'menus' => Menu::count(),
                'categories' => Category::count(),
                'customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
            ],
            'recentOrders' => $recentOrders,
            'topMenus' => $topMenus,
        ]);
    }
}

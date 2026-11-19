<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $menus = Menu::query()
            ->with('category')
            ->where('is_available', true)
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%');
            })
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('menus.index', [
            'categories' => $categories,
            'menus' => $menus,
            'activeCategory' => $request->category,
            'search' => $request->q,
        ]);
    }

    public function show(Menu $menu)
    {
        abort_unless($menu->is_available, 404);

        $related = Menu::query()
            ->where('category_id', $menu->category_id)
            ->whereKeyNot($menu->id)
            ->where('is_available', true)
            ->take(3)
            ->get();

        return view('menus.show', [
            'menu' => $menu->load('category'),
            'related' => $related,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::query()
            ->with('category')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->q.'%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.form', [
            'menu' => new Menu,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Menu::create($this->validated($request));

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menus.form', [
            'menu' => $menu,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $menu->update($this->validated($request));

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|url|max:255',
        ]);

        $data['is_available'] = $request->boolean('is_available');

        return $data;
    }
}

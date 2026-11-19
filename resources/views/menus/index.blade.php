@extends('layouts.app')

@section('title', 'Menu')

@section('content')
    <div class="split" style="margin-bottom: 18px;">
        <h1 style="margin: 0;">Menu Foodie</h1>
        <a href="{{ route('cart.index') }}" class="btn btn-outline">Keranjang ({{ \App\Support\Cart::count() }})</a>
    </div>

    <form method="GET" action="{{ route('menus.index') }}" class="filters">
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari menu..." style="max-width: 260px;">
        @if ($activeCategory)
            <input type="hidden" name="category" value="{{ $activeCategory }}">
        @endif
        <button type="submit" class="btn btn-sm">Cari</button>
        @if ($search || $activeCategory)
            <a href="{{ route('menus.index') }}" class="pill">Reset</a>
        @endif
    </form>

    <div class="filters">
        <a href="{{ route('menus.index', array_filter(['q' => $search])) }}" class="pill {{ ! $activeCategory ? 'active' : '' }}">Semua</a>
        @foreach ($categories as $category)
            <a href="{{ route('menus.index', array_filter(['category' => $category->slug, 'q' => $search])) }}"
               class="pill {{ $activeCategory === $category->slug ? 'active' : '' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    @if ($menus->isEmpty())
        <div class="card">
            <p class="muted" style="margin: 0;">Tidak ada menu yang cocok dengan pencarian kamu.</p>
        </div>
    @else
        <div class="grid grid-3">
            @foreach ($menus as $menu)
                <div class="card menu-card">
                    <div class="thumb">
                        @if ($menu->image_url)
                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}">
                        @else
                            🍲
                        @endif
                    </div>

                    <span class="muted" style="font-size: 12px;">{{ $menu->category->name }}</span>
                    <h3 style="margin: 4px 0 6px;">
                        <a href="{{ route('menus.show', $menu) }}">{{ $menu->name }}</a>
                    </h3>
                    <p class="muted" style="font-size: 13px; margin: 0 0 10px; flex: 1;">
                        {{ \Illuminate\Support\Str::limit($menu->description, 70) }}
                    </p>

                    <div class="split" style="margin-bottom: 12px;">
                        <span class="price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        <span class="muted" style="font-size: 12px;">Stok: {{ $menu->stock }}</span>
                    </div>

                    @if ($menu->isOrderable())
                        <form method="POST" action="{{ route('cart.store', $menu) }}" class="inline-form">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $menu->stock }}" style="width: 70px;">
                            <button type="submit" class="btn btn-sm" style="flex: 1;">Tambah</button>
                        </form>
                    @else
                        <button class="btn btn-sm" disabled>Stok habis</button>
                    @endif
                </div>
            @endforeach
        </div>

        <div style="margin-top: 10px;">
            {{ $menus->links() }}
        </div>
    @endif
@endsection

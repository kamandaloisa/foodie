@extends('layouts.app')

@section('title', $menu->name)

@section('content')
    <p><a href="{{ route('menus.index') }}">← Kembali ke menu</a></p>

    <div class="grid grid-2">
        <div class="card" style="display: flex; align-items: center; justify-content: center; font-size: 80px; min-height: 240px; background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
            @if ($menu->image_url)
                <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" style="max-width: 100%; max-height: 240px; border-radius: 12px;">
            @else
                🍲
            @endif
        </div>

        <div class="card">
            <span class="muted">{{ $menu->category->name }}</span>
            <h1 style="margin: 6px 0 10px;">{{ $menu->name }}</h1>
            <p class="muted">{{ $menu->description ?: 'Belum ada deskripsi untuk menu ini.' }}</p>

            <p class="order-total" style="margin: 18px 0 6px;">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
            <p class="muted" style="margin-top: 0;">Stok tersedia: {{ $menu->stock }}</p>

            @if ($menu->isOrderable())
                <form method="POST" action="{{ route('cart.store', $menu) }}" class="inline-form" style="margin-top: 14px;">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $menu->stock }}" style="width: 90px;">
                    <button type="submit" class="btn">Tambah ke Keranjang</button>
                </form>
            @else
                <button class="btn" disabled>Stok habis</button>
            @endif
        </div>
    </div>

    @if ($related->isNotEmpty())
        <h2 style="margin-top: 26px;">Menu serupa</h2>
        <div class="grid grid-3">
            @foreach ($related as $item)
                <div class="card menu-card">
                    <div class="thumb">{{ $item->image_url ? '' : '🍽️' }}
                        @if ($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                        @endif
                    </div>
                    <h3 style="margin: 0 0 6px;"><a href="{{ route('menus.show', $item) }}">{{ $item->name }}</a></h3>
                    <span class="price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>
    @endif
@endsection

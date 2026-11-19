@extends('layouts.app')

@section('title', $menu->exists ? 'Edit Menu' : 'Menu Baru')

@section('content')
    <p><a href="{{ route('admin.menus.index') }}">← Kembali ke menu</a></p>

    <div class="card" style="max-width: 680px;">
        <h1>{{ $menu->exists ? 'Edit Menu' : 'Menu Baru' }}</h1>

        <form method="POST"
              action="{{ $menu->exists ? route('admin.menus.update', $menu) : route('admin.menus.store') }}">
            @csrf
            @if ($menu->exists)
                @method('PUT')
            @endif

            <label for="name">Nama Menu</label>
            <input id="name" type="text" name="name" value="{{ old('name', $menu->name) }}" required>

            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- Pilih kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) old('category_id', $menu->category_id) === $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="3">{{ old('description', $menu->description) }}</textarea>

            <div class="grid grid-2">
                <div>
                    <label for="price">Harga (Rp)</label>
                    <input id="price" type="number" name="price" min="0" value="{{ old('price', $menu->price ?? 0) }}" required>
                </div>
                <div>
                    <label for="stock">Stok</label>
                    <input id="stock" type="number" name="stock" min="0" value="{{ old('stock', $menu->stock ?? 0) }}" required>
                </div>
            </div>

            <label for="image_url">URL Gambar (opsional)</label>
            <input id="image_url" type="url" name="image_url" value="{{ old('image_url', $menu->image_url) }}">

            <label style="display: flex; align-items: center; gap: 8px; margin-top: 14px;">
                <input type="checkbox" name="is_available" value="1" style="width: auto;"
                       @checked(old('is_available', $menu->exists ? $menu->is_available : true))>
                Tersedia untuk dipesan
            </label>

            <button type="submit" class="btn" style="margin-top: 18px;">Simpan</button>
        </form>
    </div>
@endsection

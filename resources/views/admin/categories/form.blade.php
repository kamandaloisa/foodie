@extends('layouts.app')

@section('title', $category->exists ? 'Edit Kategori' : 'Kategori Baru')

@section('content')
    <p><a href="{{ route('admin.categories.index') }}">← Kembali ke kategori</a></p>

    <div class="card" style="max-width: 620px;">
        <h1>{{ $category->exists ? 'Edit Kategori' : 'Kategori Baru' }}</h1>

        <form method="POST"
              action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
            @csrf
            @if ($category->exists)
                @method('PUT')
            @endif

            <label for="name">Nama Kategori</label>
            <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required>

            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>

            <label style="display: flex; align-items: center; gap: 8px; margin-top: 14px;">
                <input type="checkbox" name="is_active" value="1" style="width: auto;"
                       @checked(old('is_active', $category->exists ? $category->is_active : true))>
                Aktif
            </label>

            <button type="submit" class="btn" style="margin-top: 18px;">Simpan</button>
        </form>
    </div>
@endsection

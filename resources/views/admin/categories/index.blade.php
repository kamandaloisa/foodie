@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
    <div class="split" style="margin-bottom: 18px;">
        <h1 style="margin: 0;">Kategori</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn">+ Kategori Baru</a>
    </div>

    <div class="card">
        @if ($categories->isEmpty())
            <p class="muted">Belum ada kategori.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Menu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td class="muted">{{ \Illuminate\Support\Str::limit($category->description, 60) }}</td>
                            <td>{{ $category->menus_count }}</td>
                            <td>
                                <span class="badge {{ $category->is_active ? 'badge-selesai' : 'badge-dibatalkan' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="inline-form">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-light">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                          onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $categories->links() }}
@endsection

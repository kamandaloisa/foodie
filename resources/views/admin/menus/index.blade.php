@extends('layouts.app')

@section('title', 'Kelola Menu')

@section('content')
    <div class="split" style="margin-bottom: 18px;">
        <h1 style="margin: 0;">Menu</h1>
        <a href="{{ route('admin.menus.create') }}" class="btn">+ Menu Baru</a>
    </div>

    <form method="GET" class="filters">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari menu..." style="max-width: 260px;">
        <button type="submit" class="btn btn-sm">Cari</button>
    </form>

    <div class="card">
        @if ($menus->isEmpty())
            <p class="muted">Belum ada menu.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menus as $menu)
                        <tr>
                            <td>{{ $menu->name }}</td>
                            <td class="muted">{{ $menu->category->name }}</td>
                            <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td>{{ $menu->stock }}</td>
                            <td>
                                <span class="badge {{ $menu->is_available ? 'badge-selesai' : 'badge-dibatalkan' }}">
                                    {{ $menu->is_available ? 'Tersedia' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="inline-form">
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-light">Edit</a>
                                    <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}"
                                          onsubmit="return confirm('Hapus menu ini?')">
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

    {{ $menus->links() }}
@endsection

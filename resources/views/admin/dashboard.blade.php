@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="split" style="margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0 0 4px;">Admin Dashboard</h1>
            <p class="muted" style="margin: 0;">Ringkasan operasional Foodie hari ini.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.menus.create') }}" class="btn btn-outline">+ Menu</a>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-outline">+ Kategori</a>
        </div>
    </div>

    <div class="grid grid-3" style="margin-bottom: 22px;">
        <div class="stat">
            <div class="label">Pesanan Hari Ini</div>
            <div class="value">{{ $stats['orders_today'] }}</div>
        </div>
        <div class="stat">
            <div class="label">Menunggu Diproses</div>
            <div class="value">{{ $stats['orders_pending'] }}</div>
        </div>
        <div class="stat">
            <div class="label">Pendapatan (Selesai)</div>
            <div class="value" style="font-size: 22px;">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="stat">
            <div class="label">Total Menu</div>
            <div class="value">{{ $stats['menus'] }}</div>
        </div>
        <div class="stat">
            <div class="label">Kategori</div>
            <div class="value">{{ $stats['categories'] }}</div>
        </div>
        <div class="stat">
            <div class="label">Pelanggan</div>
            <div class="value">{{ $stats['customers'] }}</div>
        </div>
    </div>

    <div class="card">
        <div class="split" style="margin-bottom: 12px;">
            <h2 style="margin: 0;">Pesanan Terbaru</h2>
            <a href="{{ route('admin.orders.index') }}">Lihat semua</a>
        </div>

        @if ($recentOrders->isEmpty())
            <p class="muted">Belum ada pesanan.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                        <tr>
                            <td>{{ $order->code }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td><span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                            <td>{{ $order->created_at->diffForHumans() }}</td>
                            <td><a href="{{ route('admin.orders.show', $order) }}">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card">
        <h2>Menu Terlaris</h2>

        @if ($topMenus->isEmpty())
            <p class="muted">Belum ada data penjualan.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Terjual</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topMenus as $menu)
                        <tr>
                            <td>{{ $menu->name }}</td>
                            <td>{{ (int) $menu->sold }}</td>
                            <td>{{ $menu->stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

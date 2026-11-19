@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="split" style="margin-bottom: 20px;">
        <div>
            <h1 style="margin-bottom: 4px;">Halo, {{ auth()->user()->name }}! 👋</h1>
            <p class="muted" style="margin: 0;">Selamat datang di Foodie, pesan makanan favoritmu sekarang.</p>
        </div>
        <a href="{{ route('menus.index') }}" class="btn">Lihat Menu</a>
    </div>

    <div class="grid grid-3" style="margin-bottom: 22px;">
        <div class="stat">
            <div class="label">Total Pesanan</div>
            <div class="value">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="stat">
            <div class="label">Pesanan Aktif</div>
            <div class="value">{{ $stats['active_orders'] }}</div>
        </div>
        <div class="stat">
            <div class="label">Total Belanja</div>
            <div class="value" style="font-size: 22px;">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="card">
        <div class="split" style="margin-bottom: 12px;">
            <h2 style="margin: 0;">Pesanan Terakhir</h2>
            <a href="{{ route('orders.index') }}">Lihat semua</a>
        </div>

        @if ($orders->isEmpty())
            <p class="muted">Belum ada pesanan. Yuk pesan makanan pertamamu!</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->code }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td><span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                            <td><a href="{{ route('orders.show', $order) }}">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

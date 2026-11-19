@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="split" style="margin-bottom: 18px;">
        <h1 style="margin: 0;">Pesanan Saya</h1>
        <a href="{{ route('menus.index') }}" class="btn">Pesan Lagi</a>
    </div>

    @if ($orders->isEmpty())
        <div class="card">
            <p class="muted">Belum ada pesanan.</p>
        </div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Item</th>
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
                            <td>{{ $order->items_count }} item</td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td><span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                            <td><a href="{{ route('orders.show', $order) }}">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    @endif
@endsection

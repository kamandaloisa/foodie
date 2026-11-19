@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <p><a href="{{ auth()->user()->isAdmin() ? route('admin.orders.index') : route('orders.index') }}">← Kembali</a></p>

    <div class="card">
        <div class="split" style="margin-bottom: 14px;">
            <div>
                <h1 style="margin: 0 0 4px;">{{ $order->code }}</h1>
                <span class="muted">{{ $order->created_at->format('d M Y H:i') }}</span>
            </div>
            <span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->menu_name }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="split" style="margin-top: 16px;">
            <strong>Total</strong>
            <span class="order-total">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h2>Data Pengiriman</h2>
            <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
            <p><strong>HP:</strong> {{ $order->phone }}</p>
            <p><strong>Alamat:</strong> {{ $order->address }}</p>
            @if ($order->notes)
                <p><strong>Catatan:</strong> {{ $order->notes }}</p>
            @endif
        </div>

        <div class="card">
            <h2>Aksi</h2>
            @if ($order->isCancellable())
                <form method="POST" action="{{ route('orders.cancel', $order) }}"
                      onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                    @csrf
                    <button type="submit" class="btn btn-danger">Batalkan Pesanan</button>
                </form>
            @else
                <p class="muted" style="margin: 0;">Pesanan dengan status {{ strtolower($order->status_label) }} tidak dapat dibatalkan.</p>
            @endif
        </div>
    </div>
@endsection

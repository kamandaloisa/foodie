@extends('layouts.app')

@section('title', 'Detail Pesanan Admin')

@section('content')
    <p><a href="{{ route('admin.orders.index') }}">← Kembali ke pesanan</a></p>

    <div class="card">
        <div class="split" style="margin-bottom: 14px;">
            <div>
                <h1 style="margin: 0 0 4px;">{{ $order->code }}</h1>
                <span class="muted">{{ $order->created_at->format('d M Y H:i') }} • {{ $order->user->name }}</span>
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
            <h2>Ubah Status</h2>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf
                @method('PATCH')

                <select name="status">
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn" style="margin-top: 14px; width: 100%;">Simpan Status</button>
            </form>

            <p class="muted" style="font-size: 13px; margin-top: 12px;">
                Membatalkan pesanan otomatis mengembalikan stok menu.
            </p>
        </div>
    </div>
@endsection

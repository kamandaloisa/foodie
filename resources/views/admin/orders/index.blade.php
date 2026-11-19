@extends('layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')
    <h1>Pesanan</h1>

    <form method="GET" class="filters">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode / nama..." style="max-width: 240px;">
        <select name="status" style="max-width: 180px;">
            <option value="">Semua status</option>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-sm">Filter</button>
        @if (request()->hasAny(['q', 'status']))
            <a href="{{ route('admin.orders.index') }}" class="pill">Reset</a>
        @endif
    </form>

    <div class="card">
        @if ($orders->isEmpty())
            <p class="muted">Tidak ada pesanan.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->code }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->items_count }}</td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td><span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td><a href="{{ route('admin.orders.show', $order) }}">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $orders->links() }}
@endsection

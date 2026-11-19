@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <h1>Keranjang Belanja</h1>

    @if ($items->isEmpty())
        <div class="card">
            <p class="muted">Keranjang kamu masih kosong.</p>
            <a href="{{ route('menus.index') }}" class="btn">Pesan Menu</a>
        </div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>
                                <a href="{{ route('menus.show', $item['menu']) }}">{{ $item['menu']->name }}</a>
                                <div class="muted" style="font-size: 12px;">Stok: {{ $item['menu']->stock }}</div>
                            </td>
                            <td>Rp {{ number_format($item['menu']->price, 0, ',', '.') }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.update', $item['menu']) }}" class="inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="{{ $item['menu']->stock }}" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-light">Ubah</button>
                                </form>
                            </td>
                            <td class="price">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.destroy', $item['menu']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="split" style="margin-top: 20px;">
                <a href="{{ route('menus.index') }}">← Lanjut pilih menu</a>
                <div style="text-align: right;">
                    <div class="muted" style="font-size: 13px;">Total</div>
                    <div class="order-total">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>
            </div>

            <div style="text-align: right; margin-top: 14px;">
                <a href="{{ route('orders.create') }}" class="btn">Lanjut ke Checkout</a>
            </div>
        </div>
    @endif
@endsection

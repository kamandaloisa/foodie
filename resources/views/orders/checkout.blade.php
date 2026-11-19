@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <h1>Checkout</h1>

    <div class="grid grid-2" style="align-items: start;">
        <div class="card">
            <h2>Data Pengiriman</h2>

            <form method="POST" action="{{ route('orders.store') }}">
                @csrf

                <label for="customer_name">Nama Penerima</label>
                <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name', $user->name) }}" required>

                <label for="phone">Nomor HP</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required>

                <label for="address">Alamat Pengiriman</label>
                <textarea id="address" name="address" rows="3" required>{{ old('address') }}</textarea>

                <label for="notes">Catatan (opsional)</label>
                <textarea id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>

                <button type="submit" class="btn" style="margin-top: 18px; width: 100%;">Buat Pesanan</button>
            </form>
        </div>

        <div class="card">
            <h2>Ringkasan Pesanan</h2>

            @foreach ($items as $item)
                <div class="split" style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                    <span>{{ $item['menu']->name }} <span class="muted">x{{ $item['quantity'] }}</span></span>
                    <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
            @endforeach

            <div class="split" style="margin-top: 16px;">
                <strong>Total</strong>
                <span class="order-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <p style="margin-top: 18px;"><a href="{{ route('cart.index') }}">← Ubah keranjang</a></p>
        </div>
    </div>
@endsection

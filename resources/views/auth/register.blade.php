@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <h2>Buat akun Foodie</h2>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register.process') }}">
        @csrf

        <label for="name">Nama Lengkap</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <label for="password_confirmation">Konfirmasi Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>

        <button type="submit">Daftar</button>
    </form>

    <p class="foot">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
@endsection

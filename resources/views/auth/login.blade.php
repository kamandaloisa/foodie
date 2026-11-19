@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <h2>Masuk ke akun kamu</h2>

    @if ($errors->any())
        <p class="error">{{ $errors->first() }}</p>
    @endif

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('login.process') }}">
        @csrf

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <label class="check">
            <input type="checkbox" name="remember" value="1">
            Ingat saya
        </label>

        <button type="submit">Login</button>
    </form>

    <p class="foot">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
@endsection

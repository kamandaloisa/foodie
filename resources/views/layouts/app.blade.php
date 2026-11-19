<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Foodie') - Foodie</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f3;
            color: #1f2933;
        }
        a { color: #198754; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .navbar {
            background: #198754;
            color: #fff;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            gap: 22px;
            flex-wrap: wrap;
        }
        .navbar .brand { font-size: 20px; font-weight: 700; color: #fff; }
        .navbar .brand:hover { text-decoration: none; }
        .navbar nav { display: flex; gap: 16px; flex-wrap: wrap; align-items: center; }
        .navbar nav a { color: #e9f7ef; font-weight: 500; }
        .navbar .spacer { flex: 1; }
        .navbar .user { color: #d1fae5; font-size: 14px; }
        .container { max-width: 1100px; margin: 0 auto; padding: 28px 20px 60px; }
        .card {
            background: #fff;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, .06);
            margin-bottom: 20px;
        }
        .grid { display: grid; gap: 18px; }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }
        .grid-4 { grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); }
        h1, h2, h3 { margin-top: 0; }
        .muted { color: #6b7280; }
        .btn {
            display: inline-block;
            background: #198754;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }
        .btn:hover { background: #157347; text-decoration: none; }
        .btn-sm { padding: 6px 12px; font-size: 13px; }
        .btn-light { background: #e9f7ef; color: #198754; }
        .btn-light:hover { background: #d1fae5; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #bb2d3b; }
        .btn-outline { background: transparent; color: #198754; border: 1px solid #198754; }
        .btn-outline:hover { background: #198754; color: #fff; }
        .btn[disabled] { background: #cbd5e1; cursor: not-allowed; }
        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }
        label { display: block; font-weight: 600; font-size: 14px; margin: 12px 0 6px; }
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 8px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        th { background: #f9fafb; font-size: 13px; text-transform: uppercase; letter-spacing: .03em; color: #6b7280; }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-diproses { background: #dbeafe; color: #1e40af; }
        .badge-siap { background: #e0e7ff; color: #3730a3; }
        .badge-selesai { background: #d1fae5; color: #065f46; }
        .badge-dibatalkan { background: #fee2e2; color: #991b1b; }
        .stat { background: #fff; border-radius: 14px; padding: 18px; box-shadow: 0 2px 10px rgba(15,23,42,.06); }
        .stat .label { font-size: 13px; color: #6b7280; }
        .stat .value { font-size: 26px; font-weight: 700; margin-top: 6px; color: #198754; }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin-bottom: 18px; }
        .filters a, .filters button { }
        .pill { padding: 7px 14px; border-radius: 999px; background: #fff; border: 1px solid #d1d5db; font-size: 13px; }
        .pill.active { background: #198754; border-color: #198754; color: #fff; }
        .pill:hover { text-decoration: none; }
        .menu-card { display: flex; flex-direction: column; }
        .menu-card .thumb {
            height: 130px;
            border-radius: 10px;
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            margin-bottom: 12px;
            overflow: hidden;
        }
        .menu-card .thumb img { width: 100%; height: 100%; object-fit: cover; }
        .price { font-weight: 700; color: #198754; }
        .inline-form { display: inline-flex; gap: 8px; align-items: center; }
        .pagination { display: flex; gap: 6px; list-style: none; padding: 0; margin: 22px 0 0; flex-wrap: wrap; }
        .pagination li span, .pagination li a {
            display: inline-block;
            padding: 7px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #fff;
            font-size: 14px;
        }
        .pagination li.active span { background: #198754; border-color: #198754; color: #fff; }
        .split { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .order-total { font-size: 22px; font-weight: 700; color: #198754; }
        @media (max-width: 640px) {
            .navbar { padding: 12px 16px; }
            .container { padding: 18px 14px 50px; }
        }
    </style>
</head>
<body>
<header class="navbar">
    <a class="brand" href="{{ auth()->check() ? route('dashboard') : route('login') }}">🍽️ Foodie</a>

    @auth
        <nav>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('menus.index') }}">Menu</a>
            <a href="{{ route('orders.index') }}">Pesanan</a>
            <a href="{{ route('cart.index') }}">Keranjang ({{ \App\Support\Cart::count() }})</a>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">Admin</a>
            @endif
        </nav>

        <span class="spacer"></span>
        <span class="user">{{ auth()->user()->name }}</span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-light">Logout</button>
        </form>
    @endauth
</header>

<main class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>

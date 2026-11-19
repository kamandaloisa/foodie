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
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }
        .container {
            background: #fff;
            padding: 35px;
            border-radius: 16px;
            width: 100%;
            max-width: 390px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .12);
        }
        .brand { text-align: center; font-size: 26px; font-weight: 800; color: #198754; margin-bottom: 4px; }
        h2 { text-align: center; color: #111827; font-size: 19px; margin: 0 0 20px; font-weight: 600; }
        input {
            width: 100%;
            padding: 12px;
            margin: 6px 0 4px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }
        label { font-size: 14px; font-weight: 600; }
        button {
            width: 100%;
            padding: 12px;
            background: #198754;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            margin-top: 16px;
        }
        button:hover { background: #157347; }
        a { color: #198754; }
        .error { background: #fee2e2; color: #991b1b; padding: 10px 14px; border-radius: 8px; font-size: 14px; }
        .success { background: #d1fae5; color: #065f46; padding: 10px 14px; border-radius: 8px; font-size: 14px; }
        .foot { text-align: center; font-size: 14px; margin-top: 18px; color: #4b5563; }
        .check { display: flex; align-items: center; gap: 8px; margin-top: 10px; font-weight: 400; }
        .check input { width: auto; margin: 0; }
    </style>
</head>
<body>
<div class="container">
    <div class="brand">🍽️ Foodie</div>

    @yield('content')
</div>
</body>
</html>

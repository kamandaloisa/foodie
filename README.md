# Foodie

Aplikasi pemesanan makanan berbasis Laravel 13 dengan dua peran pengguna: **pelanggan** dan **admin**.

## Fitur

### Pelanggan
- Registrasi & login (dengan opsi "ingat saya")
- Melihat katalog menu, filter per kategori, dan pencarian
- Detail menu beserta menu serupa
- Keranjang belanja berbasis session (tambah, ubah jumlah, hapus)
- Checkout dengan data pengiriman & catatan
- Riwayat pesanan dan detail pesanan
- Membatalkan pesanan (stok otomatis dikembalikan)

### Admin
- Dashboard statistik: pesanan hari ini, pesanan menunggu, pendapatan, jumlah menu/kategori/pelanggan, dan menu terlaris
- CRUD kategori (tidak bisa dihapus jika masih punya menu)
- CRUD menu (harga, stok, status ketersediaan, gambar)
- Daftar pesanan dengan filter status & pencarian
- Detail pesanan dan perubahan status (`pending`, `diproses`, `siap`, `selesai`, `dibatalkan`)
- Pembatalan pesanan otomatis mengembalikan stok

## Kebutuhan

- PHP 8.3+ dengan ekstensi `pdo_mysql` (atau `pdo_sqlite` untuk testing)
- Composer
- MySQL / MariaDB (default: `foodie_db`)

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Sesuaikan koneksi database pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foodie_db
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi dan seeder:

```bash
php artisan migrate
php artisan db:seed
```

Jalankan server:

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Akun Demo

| Peran      | Email                   | Password   |
|------------|-------------------------|------------|
| Admin      | admin@foodie.test       | `password` |
| Pelanggan  | pelanggan@foodie.test   | `password` |

Seeder juga mengisi 4 kategori dan 13 menu contoh.

## Struktur Utama

```
app/
  Http/Controllers/
    AuthController.php          # login, register, logout, dashboard
    MenuController.php          # katalog & detail menu
    CartController.php          # keranjang belanja
    OrderController.php         # checkout, riwayat, detail, batal
    Admin/                      # controller panel admin
  Http/Middleware/
    EnsureUserIsAdmin.php       # alias middleware "admin"
  Models/
    User.php  Category.php  Menu.php  Order.php  OrderItem.php
  Support/
    Cart.php                    # logika keranjang berbasis session
resources/views/
  layouts/                      # layout app & guest
  auth/  menus/  cart/  orders/  admin/
routes/web.php
database/migrations/  database/seeders/
```

## Testing

Test menggunakan SQLite in-memory (lihat `phpunit.xml`), sehingga ekstensi `pdo_sqlite` perlu aktif.

```bash
php artisan test
```

## Lisensi

Dirilis di bawah lisensi [MIT](https://opensource.org/licenses/MIT).

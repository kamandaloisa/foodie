<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@foodie.test'],
            [
                'name' => 'Admin Foodie',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        User::updateOrCreate(
            ['email' => 'pelanggan@foodie.test'],
            [
                'name' => 'Pelanggan Foodie',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CUSTOMER,
            ]
        );

        $catalog = [
            'Makanan Utama' => [
                ['Nasi Goreng Spesial', 'Nasi goreng dengan telur, ayam, dan sosis.', 25000, 50],
                ['Mie Ayam Bakso', 'Mie ayam dengan bakso sapi dan pangsit.', 22000, 40],
                ['Ayam Geprek Sambal Ijo', 'Ayam crispy dengan sambal ijo pedas.', 27000, 35],
                ['Sate Ayam Madura', '10 tusuk sate ayam dengan bumbu kacang.', 30000, 30],
            ],
            'Camilan' => [
                ['Kentang Goreng', 'Kentang goreng renyah dengan saus.', 15000, 60],
                ['Pisang Goreng Keju', 'Pisang goreng dengan topping keju.', 18000, 45],
                ['Tahu Crispy', 'Tahu goreng crispy dengan bumbu tabur.', 12000, 50],
            ],
            'Minuman' => [
                ['Es Teh Manis', 'Teh manis dingin segar.', 8000, 100],
                ['Es Jeruk', 'Perasan jeruk segar dengan es.', 10000, 80],
                ['Kopi Susu Gula Aren', 'Kopi susu dengan gula aren.', 20000, 40],
                ['Air Mineral', 'Botol air mineral 600ml.', 5000, 120],
            ],
            'Dessert' => [
                ['Es Krim Coklat', 'Es krim coklat lembut.', 15000, 25],
                ['Pudding Mangga', 'Pudding mangga dengan saus vanilla.', 17000, 20],
            ],
        ];

        foreach ($catalog as $categoryName => $menus) {
            $category = Category::updateOrCreate(
                ['name' => $categoryName],
                ['description' => 'Pilihan '.strtolower($categoryName).' terbaik dari Foodie.', 'is_active' => true]
            );

            foreach ($menus as [$name, $description, $price, $stock]) {
                Menu::updateOrCreate(
                    ['name' => $name],
                    [
                        'category_id' => $category->id,
                        'description' => $description,
                        'price' => $price,
                        'stock' => $stock,
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}

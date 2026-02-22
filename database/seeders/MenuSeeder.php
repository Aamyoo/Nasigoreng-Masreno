<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['nama_menu' => 'Nasi Goreng Biasa', 'harga' => 15000, 'kategori' => 'Nasi Goreng', 'stok' => 50, 'gambar' => 'nasigoreng_biasa.jpg', 'is_active' => 1],
            ['nama_menu' => 'Nasi Goreng Spesial', 'harga' => 20000, 'kategori' => 'Nasi Goreng', 'stok' => 30, 'gambar' => 'nasigoreng_spesial.jpg', 'is_active' => 1],
            ['nama_menu' => 'Nasi Goreng Seafood', 'harga' => 25000, 'kategori' => 'Nasi Goreng', 'stok' => 25, 'gambar' => 'nasigoreng_seafood.jpg', 'is_active' => 1],
            ['nama_menu' => 'Nasi Goreng Ayam', 'harga' => 18000, 'kategori' => 'Nasi Goreng', 'stok' => 35, 'gambar' => 'nasigoreng_ayam.jpg', 'is_active' => 1],
            ['nama_menu' => 'Nasi Goreng Kambing', 'harga' => 28000, 'kategori' => 'Nasi Goreng', 'stok' => 20, 'gambar' => 'nasigoreng_kambing.jpg', 'is_active' => 1],
            ['nama_menu' => 'Mie Goreng', 'harga' => 15000, 'kategori' => 'Mie', 'stok' => 30, 'gambar' => 'mie_goreng.jpg', 'is_active' => 1],
            ['nama_menu' => 'Mie Rebus', 'harga' => 13000, 'kategori' => 'Mie', 'stok' => 25, 'gambar' => 'mie_rebus.jpg', 'is_active' => 1],
            ['nama_menu' => 'Ayam Goreng', 'harga' => 17000, 'kategori' => 'Ayam', 'stok' => 20, 'gambar' => 'ayam_goreng.jpg', 'is_active' => 1],
            ['nama_menu' => 'Es Teh Manis', 'harga' => 5000, 'kategori' => 'Minuman', 'stok' => 100, 'gambar' => 'es_teh.jpg', 'is_active' => 1],
            ['nama_menu' => 'Es Jeruk', 'harga' => 7000, 'kategori' => 'Minuman', 'stok' => 80, 'gambar' => 'es_jeruk.jpg', 'is_active' => 1],
            ['nama_menu' => 'Teh Hangat', 'harga' => 4000, 'kategori' => 'Minuman', 'stok' => 100, 'gambar' => 'teh_hangat.jpg', 'is_active' => 1],
            ['nama_menu' => 'Jeruk Hangat', 'harga' => 6000, 'kategori' => 'Minuman', 'stok' => 80, 'gambar' => 'jeruk_hangat.jpg', 'is_active' => 1],
            ['nama_menu' => 'Kerupuk', 'harga' => 3000, 'kategori' => 'Snack', 'stok' => 50, 'gambar' => 'kerupuk.jpg', 'is_active' => 1],
            ['nama_menu' => 'Telur Dadar', 'harga' => 5000, 'kategori' => 'Lauk', 'stok' => 30, 'gambar' => 'telur_dadar.jpg', 'is_active' => 1],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(['nama_menu' => $menu['nama_menu']], $menu);
        }
    }
}

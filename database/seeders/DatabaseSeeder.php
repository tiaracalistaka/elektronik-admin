<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin
        User::updateOrCreate(
            ['email' => 'admin@elektronik.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Kategori
        $kategoris = [
            'Iphone' => Kategori::updateOrCreate(['nama' => 'Iphone'], ['nama' => 'Iphone']),
            'Samsung' => Kategori::updateOrCreate(['nama' => 'Samsung'], ['nama' => 'Samsung']),
            'Xiaomi' => Kategori::updateOrCreate(['nama' => 'Xiaomi'], ['nama' => 'Xiaomi']),
            'Android' => Kategori::updateOrCreate(['nama' => 'Android'], ['nama' => 'Android']),
            'Aksesoris' => Kategori::updateOrCreate(['nama' => 'Aksesoris'], ['nama' => 'Aksesoris']),
        ];

        // Produk
        $produks = [
            [
                'nama' => 'Iphone 13 Pro',
                'kategori' => 'Iphone',
                'harga' => 12000000,
                'thumbnail' => 'https://images.unsplash.com/photo-1632661674596-df8be59a8a34?w=400',
                'link_gambar' => 'https://images.unsplash.com/photo-1632661674596-df8be59a8a34',
                'stok' => 10,
            ],
            [
                'nama' => 'Samsung Z Flip',
                'kategori' => 'Samsung',
                'harga' => 20000000,
                'thumbnail' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=400',
                'link_gambar' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c',
                'stok' => 8,
            ],
            [
                'nama' => 'Xiaomi Redmi Note 11 Pro',
                'kategori' => 'Xiaomi',
                'harga' => 3200000,
                'thumbnail' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=400',
                'link_gambar' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97',
                'stok' => 15,
            ],
        ];

        foreach ($produks as $p) {
            Produk::updateOrCreate(
                ['nama' => $p['nama']],
                [
                    'kategori_id' => $kategoris[$p['kategori']]->id,
                    'harga' => $p['harga'],
                    'thumbnail' => $p['thumbnail'],
                    'link_gambar' => $p['link_gambar'],
                    'stok' => $p['stok'],
                    'deskripsi' => 'Produk original dengan garansi resmi',
                ]
            );
        }

        // Penjualan sample
        $allProduks = Produk::all();
        $customers = ['Budi Santoso', 'Siti Rahayu', 'Ahmad Fauzi', 'Dewi Lestari', 'Rina Wijaya', 'Hendra Kusuma', 'Maya Putri', 'Fajar Nugroho', 'Anisa Fitri', 'Tono Susilo'];
        $statuses = ['completed', 'completed', 'completed', 'completed', 'processing', 'pending', 'completed', 'completed', 'pending', 'completed'];

        foreach ($customers as $i => $customer) {
            $produk = $allProduks[$i % $allProduks->count()];
            $jumlah = rand(1, 3);
            Penjualan::create([
                'produk_id' => $produk->id,
                'jumlah' => $jumlah,
                'total_harga' => $produk->harga * $jumlah,
                'customer_name' => $customer,
                'status' => $statuses[$i],
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}

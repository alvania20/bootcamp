<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'nama' => 'MacBook Pro M2',
                'harga' => 21999000,
                'stok' => 5,
                'kategori' => 'Laptop',
                'deskripsi' => 'Laptop Apple MacBook Pro dengan chip M2 yang sangat bertenaga untuk kebutuhan komputasi harian, rendering video, dan programming dengan efisiensi daya super hemat.',
                'gambar' => 'macbook-pro-m2.jpg'
            ],
            [
                'nama' => 'Asus ROG Zephyrus Duo',
                'harga' => 45999000,
                'stok' => 3,
                'kategori' => 'Laptop',
                'deskripsi' => 'Laptop gaming layar ganda terbaik dari Asus ROG. Dilengkapi performa grafis ekstrem dan teknologi pendinginan aktif untuk sesi gaming dan multitasking profesional tingkat lanjut.',
                'gambar' => 'asus-rog-zephyrus.jpg'
            ],
            [
                'nama' => 'Laptop Gaming Pro (TUF Gaming)',
                'harga' => 14499000,
                'stok' => 8,
                'kategori' => 'Laptop',
                'deskripsi' => 'Laptop gaming dengan standar durabilitas militer yang tangguh. Cocok bagi mahasiswa dan kreator konten pemula yang mendambakan kombinasi performa kencang dan ketahanan tinggi.',
                'gambar' => 'laptop-gaming-pro.jpg'
            ],
            [
                'nama' => 'iPhone 15 Pro',
                'harga' => 18999000,
                'stok' => 12,
                'kategori' => 'Smartphone',
                'deskripsi' => 'Smartphone premium terbaru dari Apple dengan rangka titanium yang kokoh dan ringan, ditenagai chip A17 Pro untuk kualitas kamera sinematik dan performa gaming mobile terbaik.',
                'gambar' => 'iphone-15-pro.jpg'
            ],
            [
                'nama' => 'Samsung Galaxy S24 Ultra',
                'harga' => 20499000,
                'stok' => 10,
                'kategori' => 'Smartphone',
                'deskripsi' => 'Unggulan terbaru dari Samsung dengan integrasi teknologi kecerdasan buatan Galaxy AI, kamera zoom optik 5x detail tinggi, layar anti-reflektif, dan S-Pen bawaan yang presisi.',
                'gambar' => 'samsung-galaxy-s24-ultra.jpg'
            ],
            [
                'nama' => 'iPad Air',
                'harga' => 10999000,
                'stok' => 7,
                'kategori' => 'Smartphone',
                'deskripsi' => 'Tablet serbaguna nan tipis dari Apple, ideal untuk menggambar, mencatat perkuliahan, hingga editing ringan menggunakan dukungan Apple Pencil generasi terbaru.',
                'gambar' => 'ipad-air.jpg'
            ],
            [
                'nama' => 'Apple Watch Series',
                'harga' => 6499000,
                'stok' => 15,
                'kategori' => 'Aksesoris',
                'deskripsi' => 'Jam tangan pintar pendamping aktivitas harian Anda. Dilengkapi pelacakan kebugaran, sensor detak jantung real-time, dan tampilan antarmuka yang sangat responsif.',
                'gambar' => 'apple-watch.jpg'
            ],
            [
                'nama' => 'Mechanical Keyboard RGB 65%',
                'harga' => 1250000,
                'stok' => 20,
                'kategori' => 'Aksesoris',
                'deskripsi' => 'Keyboard mekanikal berukuran ringkas 65% dengan skema warna oranye-putih-hitam yang estetis. Sangat nyaman untuk sesi mengetik intensif dan gaming berkat switch mekanik responsif.',
                'gambar' => 'mechanical-keyboard.jpg'
            ],
            [
                'nama' => 'Mouse Wireless Logitech MX Master',
                'harga' => 1499000,
                'stok' => 25,
                'kategori' => 'Aksesoris',
                'deskripsi' => 'Mouse nirkabel ergonomis premium yang dirancang khusus untuk meningkatkan produktivitas desainer, programmer, dan profesional kreatif. Dilengkapi scroll wheel elektromagnetik ultra-cepat.',
                'gambar' => 'mouse-wireless-logitech.jpg'
            ],
            [
                'nama' => 'Headphone Sony WH-1000XM5',
                'harga' => 4799000,
                'stok' => 6,
                'kategori' => 'Audio',
                'deskripsi' => 'Headphone nirkabel dengan teknologi peredam bising (Active Noise Cancelling) tercanggih di industri audio saat ini. Menghasilkan suara resolusi tinggi yang luar biasa jernih.',
                'gambar' => 'headphone-sony-wh1000xm5.jpg'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $laptop = Category::where('name', 'Laptop')->first();
        $phone = Category::where('name', 'Smartphone')->first();
        $accessory = Category::where('name', 'Aksesoris')->first();
        $audio = Category::where('name', 'Audio')->first();

        // 2. Tambahkan pengecekan agar tidak error jika kategori tidak ditemukan
        $laptopCategoryId    = $laptop ? $laptop->id : null;
        $phoneCategoryId     = $phone ? $phone->id : null;
        $accessoryCategoryId = $accessory ? $accessory->id : null;
        $audioCategoryId     = $audio ? $audio->id : null;

        $products = [
            [
                'name' => 'MacBook Pro M2',
                'slug' => 'macbook-pro-m2',
                'price' => 21999000,
                'stock' => 5,
                'category_id' => $laptopCategoryId,
                'description' => 'Laptop Apple MacBook Pro dengan chip M2 yang sangat bertenaga untuk kebutuhan komputasi harian, rendering video, dan programming dengan efisiensi daya super hemat.',
                'image' => 'macbook-pro-m2.jpg'
            ],
            [
                'name' => 'Asus ROG Zephyrus Duo',
                'slug' => 'asus-rog-zephyrus-duo',
                'price' => 45999000,
                'stock' => 3,
                'category_id' => $laptopCategoryId,
                'description' => 'Laptop gaming layar ganda terbaik dari Asus ROG. Dilengkapi performa grafis ekstrem dan teknologi pendinginan aktif untuk sesi gaming dan multitasking profesional tingkat lanjut.',
                'image' => 'asus-rog-zephyrus.jpg'
            ],
            [
                'name' => 'Laptop Gaming Pro (TUF Gaming)',
                'slug' => 'laptop-gaming-pro-tuf-gaming',
                'price' => 14499000,
                'stock' => 8,
                'category_id' => $laptopCategoryId,
                'description' => 'Laptop gaming dengan standar durabilitas militer yang tangguh. Cocok bagi mahasiswa dan kreator konten pemula yang mendambakan kombinasi performa kencang dan ketahanan tinggi.',
                'image' => 'laptop-gaming-pro.jpg'
            ],
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'price' => 18999000,
                'stock' => 12,
                'category_id' => $phoneCategoryId,
                'description' => 'Smartphone premium terbaru dari Apple dengan rangka titanium yang kokoh dan ringan, ditenagai chip A17 Pro untuk kualitas kamera sinematik dan performa gaming mobile terbaik.',
                'image' => 'iphone-15-pro.jpg'
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'price' => 20499000,
                'stock' => 10,
                'category_id' => $phoneCategoryId,
                'description' => 'Unggulan terbaru dari Samsung dengan integrasi teknologi kecerdasan buatan Galaxy AI, kamera zoom optik 5x detail tinggi, layar anti-reflektif, dan S-Pen bawaan yang presisi.',
                'image' => 'samsung-galaxy-s24-ultra.jpg'
            ],
            [
                'name' => 'iPad Air',
                'slug' => 'ipad-air',
                'price' => 10999000,
                'stock' => 7,
                'category_id' => $phoneCategoryId,
                'description' => 'Tablet serbaguna nan tipis dari Apple, ideal untuk menggambar, mencatat perkuliahan, hingga editing ringan menggunakan dukungan Apple Pencil generasi terbaru.',
                'image' => 'ipad-air.jpg'
            ],
            [
                'name' => 'Apple Watch Series',
                'slug' => 'apple-watch-series',
                'price' => 6499000,
                'stock' => 15,
                'category_id' => $accessoryCategoryId,
                'description' => 'Jam tangan pintar pendamping aktivitas harian Anda. Dilengkapi pelacakan kebugaran, sensor detak jantung real-time, dan tampilan antarmuka yang sangat responsif.',
                'image' => 'apple-watch.jpg'
            ],
            [
                'name' => 'Mechanical Keyboard RGB 65%',
                'slug' => 'mechanical-keyboard-rgb-65',
                'price' => 1250000,
                'stock' => 20,
                'category_id' => $accessoryCategoryId,
                'description' => 'Keyboard mekanikal berukuran ringkas 65% dengan skema warna oranye-putih-hitam yang estetis. Sangat nyaman untuk sesi mengetik intensif dan gaming berkat switch mekanik responsif.',
                'image' => 'mechanical-keyboard.jpg'
            ],
            [
                'name' => 'Mouse Wireless Logitech MX Master',
                'slug' => 'mouse-wireless-logitech-mx-master',
                'price' => 1499000,
                'stock' => 25,
                'category_id' => $accessoryCategoryId,
                'description' => 'Mouse nirkabel ergonomis premium yang dirancang khusus untuk meningkatkan produktivitas desainer, programmer, dan profesional kreatif. Dilengkapi scroll wheel elektromagnetik ultra-cepat.',
                'image' => 'mouse-wireless-logitech.jpg'
            ],
            [
                'name' => 'Headphone Sony WH-1000XM5',
                'slug' => 'headphone-sony-wh1000xm5',
                'price' => 4799000,
                'stock' => 6,
                'category_id' => $audioCategoryId,
                'description' => 'Headphone nirkabel dengan teknologi peredam bising (Active Noise Cancelling) tercanggih di industri audio saat ini. Menghasilkan suara resolusi tinggi yang luar biasa jernih.',
                'image' => 'headphone-sony-wh1000xm5.jpg'
            ],
             [
                'name' => 'Mouse Wireless',
                'slug' => 'mouse-wireless',
                'price' => 350000,
                'stock' => 18,
                'category_id' => $accessoryCategoryId,
                'description' => 'Mouse nirkabel ergonomis premium yang dirancang khusus untuk meningkatkan produktivitas desainer, programmer, dan profesional kreatif. Dilengkapi scroll wheel elektromagnetik ultra-cepat.',
                'image' => 'mouse-wireless.jpg'
            ]
           
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(
        ['slug' => $data['slug']], $data
        );}
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder dalam urutan yang benar
        $this->call([
            UserSeeder::class,     // 1. Buat User dulu
            CategorySeeder::class, // 2. Buat Kategori
            ProductSeeder::class,  // 3. Buat Produk
        ]);
    }
}
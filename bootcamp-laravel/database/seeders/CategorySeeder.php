<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // KOREKSI: Pastikan nama tabel di sini adalah 'categories'
        // agar sesuai dengan file migrasi database Anda.
        DB::table('categories')->insert([
            ['name' => 'Laptop', 'slug' => 'laptop'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris'],
            ['name' => 'Smartphone', 'slug' => 'smartphone'],
            ['name' => 'Audio', 'slug' => 'audio'],
        ]);
    }
}
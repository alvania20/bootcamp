<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Laptop', 'slug' => 'laptop'],
            ['id' => 2, 'name' => 'Aksesoris', 'slug' => 'aksesoris'],
            ['id' => 3, 'name' => 'Smartphone', 'slug' => 'smartphone'],
            ['id' => 4, 'name' => 'Audio', 'slug' => 'audio']
            
        ]);
    }
}
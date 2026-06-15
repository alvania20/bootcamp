<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Menggunakan constrained('categories') sudah benar
            // Menambahkan index pada foreign key untuk performa query JOIN/Filter
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('cascade'); // Produk otomatis terhapus jika kategori dihapus
            
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->text('description')->nullable();
            
            // Menggunakan unsignedDecimal agar harga tidak bisa negatif (proteksi data)
            $table->unsignedDecimal('price', 12, 2); 
            
            // Menggunakan unsignedInteger untuk stok agar tidak bisa negatif
            $table->unsignedInteger('stock')->default(0);
            
            // Image dibiarkan nullable agar bisa dibuat opsional seperti permintaan Anda
            $table->string('image')->nullable();
            
            // Menambahkan kolom views untuk fitur statistik (sesuai controller show)
            $table->unsignedInteger('views')->default(0);
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
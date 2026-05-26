<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Jika order dihapus, item di dalamnya ikut terhapus
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained(); // Menghubungkan ke produk
            $table->integer('quantity');
            $table->decimal('price', 12, 2); // Harga produk saat transaksi (mencegah manipulasi jika harga produk utama berubah)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

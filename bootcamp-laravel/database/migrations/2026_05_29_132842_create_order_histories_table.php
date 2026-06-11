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
        // Pastikan tabel tidak ada sebelumnya untuk menghindari error saat migrasi ulang
        if (!Schema::hasTable('order_histories')) {
            Schema::create('order_histories', function (Blueprint $table) {
                $table->id();
                
                // Menghubungkan log ke pesanan tertentu
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                
                // Menghubungkan log ke user (admin/staf) yang mengubah status
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                
                $table->string('status'); 
                $table->text('notes')->nullable(); 
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_histories');
    }
};
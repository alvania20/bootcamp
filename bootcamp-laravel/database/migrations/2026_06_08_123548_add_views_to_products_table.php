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
        Schema::table('products', function (Blueprint $table) {
            // Menggunakan unsignedInteger karena jumlah view tidak mungkin negatif.
            // Ini adalah proteksi tambahan pada tingkat database.
            $table->unsignedInteger('views')->default(0)->after('description'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('views');
        });
    }
};
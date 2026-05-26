<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method'); // Misal: 'Bank Transfer', 'E-Wallet', 'Midtrans'
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('payment_receipt')->nullable(); // Path gambar bukti transfer jika manual
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
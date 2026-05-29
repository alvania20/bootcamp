<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // Mengubah tabel menjadi 'carts' agar sesuai dengan struktur database Anda
    protected $table = 'carts';

    protected $fillable = ['user_id', 'product_id', 'quantity'];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Relasi ke Product
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
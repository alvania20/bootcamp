<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'status',
        'shipping_address',
        'tracking_number'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products() 
    {
    return $this->belongsToMany(Product::class)->withPivot('quantity', 'price');
    }

    // Relasi ke Order Items (Detail Barang)
    public function orderItems()
    {
        // Menggunakan path lengkap class string untuk menghindari error Intelephense
        return $this->hasMany(\App\Models\OrderItem::class);
    }

    // Relasi ke Pembayaran (One-to-One)
    public function payment()
    {
        // Menggunakan path lengkap class string untuk menghindari error Intelephense
        return $this->hasOne(\App\Models\Payment::class);
    }
}
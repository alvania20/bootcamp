<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    // Mengizinkan field ini untuk diisi melalui mass assignment
    protected $fillable = [
        'order_id', 
        'user_id', 
        'status', 
        'notes'
    ];

    // Relasi ke Order (Setiap history milik satu pesanan)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke User (Admin/User yang melakukan perubahan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
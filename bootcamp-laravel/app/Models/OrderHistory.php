<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderHistory extends Model
{
    /**
     * Tentukan nama tabel jika tidak mengikuti konvensi jamak standar.
     * (Opsional jika nama tabel di database sudah 'order_histories')
     */
    protected $table = 'order_histories';

    /**
     * Mengizinkan field ini untuk diisi melalui mass assignment.
     */
    protected $fillable = [
        'order_id', 
        'user_id', 
        'status', 
        'notes'
    ];

    /**
     * Relasi ke Order (Setiap history milik satu pesanan).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke User (Admin/User yang melakukan perubahan).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * Secara default Laravel sudah mendeteksi 'carts', 
     * jadi ini opsional namun bagus untuk kejelasan.
     */
    protected $table = 'carts';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'user_id', 
        'product_id', 
        'quantity'
    ];

    /**
     * Relasi ke model User.
     * Gunakan return type hint untuk kejelasan kode.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
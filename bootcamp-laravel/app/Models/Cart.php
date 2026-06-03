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
     * Opsional karena Laravel secara otomatis menebak 'carts'.
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
     * Cast atribut ke tipe data asli untuk keamanan data.
     * Ini memastikan 'quantity' selalu dianggap integer.
     */
    protected function casts(): array
    {
        return [
            'user_id'    => 'integer',
            'product_id' => 'integer',
            'quantity'   => 'integer',
        ];
    }

    /**
     * Relasi ke model User.
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
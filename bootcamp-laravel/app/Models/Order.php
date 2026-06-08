<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'status',
        'shipping_address',
        'tracking_number'
    ];

    /**
     * Cast atribut ke tipe data asli.
     */
    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
        ];
    }

    /**
     * Relasi ke User (Pembeli).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Many-to-Many ke Produk.
     */
    public function products(): BelongsToMany 
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    /**
     * Relasi ke Order Items (Detail Barang).
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke Order Histories (Riwayat Perubahan Status).
     * DITAMBAHKAN UNTUK MEMPERBAIKI ERROR
     */
    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class, 'order_id', 'id');
    }

    /**
     * Relasi ke Pembayaran (One-to-One).
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
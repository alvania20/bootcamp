<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atribut yang dapat diisi secara massal.
     * Menambahkan 'clicks' agar bisa diupdate melalui Controller.
     */
    protected $fillable = [
        'name', 
        'slug', 
        'price', 
        'stock', 
        'category_id', 
        'description', 
        'image',
        'views',
        'clicks'
    ];

    /**
     * Casting atribut ke tipe data asli.
     */
    protected function casts(): array
    {
        return [
            'price'       => 'integer',
            'stock'       => 'integer',
            'category_id' => 'integer',
            'views'       => 'integer',
            'clicks'      => 'integer', // Tambahkan casting untuk clicks
            'deleted_at'  => 'datetime',
        ];
    }

    /**
     * Accessor untuk memformat harga.
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->price, 0, ',', '.')
        );
    }

    /**
     * Relasi ke Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Order
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}
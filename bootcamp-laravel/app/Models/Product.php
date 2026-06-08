<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'name', 
        'slug', 
        'price', 
        'stock', 
        'category_id', 
        'description', 
        'image',
        'views' // Menambahkan views agar bisa diupdate melalui sistem
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
            'views'       => 'integer', // Pastikan views bertipe integer
        ];
    }

    /**
     * Booted method untuk otomatisasi slug.
     */
    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            // Buat slug jika kosong atau jika nama produk diubah
            if (empty($product->slug) || $product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Relasi ke Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Order (Many-to-Many)
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}
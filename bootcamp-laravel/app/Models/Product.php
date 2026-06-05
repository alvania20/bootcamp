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
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 
        'slug', 
        'price', 
        'stock', 
        'category_id', 
        'description', 
        'image'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'price'       => 'integer',
        'stock'       => 'integer',
        'category_id' => 'integer',
    ];

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
     * Relasi ke Order
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
    }
}
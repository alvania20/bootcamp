<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Boot method untuk otomatisasi slug.
     * Menggunakan event 'saving' mencakup 'creating' dan 'updating'.
     */
    protected static function booted(): void
    {
        static::saving(function ($category) {
            // Hanya buat ulang slug jika nama berubah
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relasi ke Produk.
     * Menggunakan return type hint untuk standar Laravel modern.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
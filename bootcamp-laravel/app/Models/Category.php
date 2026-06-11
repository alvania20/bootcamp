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
     * Atribut yang dapat diisi secara massal (mass-assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'slug'];

    /**
     * "Booting" model untuk logika otomatisasi.
     */
    protected static function booted(): void
    {
        // Otomatisasi pembuatan slug sebelum data disimpan/diupdate
        static::saving(function (Category $category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Mendapatkan semua produk yang terkait dengan kategori ini.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
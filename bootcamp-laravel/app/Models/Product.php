<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel (opsional, jika tabel Anda bernama 'products')
     */
    protected $table = 'products';

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
     * Casting atribut untuk tipe data yang konsisten.
     */
    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'category_id' => 'integer',
    ];

    /**
     * Boot method untuk menangani event model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Memastikan slug terisi jika belum ada
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            // Memastikan slug diperbarui jika nama produk berubah
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Relasi ke Category: Satu produk milik satu kategori.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Order: Satu produk bisa ada di banyak order.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
    }
}
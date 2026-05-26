<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes - GadgetShop
|--------------------------------------------------------------------------
| Berikut adalah konfigurasi rute aplikasi Anda.
*/

// 1. Halaman Publik (Bisa diakses siapa saja)
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('page.about');

// 2. Rute Produk (Publik & Manajemen - Dibuat publik untuk sementara agar mudah didevelop)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

// 3. Rute Keranjang & Checkout (Publik untuk testing)
// Karena Anda belum menginstal Breeze, rute ini diletakkan di luar middleware 'auth'
// agar tidak error saat diakses.
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/store/{product}', [CartController::class, 'store'])->name('carts.store');
Route::put('/cart/{id}', [CartController::class, 'update'])->name('carts.update');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('carts.destroy');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// 4. Placeholder Login
Route::get('/login', function () {
    return 'Halaman Login belum dibuat. Silakan instal Laravel Breeze atau sistem Auth.';
})->name('login');

/*
|--------------------------------------------------------------------------
| Rute Terlindungi (Middleware)
|--------------------------------------------------------------------------
| Nanti, jika sistem Login sudah siap, pindahkan rute di atas ke dalam group ini.
*/
Route::middleware(['auth'])->group(function () {
    // Rute yang membutuhkan login akan diletakkan di sini di masa depan
});
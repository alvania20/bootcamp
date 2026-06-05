<?php

use App\Http\Controllers\{
    CartController,
    CategoryController,
    CheckoutController,
    PageController,
    ProductController,
    ProfileController
};
use Illuminate\Support\Facades\Route;

// --- Public Routes ---
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('page.about');
Route::get('/katalog', [ProductController::class, 'katalog'])->name('products.katalog');

// --- Protected Routes (Perlu Login) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Manajemen Produk
    // Route::resource otomatis membuat route untuk index,
    // kita cukup pastikan index dipanggil di sini agar urutannya bersih
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::resource('products', ProductController::class)->except(['index']);

    // Keranjang
    Route::resource('cart', CartController::class)->except(['create', 'show', 'edit']);
    
    // Checkout
    Route::prefix('checkout')->controller(CheckoutController::class)->group(function () {
        Route::get('/', 'index')->name('checkout.index');
        Route::post('/', 'store')->name('checkout.store');
    });

    // Kategori
    Route::resource('categories', CategoryController::class);

    // Profile
    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('profile.edit');
        Route::patch('/', 'update')->name('profile.update');
        Route::delete('/', 'destroy')->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
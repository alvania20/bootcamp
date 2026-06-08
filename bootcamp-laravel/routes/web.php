<?php

use App\Http\Controllers\{
    CartController,
    CategoryController,
    CheckoutController,
    DashboardController,
    OrderController, // Pastikan OrderController sudah dibuat
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
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Produk
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::resource('products', ProductController::class)->except(['index']);

    // Keranjang
    Route::resource('cart', CartController::class)->except(['create', 'show', 'edit']);
    
    // Checkout
    Route::prefix('checkout')->controller(CheckoutController::class)->group(function () {
        Route::get('/', 'index')->name('checkout.index');
        Route::post('/', 'store')->name('checkout.store');
    });

    // --- Order / Transaksi (Baru) ---
    // Route untuk melihat daftar transaksi dan detail spesifik per transaksi
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

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
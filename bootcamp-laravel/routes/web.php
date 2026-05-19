<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

// 1. Halaman Utama (/)
Route::get('/', function () {
    return 'Ini adalah Halaman Utama Ecommerce';
});

// 2. Halaman Produk (/products)
Route::get('/products', function () {
    return 'Ini adalah Halaman Daftar Produk';
});

// 3. Halaman Keranjang Belanja (/cart)
Route::get('/cart', function () {
    return 'Ini adalah Halaman Keranjang Belanja Anda';
});

// 4. Halaman Checkout (/checkout)
Route::get('/checkout', function () {
    return 'Ini adalah Halaman Proses Checkout Pembayaran';
});
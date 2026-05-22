<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CheckoutController;

Route::get('/', [PageController::class, 'home'])->name('page.home');
Route::get('/about', [PageController::class, 'about'])->name('page.about');
Route::resource('products', ProductController::class);
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/products/{id}/add-to-cart', [ProductController::class, 'addToCart'])->name('products.addToCart');Route::post('/products/{id}/add-to-cart', [App\Http\Controllers\ProductController::class, 'addToCart'])->name('products.addToCart');
Route::patch('/checkout/update/{id}', [CheckoutController::class, 'update'])->name('checkout.update');
Route::delete('/checkout/remove/{id}', [CheckoutController::class, 'remove'])->name('checkout.remove');
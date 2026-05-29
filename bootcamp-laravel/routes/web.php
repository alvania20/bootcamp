<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes - GadgetShop
|--------------------------------------------------------------------------
*/

// 1. Halaman Publik
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('page.about');

// 2. Rute Produk
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store'); // Menambahkan rute store
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

// 3. Rute Keranjang & Checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/store/{product}', [CartController::class, 'store'])->name('carts.store');
Route::put('/cart/{id}', [CartController::class, 'update'])->name('carts.update');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('carts.destroy');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::put('/checkout/{id}', [CheckoutController::class, 'update'])->name('checkout.update');
Route::delete('/checkout/remove/{id}', [CheckoutController::class, 'remove'])->name('checkout.remove');

// 4. Rute Order
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

// 5. Sistem Login Sederhana
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors(['email' => 'Email atau password salah.']);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Rute Terlindungi (Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Pindahkan rute yang butuh login ke sini jika sudah siap
});
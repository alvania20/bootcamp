<?php

use App\Http\Controllers\{
    CartController, CategoryController, CheckoutController,
    DashboardController, OrderController, PageController,
    ProductController, ProfileController
};
use Illuminate\Support\Facades\{Route, Auth, Artisan};
use App\Models\User;

// --- 1. Public Routes ---
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('page.about');
Route::get('/katalog', [ProductController::class, 'katalog'])->name('products.katalog');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// --- 2. Authenticated Routes (User & Admin) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('cart', CartController::class)->except(['create', 'show', 'edit']);
    
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
    });

    // Orders (User & Admin View)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // --- 3. Admin Routes (Dilindungi Middleware Admin) ---
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        
        // Diperjelas namanya untuk menghindari konflik
        Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});

// --- 4. Development Tools ---
if (app()->environment('local')) {
    Route::get('/dev-login/{id}', function ($id) {
        Auth::login(User::findOrFail($id));
        return redirect()->route('dashboard');
    });

    Route::get('/reset-semua-sesi', function () {
        Artisan::call('optimize:clear');
        array_map('unlink', glob(storage_path('framework/sessions/*')));
        return "Cache dan sesi dibersihkan.";
    });
}

require __DIR__.'/auth.php';
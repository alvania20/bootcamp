<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Tempat untuk mendaftarkan binding service ke container.
        // Jangan taruh logika Auth di sini.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Baris kode yang memaksa login otomatis (Auth::loginUsingId(1)) 
        // TELAH DIHAPUS agar sistem autentikasi Laravel berjalan normal.
    }
}
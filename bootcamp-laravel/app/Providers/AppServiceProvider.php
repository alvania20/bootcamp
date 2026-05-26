<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth; // Jangan lupa import ini

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pindahkan logika login dummy ke dalam sini
        if (!Auth::check()) {
            Auth::loginUsingId(1);
        }
    }
}
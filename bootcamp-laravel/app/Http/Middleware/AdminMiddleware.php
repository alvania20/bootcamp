<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Gunakan operator null-safe (?->) untuk mengecek isAdmin()
        // Ini mencegah error jika terjadi sesuatu yang tidak terduga pada objek user
        if (!Auth::user()?->isAdmin()) {
            
            // Jika request berupa API/JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Akses ditolak.',
                ], 403);
            }

            // Jika sudah login tapi bukan admin, beri error 403
            abort(403, 'Unauthorized - Hanya admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
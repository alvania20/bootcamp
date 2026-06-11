<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi user.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Validasi kredensial (LoginRequest menangani ini)
        $request->authenticate();

        // 2. Regenerasi session untuk mencegah session fixation attack
        $request->session()->regenerate();

        // 3. Redirect ke dashboard atau ke URL yang sebelumnya ingin diakses user
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Hapus sesi user (Logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman utama setelah logout
        return redirect('/');
    }
}
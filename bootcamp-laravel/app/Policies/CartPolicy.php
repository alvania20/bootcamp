<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;

class CartPolicy
{
    // Fungsi ini mengecek apakah user boleh update atau hapus data
    public function manage(User $user, Cart $cart)
    {
        // Admin boleh melakukan apa saja, atau jika user adalah pemilik keranjang
        return $user->role === 'admin' || $user->id === $cart->user_id;
    }
}
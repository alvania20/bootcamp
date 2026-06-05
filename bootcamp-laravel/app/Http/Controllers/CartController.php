<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CartController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan daftar item di keranjang.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Admin melihat semua, User hanya melihat milik sendiri
        $query = Cart::with('product');
        
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        } else {
            $query->with('user'); // Admin butuh data user
        }
        
        $cartItems = $query->get();
        
        // Perhitungan total harga dengan filter agar tidak error jika produk null
        $totalPrice = $cartItems->sum(function ($item) {
            return ($item->product->price ?? 0) * $item->quantity;
        });
        
        return view('cart.index', compact('cartItems', 'totalPrice'));
    }

    /**
     * Menyimpan produk ke keranjang.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $quantity = $request->quantity ?? 1;

        $cartItem = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ],
            [
                'quantity' => \Illuminate\Support\Facades\DB::raw("quantity + $quantity")
            ]
        );

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambah ke keranjang!');
    }

    /**
     * Mengupdate jumlah item di keranjang.
     */
    public function update(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);
        
        $this->authorize('manage', $cartItem);
        
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $cartItem->update(['quantity' => $request->quantity]);
        
        return redirect()->route('cart.index')->with('success', 'Jumlah produk diperbarui.');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);
        
        $this->authorize('manage', $cartItem);
        
        $cartItem->delete();
        
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}
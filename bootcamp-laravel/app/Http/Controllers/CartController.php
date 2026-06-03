<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. Tambahkan import ini

class CartController extends Controller
{
    use AuthorizesRequests; // 2. Gunakan trait agar method authorize() bisa diakses

    /**
     * Menampilkan daftar item di keranjang.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Admin melihat semua, User hanya melihat milik sendiri
        if ($user->isAdmin()) {
            $cartItems = Cart::with(['product', 'user'])->get();
        } else {
            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
        }
        
        $totalPrice = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);
        
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

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();
        
        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => Auth::id(), 
                'product_id' => $productId, 
                'quantity' => $quantity
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambah ke keranjang!');
    }

    /**
     * Mengupdate jumlah item di keranjang.
     */
    public function update(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);
        
        // Sekarang authorize() akan berjalan tanpa error
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
        
        // Sekarang authorize() akan berjalan tanpa error
        $this->authorize('manage', $cartItem);
        
        $cartItem->delete();
        
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}
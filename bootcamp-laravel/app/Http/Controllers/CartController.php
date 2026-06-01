<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang pengguna.
     */
    public function index()
    {
        // Gunakan middleware 'auth' di route agar tidak perlu pengecekan manual berulang-ulang
        $userId = Auth::id();

        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        $totalPrice = $cartItems->sum(function ($item) {
            return ($item->product->price ?? 0) * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'totalPrice'));
    }

    /**
     * Menambah produk ke keranjang.
     */
    public function store(Request $request, $productId)
    {
        
        $request->validate([
            'quantity' => 'nullable|integer|min:1'
        ]);

        $quantity = $request->quantity ?? 1;
        $product = Product::findOrFail($productId);
        $userId = Auth::id();

        // Cek stok produk
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi!');
        }

        // Cari apakah produk sudah ada di keranjang user
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Jumlah total melebihi stok yang tersedia!');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Memperbarui jumlah item di keranjang.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        
        // Pastikan stok mencukupi untuk update jumlah baru
        if ($cartItem->product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk jumlah tersebut.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function destroy($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Barang berhasil dihapus dari keranjang.');
    }
}
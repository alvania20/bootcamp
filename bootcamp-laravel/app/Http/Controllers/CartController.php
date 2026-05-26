<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Pastikan user sudah login untuk melihat keranjang
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat keranjang.');
        }

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'totalPrice'));
    }

    public function store(Request $request, $productId)
    {
        // 1. WAJIB LOGIN untuk menggunakan keranjang
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk menambah produk ke keranjang.');
        }

        $userId = Auth::id();

        $request->validate([
            'quantity' => 'nullable|integer|min:1'
        ]);

        $quantity = $request->quantity ?? 1;
        $product = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi!');
        }

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Jumlah total melebihi stok!');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // ... (Fungsi update, destroy, dll tetap sama)
}
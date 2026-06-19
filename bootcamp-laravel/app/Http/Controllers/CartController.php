<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Admin sebaiknya tidak melihat keranjang belanja (kosongkan atau redirect)
        // Jika Admin tetap ingin melihat, biarkan seperti ini, jika tidak, tambahkan pengecekan.
        $query = Cart::with('product')->where('user_id', $user->id);
        
        $cartItems = $query->get();
        $totalPrice = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);
        
        return view('cart.index', compact('cartItems', 'totalPrice'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Admin tidak perlu memiliki keranjang belanja
        if (Auth::user()->isAdmin()) {
            return back()->with('error', 'Admin tidak dapat melakukan pembelian.');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        // Validasi stok produk
        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        DB::transaction(function () use ($validated) {
            $cart = Cart::where('user_id', Auth::id())
                        ->where('product_id', $validated['product_id'])
                        ->first();

            if ($cart) {
                $cart->increment('quantity', $validated['quantity']);
            } else {
                Cart::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $validated['product_id'],
                    'quantity'   => $validated['quantity']
                ]);
            }
        });

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, Cart $cart): RedirectResponse
    {
        $this->authorizeAccess($cart);
        
        $validated = $request->validate(['quantity' => 'required|integer|min:1']);
        
        // Cek stok sebelum update
        if ($cart->product->stock < $validated['quantity']) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }
        
        $cart->update(['quantity' => $validated['quantity']]);
        
        return redirect()->route('cart.index')->with('success', 'Kuantitas diperbarui.');
    }

    public function destroy(Cart $cart): RedirectResponse
    {
        $this->authorizeAccess($cart);
        $cart->delete();
        
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    private function authorizeAccess(Cart $cart): void
    {
        // Pastikan User hanya bisa memodifikasi keranjangnya sendiri
        if ((int)$cart->user_id !== (int)Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk tindakan ini.');
        }
    }
}
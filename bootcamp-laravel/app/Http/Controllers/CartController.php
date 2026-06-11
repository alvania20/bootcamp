<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Eager loading produk untuk efisiensi query
        $query = Cart::with('product');
        
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }
        
        $cartItems = $query->get();
        
        $totalPrice = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);
        
        return view('cart.index', compact('cartItems', 'totalPrice'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $productId = $validated['product_id'];
        $addedQuantity = (int)$validated['quantity'];

        // Menggunakan logika transaksi untuk keamanan data
        \Illuminate\Support\Facades\DB::transaction(function () use ($productId, $addedQuantity) {
            $cart = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();

            if ($cart) {
                $cart->increment('quantity', $addedQuantity);
            } else {
                Cart::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $productId,
                    'quantity'   => $addedQuantity
                ]);
            }
        });

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Cart $cart): RedirectResponse
    {
        $this->authorizeAccess($cart);
        
        $validated = $request->validate(['quantity' => 'required|integer|min:1']);
        
        $cart->update(['quantity' => $validated['quantity']]);
        
        return redirect()->route('cart.index')->with('success', 'Kuantitas diperbarui.');
    }

    public function destroy(Cart $cart): RedirectResponse
    {
        $this->authorizeAccess($cart);
        
        $cart->delete();
        
        return redirect()->route('cart.index')->with('success', 'Produk dihapus.');
    }

    private function authorizeAccess(Cart $cart): void
    {
        $user = Auth::user();
        
        // Menggunakan Policy (atau logika if sederhana)
        if (!$user->isAdmin() && (int)$cart->user_id !== (int)$user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk tindakan ini.');
        }
    }
}
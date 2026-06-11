<?php

namespace App\Http\Controllers;

use App\Models\{Cart, Order, OrderItem, Payment, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        return view('checkout.index', compact('cartItems', 'totalPrice'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'shipping_address' => 'required|string|min:10',
            'payment_method'   => 'required|string'
        ]);

        $userId = Auth::id();
        $order = null;

        try {
            DB::transaction(function () use ($request, $userId, &$order) {
                $cartItems = Cart::with('product')->where('user_id', $userId)->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception("Keranjang Anda kosong.");
                }

                $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

                // 1. Buat Order
                $order = Order::create([
                    'user_id'          => $userId,
                    'order_number'     => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                    'total_price'      => $totalPrice,
                    'status'           => 'pending',
                    'shipping_address' => $request->shipping_address,
                ]);

                // 2. Proses Items & Stok
                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);

                    if ($product->stock < $item->quantity) {
                        throw new \Exception("Stok untuk '{$product->name}' tidak mencukupi.");
                    }

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'quantity'   => $item->quantity,
                        'price'      => $product->price
                    ]);

                    $product->decrement('stock', $item->quantity);
                }

                // 3. Catat Pembayaran
                Payment::create([
                    'order_id'       => $order->id,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                ]);

                // 4. Kosongkan Keranjang
                Cart::where('user_id', $userId)->delete();
            }, 5);

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
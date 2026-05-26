<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout
     */
    public function index()
    {
        // Mengambil data keranjang dari database berdasarkan user yang login
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong!');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'totalPrice'));
    }

    /**
     * Memproses transaksi (Checkout ke database)
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|min:10',
            'payment_method' => 'required|string'
        ]);

        $userId = Auth::id();
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        // Database Transaction untuk keamanan data
        DB::beginTransaction();

        try {
            $totalPrice = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Membuat nomor invoice unik
            $orderNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            // 1. Simpan ke tabel orders
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
            ]);

            // 2. Simpan setiap barang belanjaan ke tabel order_items & kurangi stok
            foreach ($cartItems as $item) {
                $product = Product::findOrFail($item->product_id);

                if ($product->stock < $item->quantity) {
                    throw new \Exception("Stok untuk produk '{$product->name}' tidak mencukupi.");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $product->price
                ]);

                // Kurangi stok produk secara otomatis
                $product->decrement('stock', $item->quantity);
            }

            // 3. Catat data pembayaran awal ke tabel payments
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            // 4. Kosongkan keranjang belanja di database
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
        }
    }
}
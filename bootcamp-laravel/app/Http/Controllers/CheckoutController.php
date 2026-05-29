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
    public function index()
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string|min:10',
        ]);

        DB::beginTransaction();
        try {
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
            $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

            $order = Order::create([
                'user_id'          => Auth::id(),
                'order_number'     => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'total_price'      => $totalPrice,
                'status'           => 'pending',
                'shipping_address' => $request->address,
                'name'             => $request->name,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            Cart::where('user_id', Auth::id())->delete();
            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
        }
    }
}
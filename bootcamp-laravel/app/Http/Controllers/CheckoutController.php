<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        // Ambil data dari session 'cart'
        $cartItems = session()->get('cart', []);
        
        // Hitung total harga
        $totalPrice = 0;
        foreach ($cartItems as $item) 
        {
            $totalPrice += ($item['harga'] * $item['quantity']);
        }
        
        return view('checkout.index', compact('cartItems', 'totalPrice'));
    }

    public function update(Request $request, $id) 
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) 
        {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        return redirect()->route('checkout.index');
    }

        public function remove($id) {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) 
        {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->route('checkout.index');
    }
}
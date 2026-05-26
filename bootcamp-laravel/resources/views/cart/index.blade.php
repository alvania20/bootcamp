@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-extrabold text-slate-900 mb-8">Keranjang Belanja Anda</h2>

    @if($cartItems->isEmpty())
        <div class="text-center py-20 bg-white rounded-2xl border shadow-sm">
            <p class="text-slate-500 text-lg">Keranjang Anda masih kosong.</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700">
                Belanja Sekarang
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $item)
                <div class="bg-white p-4 rounded-xl border flex items-center gap-4 shadow-sm">
                    <img src="{{ asset('img/' . $item->product->image) }}" class="w-20 h-20 object-cover rounded-lg">
                    <div class="flex-grow">
                        <h3 class="font-bold text-slate-900">{{ $item->product->name }}</h3>
                        <p class="text-sm text-indigo-600 font-bold">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('carts.update', $item->id) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 border rounded p-1 text-center">
                            <button type="submit" class="text-xs text-slate-500 underline">Update</button>
                        </form>
                        <form action="{{ route('carts.destroy', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 font-bold ml-4">Hapus</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-slate-50 p-6 rounded-2xl border h-fit">
                <h3 class="text-lg font-bold mb-4">Ringkasan Belanja</h3>
                <div class="flex justify-between mb-2">
                    <span class="text-slate-600">Total Harga</span>
                    <span class="font-bold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                <hr class="my-4">
                <a href="{{ route('checkout.index') }}" class="block w-full bg-indigo-600 text-white text-center py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
                    Lanjut Checkout
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
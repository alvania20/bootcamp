<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Checkout</h1>
            
            {{-- Menambahkan Error Handling --}}
            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-6 font-bold">
                    {{ session('error') }}
                </div>
            @endif

            @if(isset($cartItems) && $cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Bagian Kiri: Ringkasan Pesanan --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-bold mb-6 text-slate-800 border-b pb-4">Butiran Pesanan</h2>
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                        <div class="flex items-center gap-4">
                            <img src="{{ $item->product?->image && file_exists(public_path('img/'.$item->product->image)) ? asset('img/' . $item->product->image) : asset('img/default.png') }}" 
                                 alt="{{ $item->product->name ?? 'Produk' }}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-bold text-slate-900">{{ $item->product->name ?? 'Produk Dihapus' }}</h3>
                                <p class="text-sm text-slate-500">Qty: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-bold text-slate-900">
                                Rp {{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-lg font-bold">Total Pembayaran</span>
                        <span class="text-2xl font-black text-indigo-600">
                            Rp {{ number_format($totalPrice ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Bagian Kanan: Form Alamat & Pembayaran --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-bold mb-6 text-slate-800">Informasi Pengiriman</h2>
                    
                    {{-- Tambahkan x-data atau script sederhana untuk mencegah double click --}}
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                   class="w-full border-slate-200 rounded-xl p-3 mt-1 focus:ring-indigo-500" readonly>
                        </div>
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700">Alamat Lengkap</label>
                            <textarea name="shipping_address" class="w-full border-slate-200 rounded-xl p-3 mt-1 focus:ring-indigo-500" 
                                      rows="3" required>{{ old('shipping_address') }}</textarea>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700">Metode Pembayaran</label>
                            <select name="payment_method" class="w-full border-slate-200 rounded-xl p-3 mt-1 focus:ring-indigo-500" required>
                                <option value="transfer_bank">Transfer Bank</option>
                                <option value="cod">COD (Bayar di Tempat)</option>
                            </select>
                        </div>
                        
                        {{-- Button yang aman dari double click --}}
                        <button type="submit" 
                                class="w-full bg-indigo-600 text-white py-4 rounded-xl font-black hover:bg-indigo-700 transition active:scale-95 disabled:opacity-50"
                                onclick="this.disabled=true; this.innerText='Memproses...'; this.form.submit();">
                            Selesaikan Pembayaran
                        </button>
                    </form>
                </div>
            </div>
            @else
                <div class="text-center py-20 bg-white rounded-2xl border shadow-sm">
                    <p class="text-slate-500 text-lg mb-4">Keranjang Anda kosong.</p>
                    <a href="{{ route('products.katalog') }}" class="text-indigo-600 font-bold hover:underline">Kembali ke Katalog</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
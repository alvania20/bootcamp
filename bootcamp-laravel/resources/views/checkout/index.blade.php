<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto px-6">
            <h1 class="text-3xl font-bold mb-6">Checkout</h1>
            
            @if(isset($cartItems) && $cartItems->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Butiran Pesanan</h2>
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                        <div class="flex items-center space-x-4 border-b pb-4">
                            <img src="{{ asset('img/' . ($item->product->image ?? 'default.jpg')) }}" alt="{{ $item->product->name ?? 'Produk' }}" class="w-16 h-16 object-cover rounded">
                            <div class="flex-1">
                                <h3 class="font-medium">{{ $item->product->name ?? 'Produk Tidak Ditemukan' }}</h3>
                                <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-bold">Rp {{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t font-bold text-lg">
                        Total: Rp {{ number_format($totalPrice ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Alamat Pengiriman</h2>
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Nama Penuh</label>
                            <input type="text" name="name" class="w-full border rounded-lg p-3 mt-1" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Alamat Lengkap</label>
                            <textarea name="shipping_address" class="w-full border rounded-lg p-3 mt-1" rows="3" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Metode Pembayaran</label>
                            <select name="payment_method" class="w-full border rounded-lg p-3 mt-1" required>
                                <option value="transfer_bank">Transfer Bank</option>
                                <option value="cod">COD (Bayar di Tempat)</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">
                            Selesaikan Pembayaran
                        </button>
                    </form>
                </div>
            </div>
            @else
                <div class="text-center py-10">
                    <p class="text-gray-500 text-lg">Keranjang Anda kosong atau data checkout tidak ditemukan.</p>
                    <a href="{{ route('products.index') }}" class="text-blue-600 underline">Kembali ke Katalog</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
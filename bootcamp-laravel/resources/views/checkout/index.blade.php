@extends('layouts.app') 

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Checkout</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Butiran Pesanan</h2>
            <div class="space-y-4">
                @forelse($cartItems as $id => $item)
                <div class="flex items-center space-x-4 border-b pb-4">
                    <img src="{{ asset('img/' . $item['image']) }}" alt="{{ $item['nama'] }}" class="w-16 h-16 object-cover rounded">
                    <div class="flex-1">
                        <h3 class="font-medium">{{ $item['nama'] }}</h3>
                        <div class="flex items-center mt-2">
                            <form action="{{ route('checkout.update', $id) }}" method="POST" class="flex items-center">
                                @csrf @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border rounded p-1 mr-2">
                                <button type="submit" class="text-xs bg-gray-200 px-2 py-1 rounded">Update</button>
                            </form>
                        </div>
                    </div>
                    <form action="{{ route('checkout.remove', $id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                    </form>
                    <span class="font-bold">Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</span>
                </div>
                @empty
                    <p class="text-gray-500">Keranjang anda kosong.</p>
                @endforelse
            </div>
            <div class="mt-4 pt-4 border-t font-bold text-lg">
                Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Alamat Penghantaran</h2>
            <form action="{{ route('checkout.index') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Penuh</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded-lg p-3 mt-1" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea name="address" class="w-full border-gray-300 rounded-lg p-3 mt-1" rows="3" required></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                    Selesaikan Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
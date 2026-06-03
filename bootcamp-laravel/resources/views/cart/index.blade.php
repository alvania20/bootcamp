<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-extrabold text-slate-900 mb-8">
            {{ auth()->user()->isAdmin() ? 'Manajemen Keranjang Semua User' : 'Keranjang Belanja Anda' }}
        </h2>

        @if($cartItems->isEmpty())
            <div class="text-center py-20 bg-white rounded-2xl border shadow-sm">
                <p class="text-slate-500 text-lg">Keranjang saat ini kosong.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700">
                    Belanja Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Daftar Produk --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                    <div class="bg-white p-4 rounded-xl border flex items-center gap-4 shadow-sm">
                        <img src="{{ asset('img/' . ($item->product->image ?? 'default.png')) }}" class="w-20 h-20 object-cover rounded-lg">
                        
                        <div class="flex-grow">
                            <h3 class="font-bold text-slate-900">{{ $item->product->name ?? 'Produk Tidak Ditemukan' }}</h3>
                            <p class="text-sm text-indigo-600 font-bold">Rp {{ number_format($item->product->price ?? 0, 0, ',', '.') }}</p>
                            
                            @if(auth()->user()->isAdmin())
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">User: {{ $item->user->name ?? 'Unknown' }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            {{-- PERBAIKAN: Gunakan 'cart.update' bukan 'carts.update' --}}
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 border rounded p-1 text-center">
                                <button type="submit" class="text-xs text-slate-500 underline hover:text-indigo-600">Update</button>
                            </form>
                            
                            {{-- PERBAIKAN: Gunakan 'cart.destroy' bukan 'carts.destroy' --}}
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 font-bold text-sm hover:text-red-700" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Ringkasan --}}
                @if(!auth()->user()->isAdmin())
                <div class="bg-slate-50 p-6 rounded-2xl border h-fit">
                    <h3 class="text-lg font-bold mb-4">Ringkasan Belanja</h3>
                    <div class="flex justify-between mb-2">
                        <span class="text-slate-600">Total Harga</span>
                        <span class="font-bold text-lg">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <hr class="my-4">
                    <a href="{{ route('checkout.index') }}" class="block w-full bg-indigo-600 text-white text-center py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
                        Lanjut Checkout
                    </a>
                </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
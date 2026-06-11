<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h2 class="text-3xl font-extrabold text-slate-900 mb-8">
            {{ auth()->user()->isAdmin() ? 'Manajemen Keranjang Semua User' : 'Keranjang Belanja Anda' }}
        </h2>

        @if($cartItems->isEmpty())
            <div class="text-center py-20 bg-white rounded-2xl border shadow-sm">
                <p class="text-slate-500 text-lg">Keranjang saat ini kosong.</p>
                <a href="{{ route('products.katalog') }}" 
                   class="mt-4 inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition">
                    Lihat Katalog Produk
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- Daftar Produk --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white p-4 rounded-xl border border-gray-100 flex items-center gap-4 shadow-sm hover:shadow-md transition">
                            <img src="{{ $item->product?->image && file_exists(public_path('img/'.$item->product->image)) ? asset('img/' . $item->product->image) : asset('img/default.png') }}" 
                                 class="w-20 h-20 object-cover rounded-lg" 
                                 alt="{{ $item->product->name ?? 'Produk' }}">
                            
                            <div class="flex-grow">
                                <h3 class="font-bold text-slate-900">{{ $item->product->name ?? 'Produk Tidak Ditemukan' }}</h3>
                                <p class="text-sm text-indigo-600 font-bold">
                                    {{ $item->product ? 'Rp ' . number_format($item->product->price, 0, ',', '.') : 'Harga tidak tersedia' }}
                                </p>
                                @if(auth()->user()->isAdmin() && isset($item->user))
                                    <span class="inline-block px-2 py-0.5 mt-1 bg-gray-100 text-[10px] text-gray-600 font-bold uppercase rounded">
                                        User: {{ $item->user->name }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" 
                                           max="{{ $item->product->stock ?? 99 }}"
                                           class="w-16 border border-gray-200 rounded p-1 text-center focus:ring-2 focus:ring-indigo-500 outline-none" required>
                                    <button type="submit" class="text-xs text-slate-500 underline hover:text-indigo-600">Update</button>
                                </form>
                                
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs font-bold hover:text-red-700" 
                                            onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Ringkasan Belanja --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm sticky top-24">
                    <h3 class="text-lg font-bold mb-4 text-slate-900">Ringkasan Belanja</h3>
                    <div class="flex justify-between mb-6">
                        <span class="text-slate-600">Total Harga</span>
                        <span class="font-black text-xl text-indigo-700">
                            Rp {{ number_format($totalPrice ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('checkout.index') }}" 
                           class="block w-full bg-indigo-600 text-white text-center py-3 rounded-xl font-bold hover:bg-indigo-700 active:scale-95 transition-all">
                            Lanjut Checkout
                        </a>
                    @else
                        <p class="text-xs text-slate-400 italic text-center">Mode Admin: Tidak dapat melakukan checkout.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
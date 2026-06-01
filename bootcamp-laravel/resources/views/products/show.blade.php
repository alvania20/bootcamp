<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4">
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">{{ session('error') }}</div>
            @endif

            <a href="{{ route('products.index') }}" class="group inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 font-semibold mb-8 transition-all">
                ← Kembali ke Katalog
            </a>

            <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-100 border border-slate-100 overflow-hidden grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-5 bg-slate-100 flex items-center justify-center relative min-h-[350px]">
                    <img src="{{ $product->image ? asset('img/' . $product->image) : asset('images/default.png') }}" 
                         alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>

                <div class="md:col-span-7 p-8 md:p-10 flex flex-col">
                    <h2 class="text-3xl font-black text-slate-900">{{ $product->name }}</h2>
                    
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="p-4 bg-slate-900 rounded-2xl text-white">
                            <span class="text-[9px] uppercase font-bold text-slate-400">Harga</span>
                            <div class="text-xl font-black">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-900">
                            <span class="text-[9px] uppercase font-bold text-indigo-400">Stok</span>
                            <div class="text-xl font-black">{{ $product->stock }} Unit</div>
                        </div>
                    </div>

                    <div class="mt-6 text-slate-600 text-sm">
                        {{ $product->description }}
                    </div>

                    <div class="mt-8">
                        <form action="{{ route('cart.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="flex items-center gap-4">
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                                        class="w-20 mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <button type="submit" class="mt-5 bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </form>
                    </div>

                    @auth
                    <div class="border-t border-slate-100 pt-8 mt-6 flex gap-4">
                        <a href="{{ route('products.edit', $product->id) }}" class="flex-1 bg-white border border-indigo-600 text-indigo-600 py-3 rounded-2xl font-bold text-center hover:bg-indigo-50">
                            Edit Produk
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full bg-red-50 text-red-600 py-3 rounded-2xl font-bold hover:bg-red-100">Hapus</button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
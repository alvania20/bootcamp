<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4">
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4 shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4 shadow-sm">{{ session('error') }}</div>
            @endif

            <a href="{{ route('products.katalog') }}" class="group inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 font-semibold mb-8 transition-all">
                ← Kembali ke Katalog
            </a>

            <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-100 border border-slate-100 overflow-hidden grid grid-cols-1 md:grid-cols-12">
                {{-- Image Section --}}
                <div class="md:col-span-5 bg-slate-100 flex items-center justify-center relative min-h-[350px]">
                    <img src="{{ $product->image && file_exists(public_path('img/'.$product->image)) ? asset('img/' . $product->image) : asset('img/default.png') }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover">
                </div>

                {{-- Content Section --}}
                <div class="md:col-span-7 p-8 md:p-10 flex flex-col">
                    <h2 class="text-3xl font-black text-slate-900">{{ $product->name }}</h2>
                    
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="p-4 bg-slate-900 rounded-2xl text-white">
                            <span class="text-[9px] uppercase font-bold text-slate-400">Harga</span>
                            <div class="text-xl font-black">{{ $product->formatted_price }}</div>
                        </div>
                        <div class="p-4 {{ $product->stock > 0 ? 'bg-indigo-50 text-indigo-900' : 'bg-red-50 text-red-900' }} rounded-2xl">
                            <span class="text-[9px] uppercase font-bold {{ $product->stock > 0 ? 'text-indigo-400' : 'text-red-400' }}">Stok</span>
                            <div class="text-xl font-black">{{ $product->stock > 0 ? $product->stock . ' Unit' : 'Habis' }}</div>
                        </div>
                    </div>

                    <div class="mt-6 text-slate-600 text-sm leading-relaxed">
                        {{ $product->description ?? 'Tidak ada deskripsi produk.' }}
                    </div>

                    {{-- Add to Cart Section --}}
                    <div class="mt-8 flex justify-center md:justify-start"> @if($product->stock > 0)
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <p class="text-sm text-slate-500 italic bg-slate-100 p-3 rounded-lg text-center">
                                        * Admin tidak dapat melakukan pembelian.
                                    </p>
                                @else
                                    <form action="{{ route('cart.store') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="flex flex-col items-center md:flex-row md:items-end gap-4">
                                            <div>
                                                <label for="quantity" class="block text-sm font-medium text-gray-700 text-center">Jumlah</label>
                                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                                                    class="w-20 mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-center" required>
                                            </div>
                                            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 w-full md:w-auto">
                                                Tambah ke Keranjang
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            @else
                                {{-- Guest User --}}
                                <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 w-full text-center md:w-auto">
                                    Login untuk Membeli
                                </a>
                            @endauth
                        @else
                            <button disabled class="bg-slate-300 text-white px-8 py-3 rounded-xl font-bold cursor-not-allowed w-full md:w-auto">
                                Produk Habis
                            </button>
                        @endif
                    </div>
                </div> 
            </div> 
        </div> 
    </div> 
</x-app-layout>
        @extends('layouts.app')

        @section('title', 'Katalog Produk GadgetShop')

        @section('content')
        <div class="container mx-auto px-4 md:px-6">
            <!-- Header responsif -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Inventaris</h2>
                </div>
                <a href="{{ route('products.create') }}" class="w-full sm:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-bold text-sm transition">
                    Tambah Produk
                </a>
            </div>

            <!-- Grid Responsif -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white border rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col">
                        <div class="h-40 bg-slate-100 rounded-t-2xl overflow-hidden">
                            @if($product->gambar)
                                <img src="{{ asset('img/' . $product->gambar) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-slate-800 truncate">{{ $product->nama }}</h3>
                            <p class="text-xs text-slate-500 mt-1 flex-grow">{{ Str::limit($product->deskripsi, 60) }}</p>
                            <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                <span class="font-black text-slate-900 text-sm">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                <a href="{{ route('products.show', $product->id) }}" class="text-indigo-600 text-xs font-bold">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endsection
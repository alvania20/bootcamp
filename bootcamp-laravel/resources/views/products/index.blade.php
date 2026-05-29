@extends('layouts.app')

@section('title', 'Katalog Produk GadgetShop')

@section('content')
<div class="container mx-auto px-6 py-8">
    {{-- Header & Filter --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10">
        <h2 class="text-3xl font-extrabold text-slate-900">Katalog Produk</h2>
        
        <div class="flex items-center gap-3">
            <div class="flex gap-2">
                <a href="{{ route('products.index') }}" 
                   class="px-4 py-2 text-sm font-bold rounded-xl {{ !request('category') ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-600 hover:bg-slate-50' }}">
                   Semua
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                       class="px-4 py-2 text-sm font-bold rounded-xl {{ request('category') == $category->slug ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-600 hover:bg-slate-50' }}">
                       {{ $category->name }}
                    </a>
                @endforeach
            </div>
            <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-lg shadow-indigo-200">
                + Tambah
            </a>
        </div>
    </div>

    {{-- Daftar Produk --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($products as $product)
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden">
                <div class="h-56 bg-slate-100 overflow-hidden">
                    @if($product->image) 
                        <img src="{{ asset('img/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">No Image</div>
                    @endif
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-[10px] uppercase tracking-widest font-bold text-indigo-500">
                        {{ $product->category->name ?? 'Tanpa Kategori' }}
                    </span>
                    <h3 class="font-bold text-lg text-slate-900 truncate mt-1">{{ $product->name }}</h3>
                    <p class="text-sm text-slate-500 mt-2 flex-grow">{{ Str::limit($product->description, 50) }}</p>
                    
                    <div class="mt-6 flex items-center justify-between">
                        <span class="font-black text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        <a href="{{ route('products.show', $product->id) }}" class="text-indigo-600 font-bold text-sm hover:underline">Detail</a>
                    </div>
                    
                    <form action="{{ route('carts.store', $product->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-bold hover:bg-blue-700 transition text-sm">
                            Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20">
                <p class="text-slate-500 text-lg">Belum ada produk dalam kategori ini.</p>
                <a href="{{ route('products.index') }}" class="text-indigo-600 font-bold hover:underline">Tampilkan semua produk</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination (Diatur di tengah agar rapi) --}}
    <div class="mt-12 flex justify-center">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection
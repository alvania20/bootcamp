@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="{{ route('products.index') }}" class="group inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 font-semibold mb-8 transition-all">
        <span class="bg-slate-100 p-1.5 rounded-full group-hover:bg-indigo-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </span>
        Kembali ke Katalog
    </a>

    <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-100 border border-slate-100 overflow-hidden grid grid-cols-1 md:grid-cols-12">
        
        <div class="md:col-span-5 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center relative min-h-[350px]">
            @if($product->image)
                <img src="{{ asset('img/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply transition-transform duration-500 hover:scale-105">
            @else
                <div class="text-slate-400 text-center">
                    <span class="text-5xl block mb-2">📷</span>
                    <span>No Image</span>
                </div>
            @endif
            
            <div class="absolute top-4 left-4">
                <span class="bg-white/90 backdrop-blur-sm text-indigo-700 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-sm border border-white">
                    {{ $product->category->name ?? 'Uncategorized' }}
                </span>
            </div>
        </div>

        <div class="md:col-span-7 p-8 md:p-10 flex flex-col">
            <div class="flex-grow space-y-6">
                <div>
                    <span class="text-[10px] font-bold text-indigo-500 tracking-widest uppercase">
                        ID #{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 mt-1">{{ $product->name }}</h2>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-900 rounded-2xl shadow-inner">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Harga Resmi</span>
                        <div class="text-xl font-black text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                        <span class="text-[9px] font-bold text-indigo-400 uppercase tracking-wider block mb-1">Stok Tersedia</span>
                        <div class="text-xl font-black text-indigo-900">{{ $product->stock }} Unit</div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Deskripsi Produk</h3>
                    <div class="text-slate-600 leading-relaxed text-sm bg-slate-50 p-4 rounded-xl border border-slate-100">
                        {{ $product->description }}
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-8 mt-6 flex gap-4">
                <a href="{{ route('products.edit', $product->id) }}" 
                   class="flex-1 bg-white hover:bg-slate-50 border-2 border-slate-200 text-slate-700 py-3.5 rounded-2xl font-bold text-xs transition flex items-center justify-center gap-2 shadow-sm">
                    Edit Informasi
                </a>
                
                <form action="{{ route('products.destroy', $product->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.');" 
                      class="flex-1">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full bg-red-50 hover:bg-red-100 text-red-600 py-3.5 rounded-2xl font-bold text-xs transition flex items-center justify-center gap-2 border-2 border-transparent hover:border-red-200">
                        Hapus Katalog
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
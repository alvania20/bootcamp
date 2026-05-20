@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->nama)

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 font-semibold mb-6 transition">
        <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Katalog
    </a>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-150 overflow-hidden grid grid-cols-1 md:grid-cols-12">
        <div class="md:col-span-5 bg-slate-100 flex items-center justify-center relative min-h-[300px] md:min-h-full">
            @if($product->gambar)
                <img src="{{ asset('img/' . $product->gambar) }}" alt="[Gambar {{ $product->nama }}]" class="w-full h-full object-cover">
            @else
                <div class="text-center text-slate-400">
                    <span class="text-5xl block mb-2">📷</span>
                    <span class="text-sm">Tidak Ada Foto</span>
                </div>
            @endif
            <span class="absolute top-4 left-4 bg-indigo-600/90 text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase">{{ $product->kategori }}</span>
        </div>

        <div class="md:col-span-7 p-8 flex flex-col justify-between">
            <div class="space-y-6">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 tracking-wider uppercase block">ID Spesifikasi #00{{ $product->id }}</span>
                    <h2 class="text-2xl font-extrabold text-slate-800 leading-tight mt-1">{{ $product->nama }}</h2>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[9px] font-extrabold text-slate-400 uppercase block mb-1">Harga Resmi</span>
                        <div class="text-lg font-black text-indigo-600">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[9px] font-extrabold text-slate-400 uppercase block mb-1">Stok Inventaris</span>
                        <div class="text-lg font-bold text-slate-700">{{ $product->stok }} Unit</div>
                    </div>
                </div>

                <div>
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2 block">Deskripsi Lengkap</span>
                    <p class="text-slate-600 leading-relaxed text-sm">{{ $product->deskripsi }}</p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6 mt-8 flex gap-3">
                <a href="{{ route('products.edit', $product->id) }}" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-3 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-md shadow-amber-50">
                    <svg class="w-4 h-4" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Edit Detail
                </a>

                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini secara permanen?');" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-md shadow-red-50">
                        <svg class="w-4 h-4" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Katalog
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
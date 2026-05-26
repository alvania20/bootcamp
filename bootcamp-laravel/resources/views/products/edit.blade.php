@extends('layouts.app')

@section('title', 'Ubah Produk - ' . $product->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 font-semibold mb-6 transition">
        <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Katalog
    </a>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-150 overflow-hidden">
        <div class="bg-gradient-to-tr from-amber-500 to-orange-500 p-6 text-white">
            <h2 class="text-2xl font-bold">Edit Detail Produk</h2>
            <p class="text-amber-50 text-sm mt-1">Ubah atau perbarui data spesifikasi produk.</p>
        </div>

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nama Produk</label>
                    <input type="text" name="name" value="{{ $product->name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kategori</label>
                    <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">
                        <option value="1" {{ $product->category_id == 1 ? 'selected' : 'Laptop' }}>Laptop</option>
                        <option value="2" {{ $product->category_id == 2 ? 'selected' : 'Aksesoris' }}>Aksesoris</option>
                        <option value="3" {{ $product->category_id == 3 ? 'selected' : 'Smartphone' }}>Smartphone</option>
                        <option value="4" {{ $product->category_id == 4 ? 'selected' : 'Audio' }}>Audio</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Harga (Rupiah)</label>
                    <input type="number" name="price" value="{{ $product->price }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Stok (Unit)</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi Lengkap</label>
                <textarea name="description" rows="4" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">{{ $product->description }}</textarea>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                <div class="text-center md:col-span-1">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Foto Saat Ini</span>
                    @if($product->image)
                        <img src="{{ asset('img/' . $product->image) }}" alt="[Image {{ $product->name }}]" class="w-24 h-24 object-cover mx-auto rounded-xl shadow-sm border border-slate-200">
                    @else
                        <span class="text-xs text-slate-500">Tidak ada foto</span>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Ganti Foto Baru (Opsional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6 flex gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-indigo-100">
                    Simpan Perubahan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
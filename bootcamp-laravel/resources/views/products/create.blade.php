@extends('layouts.app')

@section('title', 'Tambah Produk Baru - GadgetShop')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 font-semibold mb-6 transition">
        <svg class="w-5 h-5" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Katalog
    </a>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-150 overflow-hidden">
        <div class="bg-gradient-to-tr from-indigo-600 to-violet-600 p-6 text-white">
            <h2 class="text-2xl font-bold">Tambah Produk Baru</h2>
            <p class="text-indigo-100 text-sm mt-1">Masukkan spesifikasi lengkap produk beserta foto katalognya.</p>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nama Produk</label>
                    <input type="text" name="nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm" placeholder="Contoh: iPhone 15 Pro">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kategori</label>
                    <select name="kategori" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">
                        <option value="Smartphone">Smartphone</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Audio">Audio</option>
                        <option value="Aksesoris">Aksesoris</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Harga (Rupiah)</label>
                    <input type="number" name="harga" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm" placeholder="Contoh: 15000000">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Stok (Unit)</label>
                    <input type="number" name="stok" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm" placeholder="Contoh: 10">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi Lengkap</label>
                <textarea name="deskripsi" rows="4" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm" placeholder="Tuliskan spesifikasi detail dan fitur unggulan produk..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Foto Katalog Produk</label>
                <input type="file" name="gambar" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <span class="text-[10px] text-slate-400 mt-1.5 block">Format: JPG, PNG, WEBP. Maksimal ukuran file: 2 MB.</span>
            </div>

            <div class="border-t border-slate-100 pt-6 flex gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-indigo-100">
                    Simpan Produk Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

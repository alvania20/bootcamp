@extends('layouts.app')

@section('title', 'Tambah Produk Baru - GadgetShop')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 font-semibold mb-6 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Katalog
    </a>

    {{-- Menampilkan Pesan Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-50 p-4 rounded-xl border border-red-200 mb-6">
            <ul class="text-sm text-red-600 list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-tr from-indigo-600 to-violet-600 p-8 text-white">
            <h2 class="text-3xl font-bold">Tambah Produk Baru</h2>
            <p class="text-indigo-100 mt-2">Lengkapi detail informasi produk di bawah ini untuk menambahkannya ke inventaris.</p>
        </div>
        
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b pb-2">Informasi Produk</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-600">Nama Produk</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kategori</label>
                        <select name="kategori" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">
                            <option value="Smartphone" {{ old('kategori') == 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
                            <option value="Laptop" {{ old('kategori') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                            <option value="Audio" {{ old('kategori') == 'Audio' ? 'selected' : '' }}>Audio</option>
                            <option value="Aksesoris" {{ old('kategori') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-600">Harga (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-slate-400 font-medium">Rp</span>
                            <input type="number" name="harga" value="{{ old('harga') }}" required placeholder="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-600">Stok</label>
                        <input type="number" name="stok" value="{{ old('stok') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-600">Deskripsi Lengkap</label>
                    <textarea name="deskripsi" rows="5" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Media Produk</h3>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-600">Foto Katalog</label>
                    <input type="file" name="gambar" required accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform hover:-translate-y-1">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
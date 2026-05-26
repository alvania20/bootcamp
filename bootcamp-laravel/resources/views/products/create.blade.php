@extends('layouts.app')

@section('title', 'Tambah Produk Baru - GadgetShop')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 font-semibold transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Katalog
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 p-4 rounded-xl border border-red-200 mb-6">
            <ul class="text-sm text-red-600 list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-tr from-indigo-600 to-violet-600 p-8 text-white">
            <h2 class="text-3xl font-bold">Tambah Produk Baru</h2>
        </div>
        
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-600">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600">Kategori</label>
                <select name="category_id" required class="w-full bg-slate-50 border rounded-xl px-4 py-3">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-600">Harga</label>
                    <input type="number" name="price" value="{{ old('price') }}" required class="w-full bg-slate-50 border rounded-xl px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-600">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" required class="w-full bg-slate-50 border rounded-xl px-4 py-3">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-600">Foto</label>
                <input type="file" name="image" required class="w-full text-sm">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl hover:bg-indigo-700">
                Simpan Produk
            </button>
        </form>
    </div>
</div>
@endsection
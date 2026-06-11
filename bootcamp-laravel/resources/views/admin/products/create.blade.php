<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4">
            <div class="mb-6">
                {{-- KOREKSI: Menggunakan admin.products.index --}}
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 font-semibold transition">
                    ← Kembali ke Katalog
                </a>
            </div>

            {{-- Menampilkan error validasi --}}
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
                </div>
                
                {{-- KOREKSI: Menggunakan admin.products.store --}}
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-600">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-600">Kategori</label>
                        <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-600">Harga</label>
                            <input type="number" name="price" value="{{ old('price') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600">Stok</label>
                            <input type="number" name="stock" value="{{ old('stock') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-600">Deskripsi</label>
                        <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 mt-1 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-600">Foto Produk</label>
                        <input type="file" name="image" required class="w-full mt-2 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        Simpan Produk
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
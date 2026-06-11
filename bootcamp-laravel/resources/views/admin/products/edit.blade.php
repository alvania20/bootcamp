<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4">
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 font-semibold mb-6 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Produk
            </a>

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-tr from-amber-500 to-orange-500 p-6 text-white">
                    <h2 class="text-2xl font-bold">Edit Produk: {{ $product->name }}</h2>
                    <p class="text-amber-50 text-sm mt-1">Pastikan data yang diinput sudah benar.</p>
                </div>

                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-slate-50 border {{ $errors->has('name') ? 'border-red-500' : 'border-slate-200' }} rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kategori</label>
                            <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Harga</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Stok</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi</label>
                        <textarea name="description" rows="4" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-indigo-500 text-sm">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col md:flex-row gap-4 items-center">
                        <div class="text-center">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Foto Saat Ini</span>
                            @if($product->image)
                                <img src="{{ asset('img/' . $product->image) }}" alt="Foto Produk" class="w-20 h-20 object-cover rounded-xl border border-slate-200">
                            @else
                                <div class="w-20 h-20 bg-slate-200 rounded-xl flex items-center justify-center text-slate-400">N/A</div>
                            @endif
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Ganti Foto Baru</label>
                            <input type="file" name="image" accept="image/*" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-500 text-sm">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl font-bold transition shadow-lg">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
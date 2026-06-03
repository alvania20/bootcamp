<x-app-layout>
    {{-- Header Opsional --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Gadget') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-6 py-8">
        {{-- Header Katalog & Filter --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10">
            <h2 class="text-3xl font-extrabold text-slate-900">Katalog Produk</h2>
            
            <div class="flex items-center gap-3">
                <div class="flex gap-2">
                    <a href="{{ route('products.index') }}" 
                       class="px-4 py-2 text-sm font-bold rounded-xl {{ !request('category') ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-600 hover:bg-slate-50' }}">
                       Semua
                    </a>
                    {{-- Gunakan pengecekan isset agar tidak error jika variable kosong --}}
                    @isset($categories)
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                               class="px-4 py-2 text-sm font-bold rounded-xl {{ request('category') == $category->slug ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-600 hover:bg-slate-50' }}">
                               {{ $category->name }}
                            </a>
                        @endforeach
                    @endisset
                </div>
                <a href="{{ route('products.create') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition">
                    + Tambah
                </a>
            </div>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden">
                    <div class="h-56 bg-slate-100 overflow-hidden">
                        {{-- Menggunakan storage/link lebih disarankan, namun tetap menggunakan logika Anda --}}
                        <img src="{{ $product->image ? asset('img/' . $product->image) : asset('images/default.png') }}" 
                             alt="{{ $product->name }}" class="w-full h-full object-cover">
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
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-slate-50 rounded-3xl border border-dashed border-slate-300">
                    <p class="text-slate-500 text-lg">Belum ada produk yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-12 flex justify-center">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
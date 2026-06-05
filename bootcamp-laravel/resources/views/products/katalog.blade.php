<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Gadget') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-6 py-8">
        {{-- Header Katalog & Filter --}}
        <div class="flex flex-col gap-6 mb-10">
            <h2 class="text-3xl font-extrabold text-slate-900">Katalog Produk</h2>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- Filter Kategori --}}
                <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    <a href="{{ route('products.katalog', ['sort' => request('sort'), 'search' => request('search')]) }}" 
                       class="whitespace-nowrap px-4 py-2 text-sm font-bold rounded-full transition {{ !request('category') ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-600 hover:bg-slate-50' }}">
                        Semua
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('products.katalog', ['category' => $category->slug, 'sort' => request('sort'), 'search' => request('search')]) }}" 
                           class="whitespace-nowrap px-4 py-2 text-sm font-bold rounded-full transition {{ request('category') == $category->slug ? 'bg-indigo-600 text-white' : 'bg-white border text-slate-600 hover:bg-slate-50' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                {{-- Form Pencarian & Sorting --}}
                <form action="{{ route('products.katalog') }}" method="GET" class="flex items-center gap-2 shrink-0">
                    {{-- Hidden inputs agar filter tidak hilang saat search/sort berubah --}}
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari produk..." 
                           class="border-slate-200 text-sm rounded-lg p-2.5 w-full md:w-64 focus:ring-indigo-500 focus:border-indigo-500">
                    
                    <select name="sort" onchange="this.form.submit()" 
                            class="border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2.5">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Paling Sesuai</option>
                        <option value="harga_tertinggi" {{ request('sort') == 'harga_tertinggi' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="harga_terendah" {{ request('sort') == 'harga_terendah' ? 'selected' : '' }}>Harga Terendah</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden">
                    <div class="h-56 bg-slate-100 overflow-hidden relative">
                        <img src="{{ $product->image && file_exists(public_path('img/'.$product->image)) ? asset('img/' . $product->image) : asset('img/default.png') }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
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
                    <p class="text-slate-500 text-lg">Produk yang Anda cari tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
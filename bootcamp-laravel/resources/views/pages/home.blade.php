<x-app-layout>
    <div class="min-h-[70vh] flex flex-col items-center justify-start px-6 pt-12 pb-20 mt-12">
        
        <div class="w-full max-w-4xl text-center space-y-8 animate-in fade-in duration-700">
            
            {{-- Badge Status --}}
            <div class="inline-flex items-center gap-2 bg-indigo-50 px-4 py-1.5 rounded-full border border-indigo-100 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                </span>
                <span class="text-[10px] font-bold text-indigo-700 uppercase tracking-[0.2em]">
                    Sistem Manajemen Terintegrasi v2.0
                </span>
            </div>
            
            {{-- Heading Utama --}}
            <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.1]">
                {{ $title ?? 'Selamat Datang di Gadget Store' }}
            </h1>
            
            {{-- Deskripsi --}}
            <p class="text-lg md:text-xl text-slate-500 leading-relaxed max-w-2xl mx-auto">
                {{ $description ?? 'Kelola inventaris gadget Anda dengan lebih efisien dan modern.' }}
            </p>
            
            {{-- Call to Action --}}
            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-6">
                <a href="{{ route('products.katalog') }}" 
                   class="group relative inline-flex items-center justify-center px-8 py-4 bg-indigo-600 text-white rounded-2xl font-semibold hover:bg-indigo-700 transition-all duration-300 shadow-xl shadow-indigo-200 hover:shadow-2xl hover:-translate-y-1">
                    Kelola Semua Produk
                    <svg class="ml-2 w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                
                <a href="{{ route('page.about') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-slate-700 rounded-2xl font-semibold border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 transition-all duration-300 shadow-sm">
                    Tentang Aplikasi
                </a>
            </div>
        </div>
        
    </div>
</x-app-layout>
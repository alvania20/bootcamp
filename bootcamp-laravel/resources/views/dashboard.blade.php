<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-sm text-slate-500">Total Pesanan</p>
                    <h3 class="text-2xl font-black text-slate-900">12</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-sm text-slate-500">Menunggu Pembayaran</p>
                    <h3 class="text-2xl font-black text-orange-500">2</h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-sm text-slate-500">Wishlist</p>
                    <h3 class="text-2xl font-black text-indigo-600">5</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-slate-900">Pesanan Terakhir</h3>
                </div>
                <div class="p-6">
                    <p class="text-slate-500">Belum ada pesanan terbaru.</p>
                    <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 font-bold hover:underline">
                        Mulai Belanja →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
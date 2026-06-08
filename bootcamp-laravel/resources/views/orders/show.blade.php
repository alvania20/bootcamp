<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            {{-- Header Detail --}}
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-800">Detail Pesanan #{{ $order->id }}</h2>
                    <p class="text-gray-500">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="px-4 py-2 bg-indigo-100 text-indigo-700 font-bold rounded-full uppercase text-sm">
                    {{ $order->status }}
                </span>
            </div>

            <hr class="my-6 border-gray-100">

            {{-- Info Pembeli --}}
            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-2">Informasi Pembeli</h3>
                <p class="text-gray-600">Nama: <span class="font-semibold">{{ $order->user->name ?? 'Tidak diketahui' }}</span></p>
                <p class="text-gray-600">Email: {{ $order->user->email ?? '-' }}</p>
            </div>

            {{-- Daftar Produk --}}
            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-4">Produk yang Dibeli</h3>
                <div class="space-y-3">
                    @foreach($order->orderItems as $item)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                            <span>{{ $item->product->name ?? 'Produk dihapus' }}</span>
                            <span class="font-bold">x{{ $item->quantity }}</span>
                            <span class="font-bold text-indigo-600">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-right font-black text-xl">
                    Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </div>
            </div>

            <hr class="my-6 border-gray-100">

            {{-- Riwayat Pesanan --}}
            <div>
                <h3 class="font-bold text-gray-800 mb-4">Riwayat Perubahan Status</h3>
                <ul class="space-y-4">
                    @forelse($order->histories as $history)
                        <li class="border-l-4 border-indigo-200 pl-4">
                            <p class="font-bold text-gray-800">{{ ucfirst($history->status) }}</p>
                            <p class="text-xs text-gray-400 font-semibold">{{ $history->created_at->format('d M Y H:i') }}</p>
                            <p class="text-sm text-gray-600 italic">"{{ $history->notes }}"</p>
                            <p class="text-[10px] text-gray-400 uppercase mt-1">Oleh: {{ $history->user->name ?? 'System' }}</p>
                        </li>
                    @empty
                        <li class="text-gray-400 italic">Belum ada riwayat perubahan status.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
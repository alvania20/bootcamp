<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            {{-- Navigasi --}}
            <a href="{{ route('orders.index') }}" class="inline-flex items-center text-sm text-indigo-600 hover:underline mb-6">
                &larr; Kembali ke daftar pesanan
            </a>
            
            {{-- Header Detail --}}
            <div class="flex flex-col md:flex-row justify-between md:items-start gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-800">Invoice #{{ $order->order_number }}</h2>
                    <p class="text-gray-500">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="px-4 py-1.5 font-bold rounded-full uppercase text-xs self-start
                    {{ $order->status == 'completed' ? 'bg-green-100 text-green-700' : 
                       ($order->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-indigo-100 text-indigo-700') }}">
                    {{ $order->status }}
                </span>
            </div>

            <hr class="my-6 border-gray-100">

            {{-- Informasi Pembeli & Alamat --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="font-bold text-gray-800 mb-2 border-l-4 border-indigo-500 pl-2">Informasi Pembeli</h3>
                    <p class="text-gray-600 text-sm">Nama: <span class="font-semibold text-gray-900">{{ $order->user->name ?? 'Tidak diketahui' }}</span></p>
                    <p class="text-gray-600 text-sm">Email: {{ $order->user->email ?? '-' }}</p>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2 border-l-4 border-indigo-500 pl-2">Alamat Pengiriman</h3>
                    <p class="text-gray-600 text-sm italic">{{ $order->shipping_address }}</p>
                </div>
            </div>

            {{-- Daftar Produk --}}
            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-4">Produk yang Dibeli</h3>
                <div class="space-y-3">
                    @foreach($order->orderItems as $item)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                            <span class="font-medium text-gray-700">{{ $item->product->name ?? 'Produk dihapus' }}</span>
                            <div class="flex items-center gap-6">
                                <span class="text-sm text-gray-500">x{{ $item->quantity }}</span>
                                <span class="font-bold text-indigo-600">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-right font-black text-xl text-gray-800">
                    Total: <span class="text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Form Update Status (KOREKSI: Menggunakan admin.orders.updateStatus) --}}
            @if(auth()->user()->isAdmin())
                <div class="mb-8 p-6 bg-indigo-50 rounded-2xl border border-indigo-100">
                    <h3 class="font-bold text-indigo-900 mb-4">Update Status Pesanan</h3>
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <select name="status" class="w-full rounded-xl border-gray-200 shadow-sm focus:ring-indigo-500">
                            @foreach(['pending', 'paid', 'shipped', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <textarea name="notes" placeholder="Tulis catatan (opsional)..." class="w-full rounded-xl border-gray-200 shadow-sm focus:ring-indigo-500"></textarea>
                        <button type="submit" class="w-full md:w-auto bg-indigo-600 text-white px-8 py-2 rounded-xl font-bold hover:bg-indigo-700 transition">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            @endif

            {{-- Riwayat Pesanan --}}
            <div>
                <h3 class="font-bold text-gray-800 mb-4">Riwayat Perubahan Status</h3>
                <div class="space-y-6">
                    @forelse($order->histories as $history)
                        <div class="relative pl-8 border-l-2 border-gray-200">
                            <div class="absolute -left-[9px] top-0 w-4 h-4 bg-indigo-500 rounded-full border-4 border-white"></div>
                            <p class="font-bold text-gray-800">{{ ucfirst($history->status) }}</p>
                            <p class="text-xs text-gray-400 font-semibold uppercase">{{ $history->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-sm text-gray-600 mt-1"><em>"{{ $history->notes }}"</em></p>
                            <p class="text-[10px] text-indigo-400 uppercase mt-1 font-bold">Oleh: {{ $history->user->name ?? 'System' }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 italic text-sm">Belum ada riwayat perubahan status.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
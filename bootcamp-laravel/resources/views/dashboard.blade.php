<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ auth()->user()->isAdmin() ? __('Dashboard Admin') : __('Dashboard Saya') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            
            @if(auth()->user()->isAdmin())
                {{-- Statistik Cards (Hanya Admin) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    @php
                        $stats = [
                            ['label' => 'Total Produk', 'value' => $data['totalProduk'] ?? 0, 'color' => 'text-gray-900'],
                            ['label' => 'Total Klik Produk', 'value' => $data['totalKlik'] ?? 0, 'color' => 'text-indigo-600'],
                            ['label' => 'Total Kategori', 'value' => $data['totalKategori'] ?? 0, 'color' => 'text-gray-900'],
                        ];
                    @endphp
                    @foreach($stats as $stat)
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">{{ $stat['label'] }}</p>
                            <h3 class="text-4xl font-extrabold {{ $stat['color'] }} mt-3">{{ number_format($stat['value']) }}</h3>
                        </div>
                    @endforeach
                </div>

                {{-- Grafik (Hanya Admin) --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Grafik Transaksi 7 Hari Terakhir</h3>
                    <div class="relative w-full h-[450px]">
                        <canvas id="transaksiChart"></canvas>
                    </div>
                </div>
            @else
                {{-- Tampilan User Biasa --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-800">Halo, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-500 mt-2">Selamat datang di dashboard pelanggan.</p>
                </div>
            @endif

            {{-- Riwayat Transaksi (Untuk keduanya) --}}
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Riwayat Transaksi Terbaru</h3>
                    <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 font-semibold hover:underline">Lihat Semua →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($transaksiTerbaru ?? [] as $t)
                        <a href="{{ route('orders.show', $t->id) }}" class="flex justify-between items-center p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:border-indigo-200 transition">
                            <div>
                                <p class="text-lg font-bold text-gray-800">{{ $t->user->name ?? 'Pelanggan' }}</p>
                                <p class="text-xs text-gray-400 uppercase font-bold mt-1">{{ ucfirst($t->status) }} • #{{ $t->id }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-indigo-600">Rp {{ number_format($t->total_price ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="col-span-2 text-center text-gray-400 py-10 italic">Belum ada transaksi.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('transaksiChart');
                if (!ctx) return; // Proteksi agar tidak error jika canvas tidak ditemukan

                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($labels ?? []),
                        datasets: [
                            {
                                label: 'Total Orders',
                                data: @json($dataOrders ?? []),
                                borderColor: '#2dd4bf',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Total Revenue',
                                data: @json($dataRevenue ?? []),
                                borderColor: '#f43f5e',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            });
        </script>
    @endif
</x-app-layout>
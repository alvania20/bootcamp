<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            {{-- Statistik Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                @php
                    $stats = [
                        ['label' => 'Total Produk', 'value' => $data['totalProduk'] ?? 0, 'color' => 'text-gray-900'],
                        ['label' => 'Total Klik Produk', 'value' => $data['totalKlik'] ?? 0, 'color' => 'text-indigo-600'],
                        ['label' => 'Total Kategori', 'value' => $data['totalKategori'] ?? 0, 'color' => 'text-gray-900'],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">{{ $stat['label'] }}</p>
                        <h3 class="text-4xl font-extrabold {{ $stat['color'] }} mt-3">{{ $stat['value'] }}</h3>
                    </div>
                @endforeach
            </div>

            {{-- Struktur baru: Grafik dan Transaksi disusun vertikal --}}
            <div class="space-y-8">
                {{-- Grafik --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Grafik Transaksi 7 Hari Terakhir</h3>
                    <div class="relative w-full h-[450px]">
                        <canvas id="transaksiChart"></canvas>
                    </div>
                </div>

                {{-- Transaksi Terbaru (Dibuat Link ke Halaman Detail) --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Riwayat Transaksi Terbaru</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($transaksiTerbaru as $t)
                            {{-- Ubah menjadi <a> untuk mengarah ke route detail --}}
                            <a href="{{ route('orders.show', $t->id) }}" 
                               class="flex justify-between items-center p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:border-indigo-400 hover:shadow-sm transition-all group">
                                <div>
                                    <p class="text-lg font-bold text-gray-800 group-hover:text-indigo-600">{{ $t->user->name ?? 'Pelanggan' }}</p>
                                    <p class="text-xs text-gray-400 uppercase font-bold mt-1">{{ $t->status }} • #{{ $t->id }}</p>
                                </div>
                                <p class="text-sm font-black text-indigo-600">Rp {{ number_format($t->total_price ?? 0, 0, ',', '.') }}</p>
                            </a>
                        @empty
                            <p class="text-center text-gray-400 py-10 italic">Belum ada transaksi.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('transaksiChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Total Orders',
                            data: @json($dataOrders),
                            borderColor: '#2dd4bf',
                            backgroundColor: 'rgba(45, 212, 191, 0.1)',
                            yAxisID: 'y',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4
                        },
                        {
                            label: 'Total Revenue',
                            data: @json($dataRevenue),
                            borderColor: '#f43f5e',
                            backgroundColor: 'rgba(244, 63, 94, 0.1)',
                            yAxisID: 'y1',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, position: 'left', title: { display: true, text: 'Jumlah Pesanan' } },
                        y1: {
                            beginAtZero: true, position: 'right', grid: { drawOnChartArea: false },
                            title: { display: true, text: 'Pendapatan (Rp)' },
                            ticks: { callback: (value) => value >= 1000000 ? (value/1000000) + ' Jt' : 'Rp ' + value.toLocaleString('id-ID') }
                        }
                    },
                    plugins: { legend: { position: 'top' } }
                }
            });
        });
    </script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ auth()->user()->isAdmin() ? __('Manajemen Semua Pesanan') : __('Daftar Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    @if($orders->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-gray-500 text-lg">Belum ada pesanan yang tercatat.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-gray-400 uppercase text-xs tracking-wider border-b">
                                        <th class="py-4">Invoice</th>
                                        @if(auth()->user()->isAdmin())
                                            <th class="py-4">User</th>
                                        @endif
                                        <th class="py-4">Status</th>
                                        <th class="py-4">Total</th>
                                        <th class="py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="py-4 font-bold text-gray-800">{{ $order->order_number }}</td>
                                            
                                            @if(auth()->user()->isAdmin())
                                                <td class="py-4 text-sm">{{ $order->user->name ?? 'Tamu' }}</td>
                                            @endif
                                            
                                            <td class="py-4">
                                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                                                    {{ $order->status == 'completed' ? 'bg-green-100 text-green-700' : 
                                                       ($order->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            
                                            <td class="py-4 font-medium text-gray-700">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </td>
                                            
                                            <td class="py-4 text-right">
                                                <a href="{{ route('orders.show', $order->id) }}" 
                                                   class="bg-gray-100 hover:bg-indigo-600 hover:text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
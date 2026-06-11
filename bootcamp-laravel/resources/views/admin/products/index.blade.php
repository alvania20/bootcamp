<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Sukses (Flash Message) --}}
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Header Tabel --}}
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Daftar Produk</h2>
                        <a href="{{ route('admin.products.create') }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                            + Tambah Produk
                        </a>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-white uppercase bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-4">ID</th>
                                    <th scope="col" class="px-6 py-4">Gambar</th>
                                    <th scope="col" class="px-6 py-4">Nama Produk</th>
                                    <th scope="col" class="px-6 py-4">Deskripsi</th>
                                    <th scope="col" class="px-6 py-4">Stok</th>
                                    <th scope="col" class="px-6 py-4">Harga</th>
                                    <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($products as $product)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $product->id }}</td>
                                        <td class="px-6 py-4">
                                            <img src="{{ asset('img/'.$product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="w-16 h-16 object-cover rounded shadow-sm">
                                        </td>
                                        <td class="px-6 py-4 font-semibold">{{ $product->name }}</td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ Str::limit($product->description, 35) }}
                                        </td>
                                        <td class="px-6 py-4">{{ $product->stock }}</td>
                                        <td class="px-6 py-4 font-medium">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 flex items-center justify-center gap-3">
                                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 font-bold">Edit</a>
                                            
                                            <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                            Belum ada data produk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-lg sm:rounded-xl">
                <h2 class="text-2xl font-extrabold mb-6 text-gray-800">Daftar Kategori Produk</h2>
                
                {{-- Form Tambah Kategori --}}
                <form action="{{ route('categories.store') }}" method="POST" class="mb-8 flex flex-col gap-2">
                    @csrf
                    <div class="flex gap-3">
                        <input type="text" name="name" placeholder="Masukkan nama kategori baru..." 
                               class="flex-grow border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2" required>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2 rounded-lg font-bold transition shadow-md">
                            Tambah
                        </button>
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                    @enderror
                </form>

                {{-- Tabel Kategori --}}
                <div class="w-full overflow-hidden border border-gray-200 rounded-lg">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase w-16">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase">Nama Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase">Jumlah Produk</th>
                                <th class="px-6 py-4 text-center text-xs font-bold uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($categories as $category)
                            <tr class="hover:bg-indigo-50 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-700 font-semibold">{{ $category->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $category->products_count }} Produk</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-4">
                                        <a href="{{ route('categories.edit', $category->id) }}" 
                                           class="flex items-center text-indigo-600 hover:text-indigo-900 font-bold text-xs gap-1">
                                            Edit
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex items-center text-red-600 hover:text-red-900 font-bold text-xs gap-1">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">Belum ada kategori yang ditambahkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
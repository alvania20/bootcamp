<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 shadow-sm sm:rounded-xl border border-gray-100">
                <h2 class="text-2xl font-extrabold mb-6 text-gray-800">Daftar Kategori Produk</h2>
                
                {{-- Form Tambah Kategori --}}
                {{-- KOREKSI: Menggunakan admin.categories.store --}}
                <form action="{{ route('admin.categories.store') }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" name="name" placeholder="Masukkan nama kategori baru..." 
                               class="flex-grow border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" required>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-lg font-bold transition shadow-md">
                            Tambah Kategori
                        </button>
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                    @enderror
                </form>

                {{-- Tabel Kategori --}}
                <div class="w-full overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah Produk</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-500 font-medium">{{ $category->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-bold">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="px-2 py-1 bg-gray-100 rounded-full text-xs font-bold">{{ $category->products_count ?? 0 }} Produk</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-4">
                                        {{-- KOREKSI: Menggunakan admin.categories.edit --}}
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 font-bold text-xs uppercase hover:underline">
                                            Edit
                                        </a>
                                        
                                        {{-- KOREKSI: Menggunakan admin.categories.destroy --}}
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs uppercase hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Belum ada kategori yang ditambahkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
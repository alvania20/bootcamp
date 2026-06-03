<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-lg sm:rounded-xl">
                <h2 class="text-2xl font-extrabold mb-6 text-gray-800">Daftar Kategori Produk</h2>
                
                <form action="{{ route('categories.store') }}" method="POST" class="mb-6 flex gap-3">
                    @csrf
                    <input type="text" name="name" placeholder="Masukkan nama kategori baru..." 
                           class="flex-grow border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2" required>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2 rounded-lg font-bold transition shadow-md">
                        Tambah
                    </button>
                </form>

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
                        <tbody class="divide-y divide-gray-200">
                            @foreach($categories as $category)
                            <tr class="hover:bg-indigo-50 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-700 font-semibold">{{ $category->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $category->products_count }} Produk</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-4">
                                        <a href="{{ route('categories.edit', $category->id) }}" 
                                           class="flex items-center text-indigo-600 hover:text-indigo-900 font-bold text-xs gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex items-center text-red-600 hover:text-red-900 font-bold text-xs gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
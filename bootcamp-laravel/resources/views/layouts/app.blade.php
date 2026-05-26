<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GadgetShop')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    <nav class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-indigo-600">GadgetShop</a>
            <div class="space-x-4">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-indigo-600 font-medium">Produk</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-white border-t py-6 text-center text-gray-500 text-sm mt-auto">
        &copy; {{ date('Y') }} GadgetShop. Semua hak dilindungi.
    </footer>

</body>
</html>
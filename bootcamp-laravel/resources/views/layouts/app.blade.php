<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GadgetShop')</title>
    <!-- Tailwind CSS CDN dengan script yang lebih stabil -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-indigo-600">GadgetShop</a>
            <div class="space-x-4">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-indigo-600 font-medium">Produk</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t py-6 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} GadgetShop. Semua hak dilindungi.
    </footer>

</body>
</html>
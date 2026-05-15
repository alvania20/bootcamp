<?php
/**
 * HALAMAN DASHBOARD ADMIN
 * Menampilkan ringkasan data dari database: ecommerce_db
 * Fitur: Statistik, Tabel Inventaris, dan Navigasi Cepat
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "ecommerce_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 1. Ambil Statistik Ringkas
$res_total_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$total_produk = mysqli_fetch_assoc($res_total_produk)['total'];

$res_total_stok = mysqli_query($conn, "SELECT SUM(stok) as total_stok FROM products");
$total_stok = mysqli_fetch_assoc($res_total_stok)['total_stok'] ?? 0;

$res_kategori = mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) as total_kat FROM products");
$total_kategori = mysqli_fetch_assoc($res_kategori)['total_kat'];

// 2. Ambil Semua Data Produk untuk Tabel
$query_produk = "SELECT * FROM products ORDER BY id DESC";
$result_produk = mysqli_query($conn, $query_produk);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white p-6 hidden md:block">
        <div class="mb-10">
            <h1 class="text-2xl font-extrabold tracking-tighter italic text-indigo-400">ADMIN.SHOP</h1>
        </div>
        <nav class="space-y-4">
            <a href="dashboard_admin.php" class="flex items-center gap-3 p-3 bg-indigo-600 rounded-xl font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="ecommerce_view.php" class="flex items-center gap-3 p-3 text-slate-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Lihat Toko
            </a>
            <a href="input_produk.php" class="flex items-center gap-3 p-3 text-slate-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                Tambah Produk
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-10">
        <!-- Header -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900">Ringkasan Produk</h2>
                <p class="text-slate-500 font-medium">Selamat datang kembali, Admin!</p>
            </div>
            <div class="flex gap-4">
                <a href="input_produk.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Produk Baru
                </a>
            </div>
        </header>

        <!-- Alert Notifikasi -->
        <?php if(isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
        <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-2xl font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            Produk berhasil dihapus!
        </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <p class="text-slate-500 text-sm font-bold uppercase tracking-wider">Total Jenis Produk</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1"><?php echo $total_produk; ?></h3>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-slate-500 text-sm font-bold uppercase tracking-wider">Total Stok Tersedia</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1"><?php echo number_format($total_stok); ?></h3>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h.01M11 11h.01M11 15h.01M15 7h.01M15 11h.01M15 15h.01"></path></svg>
                </div>
                <p class="text-slate-500 text-sm font-bold uppercase tracking-wider">Kategori Aktif</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1"><?php echo $total_kategori; ?></h3>
            </div>
        </div>

        <!-- Tabel Inventaris -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h3 class="font-extrabold text-xl">Daftar Inventaris</h3>
                <span class="px-4 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold uppercase tracking-widest">Update Otomatis</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-400 text-[11px] uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4 text-right">Harga</th>
                            <th class="px-6 py-4 text-center">Stok</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php while($row = mysqli_fetch_assoc($result_produk)): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden border border-slate-200">
                                        <?php if(!empty($row['gambar'])): ?>
                                            <img src="img/<?php echo $row['gambar']; ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path></svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 leading-tight"><?php echo $row['nama_produk']; ?></p>
                                        <p class="text-xs text-slate-400 mt-0.5 line-clamp-1 max-w-[200px]"><?php echo $row['deskripsi']; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold uppercase">
                                    <?php echo $row['kategori']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-extrabold text-slate-700">
                                Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold <?php echo ($row['stok'] < 5) ? 'text-red-500' : 'text-slate-600'; ?>">
                                    <?php echo $row['stok']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="edit_produk.php?id=<?php echo $row['id']; ?>" class="p-2 bg-slate-100 text-slate-600 hover:bg-indigo-600 hover:text-white rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <!-- Link diarahkan ke dashboard_admin.php sendiri -->
                                    <a href="dashboard_admin.php?action=delete&id=<?php echo $row['id']; ?>" 
                                       onclick="return confirm('Hapus produk ini?')"
                                       class="p-2 bg-slate-100 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>

                        <?php if(mysqli_num_rows($result_produk) == 0): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center text-slate-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2M9 19h6"></path></svg>
                                    <p class="font-bold">Belum ada data produk.</p>
                                    <a href="input_produk.php" class="text-indigo-600 text-sm hover:underline mt-2">Tambah sekarang</a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Small -->
        <footer class="mt-10 text-center text-slate-400 text-xs font-medium pb-10 uppercase tracking-[0.2em]">
            &copy; <?php echo date('Y'); ?> Panel Admin E-Commerce &bull; Dashboard V.1.0
        </footer>
    </main>
</div>

</body>
</html>
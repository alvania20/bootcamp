<?php
/**
 * TUGAS:
 * 1. Koneksi PHP ke MySQL
 * 2. Tampilkan data dengan looping PHP
 * 3. Fitur filter kategori yang responsif
 * 4. Penanganan Gambar Produk (Folder: img/)
 * 5. CRUD: Proses Hapus Produk & Sinkronisasi File
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "ecommerce_db";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$message = "";

// PROSES CRUD: DELETE (Hapus Produk)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Ambil nama gambar untuk dihapus dari folder fisik
    $res = mysqli_query($conn, "SELECT gambar FROM products WHERE id = $id");
    $data = mysqli_fetch_assoc($res);
    
    if ($data) {
        $path = "img/" . $data['gambar'];
        if (!empty($data['gambar']) && file_exists($path)) {
            unlink($path); 
        }
        
        $delete = mysqli_query($conn, "DELETE FROM products WHERE id = $id");
        if ($delete) {
            $message = "Produk berhasil dihapus dari katalog.";
        }
    }
}

// Filter Kategori
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$sql_categories = "SELECT DISTINCT kategori FROM products";
$result_categories = mysqli_query($conn, $sql_categories);

// Ambil Data Produk (Terbaru di atas)
if ($filter_kategori != '') {
    $sql = "SELECT * FROM products WHERE kategori = '" . mysqli_real_escape_string($conn, $filter_kategori) . "' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM products ORDER BY id DESC";
}
$result_products = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GadgetShop - Katalog Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .product-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .product-card:hover {
            transform: translateY(-8px);
        }
        .image-zoom img {
            transition: transform 0.6s ease;
        }
        .product-card:hover .image-zoom img {
            transform: scale(1.1);
        }
        .action-overlay {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        .product-card:hover .action-overlay {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900">

<div class="max-w-7xl mx-auto px-6 py-12">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900">Katalog <span class="text-indigo-600">Terbaru</span></h1>
            <p class="text-slate-500 mt-2 text-lg">Kelola koleksi gadget dan aksesoris terbaik Anda.</p>
        </div>
        <a href="input_produk.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk
        </a>
    </div>

    <!-- Alert Message -->
    <?php if ($message): ?>
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between animate-pulse">
            <span class="font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <?php echo $message; ?>
            </span>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Filter Tab -->
    <div class="flex flex-wrap items-center gap-4 mb-10 pb-6 border-b border-slate-200">
        <span class="text-sm font-bold text-slate-400 uppercase tracking-widest mr-2">Filter:</span>
        <a href="ecommerce_view.php" class="<?php echo $filter_kategori == '' ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 border border-slate-200'; ?> px-6 py-2.5 rounded-full text-sm font-bold transition hover:shadow-md">Semua</a>
        <?php while($cat = mysqli_fetch_assoc($result_categories)): ?>
            <a href="?kategori=<?php echo urlencode($cat['kategori']); ?>" 
               class="<?php echo $filter_kategori == $cat['kategori'] ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 border border-slate-200'; ?> px-6 py-2.5 rounded-full text-sm font-bold transition hover:shadow-md">
                <?php echo htmlspecialchars($cat['kategori']); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- Grid Produk -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php if (mysqli_num_rows($result_products) > 0): ?>
            <?php while($product = mysqli_fetch_assoc($result_products)): ?>
                <div class="product-card bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm flex flex-col">
                    
                    <!-- Media Area -->
                    <div class="relative h-64 overflow-hidden image-zoom bg-slate-100">
                        <?php 
                        $img = $product['gambar'];
                        $path = "img/" . $img;
                        if (!empty($img) && file_exists($path)): ?>
                            <img src="<?php echo $path; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center bg-indigo-50 text-indigo-300">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest">No Image</span>
                            </div>
                        <?php endif; ?>

                        <!-- Kategori Badge -->
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-xl text-[10px] font-extrabold text-indigo-600 shadow-sm uppercase tracking-wider">
                                <?php echo htmlspecialchars($product['kategori']); ?>
                            </span>
                        </div>

                        <!-- Admin Actions (Hover) -->
                        <div class="action-overlay absolute inset-0 flex items-center justify-center gap-3 z-20">
                            <a href="edit_produk.php?id=<?php echo $product['id']; ?>" class="bg-indigo-600 text-white p-3 rounded-2xl hover:scale-110 transition shadow-lg shadow-indigo-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <a href="?action=delete&id=<?php echo $product['id']; ?>" onclick="return confirm('Hapus produk ini?')" class="bg-rose-500 text-white p-3 rounded-2xl hover:scale-110 transition shadow-lg shadow-rose-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-lg font-bold text-slate-800 mb-1 truncate">
                            <?php echo htmlspecialchars($product['nama_produk']); ?>
                        </h3>
                        <div class="text-2xl font-extrabold text-indigo-600 mb-4">
                            Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?>
                        </div>
                        <p class="text-slate-500 text-sm line-clamp-2 leading-relaxed mb-6">
                            <?php echo htmlspecialchars($product['deskripsi']); ?>
                        </p>
                        
                        <!-- Footer Card (Beli Sekarang & Icon) -->
                        <div class="mt-auto pt-5 border-t border-slate-50 flex items-center justify-between">
                            <button class="flex items-center text-sm font-bold text-slate-900 group">
                                Beli Sekarang
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </button>
                            <div class="flex gap-2">
                                <button class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-rose-50 hover:text-rose-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </button>
                                <button class="p-2.5 bg-slate-900 text-white rounded-xl hover:bg-indigo-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-full py-32 bg-white rounded-[3rem] border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center px-6">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 8-8-8"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Tidak ada produk ditemukan</h3>
                <p class="text-slate-500 mt-2">Coba gunakan filter lain atau tambahkan produk baru.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php mysqli_close($conn); ?>
</body>
</html>
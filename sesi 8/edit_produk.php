<?php
/**
 * HALAMAN EDIT PRODUK
 * Sinkronisasi dengan database: ecommerce_db
 * Tabel: products
 * Fitur: Update data, penggantian gambar, & Hapus Produk
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "ecommerce_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 1. Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: ecommerce_view.php");
    exit;
}

$id = intval($_GET['id']);

// 2. Ambil data produk yang akan diedit
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='ecommerce_view.php';</script>";
    exit;
}

// 3. Proses Hapus Data (Jika tombol hapus ditekan)
if (isset($_POST['delete_product'])) {
    // Hapus file gambar fisik jika ada
    if (!empty($product['gambar']) && file_exists("img/" . $product['gambar'])) {
        unlink("img/" . $product['gambar']);
    }

    $delete_query = "DELETE FROM products WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Produk berhasil dihapus!'); window.location='ecommerce_view.php';</script>";
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// 4. Proses Update Data
if (isset($_POST['update'])) {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga     = $_POST['harga'];
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stok      = $_POST['stok'];
    
    // Inisialisasi gambar lama
    $filename = $product['gambar'];

    // Cek apakah ada file gambar baru yang diupload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $new_filename = time() . "_" . $_FILES['gambar']['name']; // Menghindari nama duplikat
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $folder   = "img/" . $new_filename;

        // Hapus gambar lama jika ada di folder img
        if (!empty($product['gambar']) && file_exists("img/" . $product['gambar'])) {
            unlink("img/" . $product['gambar']);
        }

        // Upload gambar baru
        if (move_uploaded_file($tmp_name, $folder)) {
            $filename = $new_filename;
        }
    }

    // Query Update
    $query = "UPDATE products SET 
                nama_produk = '$nama', 
                harga = '$harga', 
                kategori = '$kategori', 
                deskripsi = '$deskripsi', 
                stok = '$stok', 
                gambar = '$filename' 
              WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Produk berhasil diperbarui!'); window.location='ecommerce_view.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - <?php echo htmlspecialchars($product['nama_produk']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-xl w-full bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden border border-slate-100">
        <!-- Header Section -->
        <div class="bg-indigo-600 p-10 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-3xl font-extrabold tracking-tight">Edit Produk</h2>
                <p class="text-indigo-100 mt-2 opacity-90">Perbarui informasi katalog gadget Anda secara akurat.</p>
            </div>
            <!-- Decorative Circle -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="p-10 space-y-6">
            <!-- Nama Produk -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Produk</label>
                <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($product['nama_produk']); ?>" 
                       class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all" required>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all appearance-none cursor-pointer">
                        <option value="laptop" <?php echo ($product['kategori'] == 'laptop') ? 'selected' : ''; ?>>Laptop</option>
                        <option value="smartphone" <?php echo ($product['kategori'] == 'smartphone') ? 'selected' : ''; ?>>Smartphone</option>
                        <option value="aksesoris" <?php echo ($product['kategori'] == 'aksesoris') ? 'selected' : ''; ?>>Aksesoris</option>
                    </select>
                </div>
                <!-- Stok -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Stok Unit</label>
                    <input type="number" name="stok" value="<?php echo $product['stok']; ?>" 
                           class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all" required>
                </div>
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Harga (Rp)</label>
                <input type="number" name="harga" value="<?php echo intval($product['harga']); ?>" 
                       class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all" required>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="3" 
                          class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all leading-relaxed" required><?php echo htmlspecialchars($product['deskripsi']); ?></textarea>
            </div>

            <!-- Bagian Gambar -->
            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 flex flex-col md:flex-row gap-6 items-center">
                <!-- Preview Gambar Sekarang -->
                <div class="w-24 h-24 rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm flex-shrink-0">
                    <?php if (!empty($product['gambar']) && file_exists("img/" . $product['gambar'])): ?>
                        <img src="img/<?php echo $product['gambar']; ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex-grow text-center md:text-left">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Ganti Gambar (Opsional)</label>
                    <input type="file" name="gambar" accept="image/*" 
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-2 italic font-medium">*Biarkan kosong jika tidak ingin mengganti gambar.</p>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 flex flex-col gap-3">
                <button type="submit" name="update" 
                        class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-extrabold py-4 rounded-2xl shadow-xl shadow-slate-200 transition-all transform active:scale-[0.98]">
                    Simpan Perubahan
                </button>
                
                <button type="submit" name="delete_product" 
                        onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini? Data yang dihapus tidak bisa dikembalikan.')"
                        class="w-full bg-white border-2 border-red-500 text-red-500 hover:bg-red-50 font-extrabold py-4 rounded-2xl transition-all transform active:scale-[0.98]">
                    Hapus Produk Ini
                </button>

                <a href="ecommerce_view.php" 
                   class="block text-center text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors py-2">
                    Batal dan Kembali
                </a>
            </div>
        </form>
    </div>

</body>
</html>
<?php mysqli_close($conn); ?>
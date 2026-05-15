<?php
/**
 * HALAMAN EDIT PRODUK
 * Fitur: Update data, Kategori Baru, & Navigasi Beranda Admin
 * Validasi: Foto bersifat Opsional
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "ecommerce_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (!isset($_GET['id'])) {
    header("Location: dashboard_admin.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil data produk
$result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='dashboard_admin.php';</script>";
    exit;
}

// Ambil data kategori unik dari tabel products untuk dropdown
$res_kategori = mysqli_query($conn, "SELECT DISTINCT kategori FROM products WHERE kategori != '' ORDER BY kategori ASC");

// Proses Update
if (isset($_POST['update'])) {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga     = $_POST['harga'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stok      = $_POST['stok'];

    // Cek apakah menggunakan kategori lama atau baru
    if ($_POST['kategori'] == 'BARU' && !empty($_POST['kategori_baru'])) {
        $kategori = mysqli_real_escape_string($conn, $_POST['kategori_baru']);
    } else {
        $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    }

    /** * VALIDASI FOTO (OPSIONAL)
     * Jika ada upload baru, gunakan foto baru.
     * Jika tidak ada upload, tetap gunakan foto yang sudah ada di database.
     */
    $filename = $product['gambar']; 
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        if (!is_dir('img')) { mkdir('img', 0777, true); }
        move_uploaded_file($tmp_name, "img/" . $filename);
    }

    $query = "UPDATE products SET 
                nama = '$nama', 
                harga = '$harga', 
                kategori = '$kategori', 
                deskripsi = '$deskripsi', 
                stok = '$stok',
                gambar = '$filename'
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='dashboard_admin.php';</script>";
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
    <title>Edit Produk - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-xl w-full bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200 p-8 md:p-12 border border-slate-100">
        
        <div class="flex items-center justify-between mb-10">
            <div>
                <span class="inline-block px-4 py-1 bg-indigo-100 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-widest mb-2">Edit Mode</span>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?php echo isset($product['nama']) ? htmlspecialchars($product['nama']) : 'Produk'; ?></h1>
            </div>
            <a href="dashboard_admin.php" class="p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-900 hover:text-white transition-all group shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </a>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <div class="space-y-2">
                <label class="text-xs font-black uppercase text-slate-400 ml-1">Nama Produk</label>
                <input type="text" name="nama_produk" value="<?php echo isset($product['nama']) ? htmlspecialchars($product['nama']) : ''; ?>" required 
                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase text-slate-400 ml-1">Harga (Rp)</label>
                    <input type="number" name="harga" value="<?php echo isset($product['harga']) ? $product['harga'] : 0; ?>" required 
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium">
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase text-slate-400 ml-1">Stok</label>
                    <input type="number" name="stok" value="<?php echo isset($product['stok']) ? $product['stok'] : 0; ?>" required 
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-black uppercase text-slate-400 ml-1">Kategori Produk</label>
                <select name="kategori" onchange="toggleKategoriBaru(this.value)" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium appearance-none">
                    <?php while($cat = mysqli_fetch_assoc($res_kategori)): ?>
                        <option value="<?php echo $cat['kategori']; ?>" <?php echo (isset($product['kategori']) && $product['kategori'] == $cat['kategori']) ? 'selected' : ''; ?>>
                            <?php echo $cat['kategori']; ?>
                        </option>
                    <?php endwhile; ?>
                    <option value="BARU" class="font-bold text-indigo-600">+ Tambah Kategori Baru</option>
                </select>
            </div>

            <div id="inputKategoriBaru" class="space-y-2 hidden">
                <label class="text-xs font-black uppercase text-indigo-600 ml-1">Nama Kategori Baru</label>
                <input type="text" name="kategori_baru" id="fieldKategoriBaru" class="w-full px-6 py-4 bg-indigo-50 border-2 border-indigo-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-semibold" placeholder="Ketik kategori baru di sini...">
            </div>

            <div class="space-y-2">
                <label class="text-xs font-black uppercase text-slate-400 ml-1">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="4" 
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium"><?php echo isset($product['deskripsi']) ? htmlspecialchars($product['deskripsi']) : ''; ?></textarea>
            </div>

            <!-- Bagian Gambar (Dibuat Opsional) -->
            <div class="p-6 bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-200">
                <label class="block text-center text-xs font-black uppercase text-slate-400 mb-4">Ganti Gambar (Opsional)</label>
                <div class="flex flex-col items-center gap-4">
                    <?php if(!empty($product['gambar'])): ?>
                        <div class="flex flex-col items-center">
                            <span class="text-[10px] text-slate-400 mb-2 uppercase font-bold">Foto Saat Ini:</span>
                            <img src="img/<?php echo $product['gambar']; ?>" class="w-24 h-24 object-cover rounded-xl shadow-md border-4 border-white mb-2">
                        </div>
                    <?php else: ?>
                        <div class="p-4 bg-slate-100 rounded-xl text-slate-400 text-[10px] font-bold uppercase">Tidak ada foto</div>
                    <?php endif; ?>
                    <input type="file" name="gambar" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-900 file:text-white hover:file:bg-indigo-600 cursor-pointer">
                </div>
            </div>

            <div class="pt-6 flex flex-col gap-3">
                <button type="submit" name="update" 
                        class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-extrabold py-5 rounded-3xl shadow-xl shadow-slate-200 transition-all transform active:scale-95">
                    Simpan Perubahan
                </button>
                <a href="dashboard_admin.php" class="block text-center w-full bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-all">
                    Kembali ke Beranda Admin
                </a>
            </div>
        </form>
    </div>

    <script>
        function toggleKategoriBaru(val) {
            const inputDiv = document.getElementById('inputKategoriBaru');
            const inputField = document.getElementById('fieldKategoriBaru');
            if (val === 'BARU') {
                inputDiv.classList.remove('hidden');
                inputField.setAttribute('required', 'true');
                inputField.focus();
            } else {
                inputDiv.classList.add('hidden');
                inputField.removeAttribute('required');
            }
        }
    </script>
</body>
</html>
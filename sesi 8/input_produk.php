<?php
/**
 * HALAMAN INPUT PRODUK
 * Disesuaikan dengan database: ecommerce_db
 * Tabel: products
 * Fitur: Upload gambar opsional (Bisa pakai foto atau tidak)
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "ecommerce_db"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses Logika Input
if (isset($_POST['submit'])) {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga     = $_POST['harga'];
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stok      = $_POST['stok'] ?? 0;
    
    // Inisialisasi variabel gambar
    $filename = "";

    // Cek apakah ada file yang diupload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $folder   = "img/" . $filename;

        // Buat folder img jika belum ada
        if (!is_dir('img')) {
            mkdir('img', 0777, true);
        }

        // Pindahkan file ke folder img/
        if (!move_uploaded_file($tmp_name, $folder)) {
            $filename = ""; // Reset jika gagal upload
            echo "<script>alert('Gagal mengunggah gambar, produk akan disimpan tanpa foto.');</script>";
        }
    }

    // Query: Jika $filename kosong, maka kolom gambar akan berisi string kosong (atau NULL tergantung struktur DB)
    $query = "INSERT INTO products (nama_produk, harga, kategori, deskripsi, gambar, stok) 
              VALUES ('$nama', '$harga', '$kategori', '$deskripsi', '$filename', '$stok')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='ecommerce_view.php';</script>";
    } else {
        echo "Error Database: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - GadgetShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-indigo-600 p-8 text-white">
            <h2 class="text-2xl font-bold">Tambah Produk Baru</h2>
            <p class="text-indigo-100 mt-1">Lengkapi detail produk. Foto bersifat opsional.</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="nama_produk" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="Contoh: iPhone 15 Pro" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition bg-white">
                        <option value="laptop">Laptop</option>
                        <option value="smartphone">Smartphone</option>
                        <option value="aksesoris">Aksesoris</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stok</label>
                    <input type="number" name="stok" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition" value="10" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="harga" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Contoh: 15000000" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Jelaskan keunggulan produk..." required></textarea>
            </div>

            <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
                <label class="block text-sm font-bold text-indigo-900 mb-2">Gambar Produk (Opsional)</label>
                <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor:pointer">
                <p class="text-[11px] text-indigo-400 mt-2 italic">*Kosongkan jika tidak ingin menyertakan foto.</p>
            </div>

            <div class="pt-4 space-y-3">
                <button type="submit" name="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-200 transition-all transform active:scale-95">
                    Simpan Produk
                </button>
                <a href="ecommerce_view.php" class="block text-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition">
                    ← Kembali ke Katalog
                </a>
            </div>
        </form>
    </div>

</body>
</html>
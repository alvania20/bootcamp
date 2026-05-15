<?php
/**
 * HALAMAN INPUT PRODUK
 * Fitur: Upload gambar (Opsional), Kategori Dinamis/Baru, & Navigasi Admin
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "ecommerce_db"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data kategori unik dari tabel products untuk dropdown
$res_kategori = mysqli_query($conn, "SELECT DISTINCT kategori FROM products WHERE kategori != '' ORDER BY kategori ASC");

// Proses Logika Input
if (isset($_POST['submit'])) {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga     = $_POST['harga'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stok      = $_POST['stok'] ?? 0;
    
    // Cek apakah menggunakan kategori lama atau baru
    if ($_POST['kategori'] == 'BARU' && !empty($_POST['kategori_baru'])) {
        $kategori = mysqli_real_escape_string($conn, $_POST['kategori_baru']);
    } else {
        $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    }

    $filename = ""; // Default kosong jika tidak ada foto
    
    // Validasi Foto: Jika ada file yang diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $target_dir = "uploads/";
        
        // Buat folder jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $filename = time() . "_" . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $filename;
        
        // Pindahkan file
        if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $filename = ""; // Reset jika gagal upload
        }
    }

    // Perbaikan: Kolom database adalah 'nama_produk', bukan 'nama'
    $sql = "INSERT INTO products (nama_produk, harga, deskripsi, gambar, kategori, stok) 
            VALUES ('$nama', '$harga', '$deskripsi', '$filename', '$kategori', '$stok')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='dashboard_admin.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen pb-12">
    <div class="max-w-xl mx-auto px-6 pt-10">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tambah Produk Baru</h1>
            <p class="text-slate-500 text-sm mt-1">Lengkapi detail produk di bawah ini.</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-[32px] shadow-sm border border-slate-100 space-y-6">
            <!-- Nama Produk -->
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Nama Produk</label>
                <input type="text" name="nama_produk" required placeholder="Contoh: iPhone 15 Pro" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-slate-900 focus:ring-2 focus:ring-indigo-500 transition-all font-medium">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Harga -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Harga (Rp)</label>
                    <input type="number" name="harga" required placeholder="0" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-slate-900 focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-lg">
                </div>
                <!-- Stok -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Stok</label>
                    <input type="number" name="stok" required placeholder="0" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-slate-900 focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-lg">
                </div>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Kategori</label>
                <select name="kategori" onchange="toggleKategoriBaru(this.value)" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-slate-900 focus:ring-2 focus:ring-indigo-500 transition-all font-medium appearance-none">
                    <option value="">Pilih Kategori</option>
                    <?php while($row = mysqli_fetch_assoc($res_kategori)): ?>
                        <option value="<?php echo $row['kategori']; ?>"><?php echo $row['kategori']; ?></option>
                    <?php endwhile; ?>
                    <option value="BARU" class="font-bold text-indigo-600">+ Buat Kategori Baru</option>
                </select>
            </div>

            <!-- Input Kategori Baru (Hidden by default) -->
            <div id="inputKategoriBaru" class="hidden animate-pulse">
                <label class="block text-xs font-black uppercase tracking-widest text-indigo-500 mb-2">Nama Kategori Baru</label>
                <input type="text" name="kategori_baru" id="fieldKategoriBaru" placeholder="Ketik kategori baru..." class="w-full bg-indigo-50 border-2 border-indigo-100 rounded-2xl p-4 text-indigo-900 focus:ring-0 focus:border-indigo-400 transition-all font-medium">
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="4" placeholder="Jelaskan fitur dan spesifikasi..." class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-slate-900 focus:ring-2 focus:ring-indigo-500 transition-all font-medium"></textarea>
            </div>

            <!-- Upload Foto -->
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Foto Produk (Opsional)</label>
                <div class="relative group border-2 border-dashed border-slate-100 p-6 rounded-2xl hover:border-indigo-400 transition-all text-center">
                   <input type="file" name="gambar" accept="image/*" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-slate-900 file:text-white hover:file:bg-indigo-600 cursor-pointer">
                </div>
            </div>

            <div class="pt-6 space-y-3">
                <button type="submit" name="submit" class="w-full bg-indigo-600 hover:bg-slate-900 text-white font-extrabold py-5 rounded-3xl shadow-xl shadow-indigo-100 transition-all transform active:scale-95">
                    Tambahkan Produk Ke Katalog
                </button>
                <a href="dashboard_admin.php" class="block text-center w-full bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl hover:bg-slate-200 transition-all">
                    Batalkan & Kembali
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
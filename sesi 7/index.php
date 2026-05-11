<?php
/**
 * Tugas Dasar PHP, Form Input, dan Validasi
 * * File ini menangani input form, validasi data, dan menampilkan hasil.
 */

// 1. Deklarasi Variabel Awal
$pesan = "";
$tipe_pesan = ""; // 'error' atau 'sukses'

// 2. Mengecek apakah form telah disubmit menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Mengambil data dari form menggunakan operator assignment
    // htmlspecialchars digunakan untuk keamanan (mencegah XSS)
    $nama_produk = htmlspecialchars($_POST['nama_produk']);
    $harga_produk = $_POST['harga_produk'];
    $deskripsi = htmlspecialchars($_POST['deskripsi']);

    // 3. Tugas Validasi: Menggunakan if-else untuk mengecek kekosongan data
    if (empty($nama_produk) || empty($harga_produk) || empty($deskripsi)) {
        $pesan = "Semua field harus diisi! Tidak boleh ada yang kosong.";
        $tipe_pesan = "error";
    } elseif (!is_numeric($harga_produk) || $harga_produk <= 0) {
        // Validasi tambahan: Harga harus angka dan lebih dari 0
        $pesan = "Harga harus berupa angka yang valid.";
        $tipe_pesan = "error";
    } else {
        // Jika validasi lolos
        // Di sini biasanya tempat kode untuk INSERT INTO database
        $pesan = "Produk '<strong>$nama_produk</strong>' berhasil divalidasi dan siap disimpan ke database!";
        $tipe_pesan = "sukses";
        
        // Reset variabel agar form kosong kembali setelah sukses (opsional)
        $nama_produk = $harga_produk = $deskripsi = "";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Tugas PHP</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f9; padding: 20px; }
        .container { max-width: 500px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin: auto; }
        h2 { color: #333; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #218838; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .sukses { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Produk Baru</h2>

    <!-- Menampilkan Pesan Validasi -->
    <?php if ($pesan): ?>
        <div class="alert <?php echo $tipe_pesan; ?>">
            <?php echo $pesan; ?>
        </div>
    <?php endif; ?>

    <!-- 2. Tugas Form Input -->
    <form action="" method="POST">
        <div class="form-group">
            <label for="nama_produk">Nama Produk:</label>
            <input type="text" id="nama_produk" name="nama_produk" value="<?php echo isset($nama_produk) ? $nama_produk : ''; ?>">
        </div>

        <div class="form-group">
            <label for="harga_produk">Harga Produk (Rp):</label>
            <input type="number" id="harga_produk" name="harga_produk" value="<?php echo isset($harga_produk) ? $harga_produk : ''; ?>">
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi Produk:</label>
            <textarea id="deskripsi" name="deskripsi" rows="4"><?php echo isset($deskripsi) ? $deskripsi : ''; ?></textarea>
        </div>

        <button type="submit">Simpan Produk</button>
    </form>
</div>

</body>
</html>
<?php
session_start();

/**
 * TUGAS INTEGRASI:
 * 1. Sistem Session Keranjang Belanja
 * 2. Update Qty (+/-) di Keranjang
 * 3. Checkout WhatsApp dengan Detail Waktu (H:i:s)
 * 4. Desain UI Minimalis & Modern
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "ecommerce_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Konfigurasi Admin
$admin_wa = "6285187890042"; // Ganti dengan nomor WhatsApp Admin Anda

// --- LOGIKA KERANJANG (CART) ---
if (isset($_POST['add_to_cart'])) {
    $p_id = $_POST['product_id'];
    $p_name = $_POST['product_name'];
    $p_price = $_POST['product_price'];
    $p_image = $_POST['product_image'];

    $item_array = [
        'id' => $p_id,
        'nama' => $p_name,
        'harga' => $p_price,
        'gambar' => $p_image,
        'qty' => 1
    ];

    if (isset($_SESSION['cart'])) {
        $item_ids = array_column($_SESSION['cart'], 'id');
        if (!in_array($p_id, $item_ids)) {
            $_SESSION['cart'][] = $item_array;
        } else {
            foreach ($_SESSION['cart'] as $key => $val) {
                if ($val['id'] == $p_id) {
                    $_SESSION['cart'][$key]['qty'] += 1;
                }
            }
        }
    } else {
        $_SESSION['cart'][] = $item_array;
    }
    header("Location: ecommerce_view.php");
    exit();
}

// Update Jumlah (+ atau -)
if (isset($_GET['update_qty']) && isset($_GET['id'])) {
    $id_update = $_GET['id'];
    $action = $_GET['update_qty'];
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $val) {
            if ($val['id'] == $id_update) {
                if ($action == 'plus') {
                    $_SESSION['cart'][$key]['qty'] += 1;
                } else if ($action == 'minus' && $val['qty'] > 1) {
                    $_SESSION['cart'][$key]['qty'] -= 1;
                }
            }
        }
    }
    header("Location: ecommerce_view.php");
    exit();
}

// Hapus Item
if (isset($_GET['remove_cart'])) {
    $id_remove = $_GET['remove_cart'];
    foreach ($_SESSION['cart'] as $key => $val) {
        if ($val['id'] == $id_remove) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); 
        }
    }
    header("Location: ecommerce_view.php");
    exit();
}

// Filter Data
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$sql_categories = "SELECT DISTINCT kategori FROM products";
$result_categories = mysqli_query($conn, $sql_categories);

$sql = "SELECT * FROM products " . ($filter_kategori != '' ? "WHERE kategori = '".mysqli_real_escape_string($conn, $filter_kategori)."'" : "") . " ORDER BY id DESC";
$result_products = mysqli_query($conn, $sql);

$cart_count = 0;
if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $item) { $cart_count += $item['qty']; }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GadgetShop - Katalog Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfd; }
        .cart-sidebar { transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1); transform: translateX(100%); }
        .cart-sidebar.active { transform: translateX(0); }
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.05); }
    </style>
</head>
<body class="text-slate-900">

<!-- Navigasi Minimalis -->
<nav class="sticky top-0 bg-white/80 backdrop-blur-md z-40 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
        <h1 class="text-2xl font-extrabold tracking-tight">Gadget<span class="text-indigo-600">Store.</span></h1>
        
        <button onclick="toggleCart()" class="relative group p-2">
            <div class="bg-slate-900 text-white p-3 rounded-2xl flex items-center gap-3 transition group-hover:bg-indigo-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span class="font-bold text-sm px-1"><?php echo $cart_count; ?></span>
            </div>
        </button>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Hero Section -->
    <div class="mb-12">
        <h2 class="text-4xl font-extrabold text-slate-900">Koleksi <span class="text-indigo-600">Gadget.</span></h2>
        <p class="text-slate-500 mt-2">Temukan perangkat terbaik untuk menunjang produktivitas Anda.</p>
    </div>

    <!-- Filter Kategori -->
    <div class="flex flex-wrap items-center gap-3 mb-10">
        <a href="ecommerce_view.php" class="<?php echo $filter_kategori == '' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-slate-600 border border-slate-100'; ?> px-6 py-2.5 rounded-2xl text-xs font-bold uppercase tracking-wider transition">Semua</a>
        <?php while($cat = mysqli_fetch_assoc($result_categories)): ?>
            <a href="?kategori=<?php echo urlencode($cat['kategori']); ?>" class="<?php echo $filter_kategori == $cat['kategori'] ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-slate-600 border border-slate-100'; ?> px-6 py-2.5 rounded-2xl text-xs font-bold uppercase tracking-wider transition">
                <?php echo htmlspecialchars($cat['kategori']); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- Grid Produk -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php while($product = mysqli_fetch_assoc($result_products)): ?>
            <div class="product-card bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden flex flex-col">
                <div class="h-64 bg-slate-50 relative overflow-hidden group">
                    <?php if (!empty($product['gambar'])): ?>
                        <img src="img/<?php echo $product['gambar']; ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-slate-300 italic text-xs">Tanpa Gambar</div>
                    <?php endif; ?>
                    <div class="absolute top-5 left-5">
                        <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-xl text-[10px] font-black text-indigo-600 uppercase tracking-tighter shadow-sm border border-white/20">
                            <?php echo $product['kategori']; ?>
                        </span>
                    </div>
                </div>
                
                <div class="p-8 flex flex-col flex-grow">
                    <h3 class="font-bold text-slate-800 text-lg mb-1 leading-tight"><?php echo $product['nama_produk']; ?></h3>
                    <div class="text-indigo-600 font-black text-xl mb-6">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></div>
                    
                    <form method="POST" class="mt-auto">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $product['nama_produk']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $product['harga']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $product['gambar']; ?>">
                        <button type="submit" name="add_to_cart" class="w-full bg-slate-50 hover:bg-indigo-600 hover:text-white text-slate-900 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center justify-center gap-3 group/btn">
                            <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Tambah Keranjang
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- SIDEBAR KERANJANG -->
<div id="cartSidebar" class="cart-sidebar fixed inset-y-0 right-0 w-full md:w-[480px] bg-white shadow-2xl z-[60] flex flex-col border-l border-slate-50">
    <div class="p-8 flex justify-between items-center border-b border-slate-50">
        <div>
            <h2 class="text-xl font-extrabold">Keranjang <span class="text-indigo-600">Belanja</span></h2>
            <p class="text-xs text-slate-400 font-medium">Total <?php echo $cart_count; ?> produk terpilih</p>
        </div>
        <button onclick="toggleCart()" class="p-2 hover:bg-slate-50 rounded-full transition text-slate-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div class="flex-grow overflow-y-auto p-8 space-y-6">
        <?php 
        $total_bayar = 0;
        if (!empty($_SESSION['cart'])): 
            foreach ($_SESSION['cart'] as $item):
                $subtotal = $item['harga'] * $item['qty'];
                $total_bayar += $subtotal;
        ?>
            <div class="flex gap-5 items-center group">
                <div class="w-20 h-20 rounded-[1.5rem] bg-slate-50 overflow-hidden border border-slate-100 flex-shrink-0">
                    <img src="img/<?php echo $item['gambar']; ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/150'">
                </div>
                <div class="flex-grow">
                    <h4 class="font-bold text-slate-800 text-sm mb-2"><?php echo $item['nama']; ?></h4>
                    <div class="flex items-center justify-between">
                        <!-- Sistem Edit Qty (+/-) -->
                        <div class="flex items-center bg-slate-50 rounded-xl px-1">
                            <a href="?update_qty=minus&id=<?php echo $item['id']; ?>" class="w-8 h-8 flex items-center justify-center text-indigo-600 font-bold hover:bg-white rounded-lg transition">-</a>
                            <span class="w-10 text-center text-xs font-extrabold text-slate-900"><?php echo $item['qty']; ?></span>
                            <a href="?update_qty=plus&id=<?php echo $item['id']; ?>" class="w-8 h-8 flex items-center justify-center text-indigo-600 font-bold hover:bg-white rounded-lg transition">+</a>
                        </div>
                        <span class="font-black text-sm text-slate-900">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                    </div>
                </div>
                <a href="?remove_cart=<?php echo $item['id']; ?>" class="text-slate-300 hover:text-rose-500 transition-colors p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </a>
            </div>
        <?php endforeach; else: ?>
            <div class="flex flex-col items-center justify-center h-full text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <p class="text-slate-400 font-medium">Keranjang Anda kosong.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($total_bayar > 0): ?>
        <div class="p-8 border-t border-slate-50 bg-white space-y-4">
            <div class="flex justify-between items-center mb-4">
                <span class="text-slate-400 font-bold text-sm">Total Estimasi</span>
                <span class="text-2xl font-black text-indigo-600">Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></span>
            </div>
            
            <input type="text" id="cust_name" placeholder="Nama Lengkap" class="w-full p-4 rounded-2xl bg-slate-50 border border-transparent focus:bg-white focus:border-indigo-500 outline-none transition text-sm font-medium">
            
            <button onclick="sendWhatsApp()" class="w-full bg-emerald-500 text-white py-4 rounded-[1.5rem] font-bold shadow-xl shadow-emerald-100 flex items-center justify-center gap-3 hover:bg-emerald-600 transition-all hover:-translate-y-1">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                Pesan Sekarang via WhatsApp
            </button>
        </div>
    <?php endif; ?>
</div>

<div id="backdrop" onclick="toggleCart()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden transition-opacity duration-300"></div>

<script>
    function toggleCart() {
        const sidebar = document.getElementById('cartSidebar');
        const backdrop = document.getElementById('backdrop');
        sidebar.classList.toggle('active');
        backdrop.classList.toggle('hidden');
    }

    function sendWhatsApp() {
        const name = document.getElementById('cust_name').value;
        const adminPhone = "<?php echo $admin_wa; ?>";
        
        const now = new Date();
        const fullTime = now.getHours().toString().padStart(2, '0') + ":" + 
                         now.getMinutes().toString().padStart(2, '0') + ":" + 
                         now.getSeconds().toString().padStart(2, '0');
        const fullDate = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        if (!name) {
            alert("Harap masukkan nama Anda!");
            return;
        }

        let message = "*KONFIRMASI PESANAN - GADGETSTORE*\n";
        message += "------------------------------------------\n";
        message += "👤 *Nama Pembeli:* " + name + "\n";
        message += "📅 *Tanggal:* " + fullDate + "\n";
        message += "⏰ *Waktu:* " + fullTime + " WIB\n";
        message += "------------------------------------------\n\n";
        message += "*Daftar Belanja:*\n";

        <?php if(!empty($_SESSION['cart'])): foreach($_SESSION['cart'] as $item): ?>
            message += "✅ <?php echo $item['nama']; ?> (<?php echo $item['qty']; ?>x)\n   _Rp <?php echo number_format($item['harga'] * $item['qty'], 0, ',', '.'); ?>_\n";
        <?php endforeach; endif; ?>

        message += "\n💰 *TOTAL BAYAR: Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?>*\n";
        message += "------------------------------------------\n";
        message += "Mohon segera diproses ya Min. Terima kasih!";

        const encoded = encodeURIComponent(message);
        window.open(`https://api.whatsapp.com/send?phone=${adminPhone}&text=${encoded}`, '_blank');
    }
</script>

<?php mysqli_close($conn); ?>
</body>
</html>
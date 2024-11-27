<?php
include 'koneksi.php';

// Tambah Pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $catatan = isset($_POST['catatan']) ? $_POST['catatan'] : '';
    $menu_pesanan = $_POST['menu_pesanan'];
    $qty_pesanan = $_POST['qty_pesanan'];

    // Insert ke tabel `pesanan`
    $insert_pesanan = "INSERT INTO pesanan (nama_pelanggan, catatan, status, total_harga) VALUES ('$nama_pelanggan', '$catatan', 'Pending', 0)";
    mysqli_query($koneksi, $insert_pesanan);
    $id_pesanan = mysqli_insert_id($koneksi);

    $total_harga = 0;

    // Insert ke tabel `detail_pesanan`
    foreach ($menu_pesanan as $index => $id_menu) {
        $qty = $qty_pesanan[$index];
        
        // Ambil harga menu
        $result = mysqli_query($koneksi, "SELECT harga, stok FROM menu WHERE id_menu = '$id_menu'");
        $menu = mysqli_fetch_assoc($result);
        $harga = $menu['harga'];
        $stok = $menu['stok'];
        
        // Cek apakah stok cukup
        if ($qty > $stok) {
            echo "Stok tidak mencukupi untuk menu ID: $id_menu";
            exit;
        }
        
        $subtotal = $harga * $qty;
        $total_harga += $subtotal;

        // Insert ke `detail_pesanan`
        mysqli_query($koneksi, "INSERT INTO detail_pesanan (id_pesanan, id_menu, qty, harga) VALUES ('$id_pesanan', '$id_menu', '$qty', '$harga')");

        // Update stok di tabel `menu`
        mysqli_query($koneksi, "UPDATE menu SET stok = stok - $qty WHERE id_menu = '$id_menu'");
    }

    // Update total harga pesanan
    mysqli_query($koneksi, "UPDATE pesanan SET total_harga = '$total_harga' WHERE id_pesanan = '$id_pesanan'");

    header('Location: dashboard_karyawan.php');
    exit;
}

// Update Status Pesanan
if (isset($_GET['selesai'])) {
    $id_pesanan = $_GET['selesai'];
    mysqli_query($koneksi, "UPDATE pesanan SET status = 'Selesai' WHERE id_pesanan = '$id_pesanan'");
    header('Location: dashboard_karyawan.php');
    exit;
}

// Hapus Pesanan
if (isset($_GET['hapus'])) {
    $id_pesanan = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM detail_pesanan WHERE id_pesanan = '$id_pesanan'");
    mysqli_query($koneksi, "DELETE FROM pesanan WHERE id_pesanan = '$id_pesanan'");
    header('Location: dashboard_karyawan.php');
    exit;
}
?>

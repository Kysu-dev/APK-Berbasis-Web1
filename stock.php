<?php
include 'koneksi.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_menu = $_POST['id_menu'] ?? '';
    $jenis_perubahan = $_POST['jenis_perubahan'] ?? '';
    $jumlah = $_POST['jumlah'] ?? 0;
    $keterangan = $_POST['keterangan'] ?? '';

    if (empty($id_menu) || empty($jenis_perubahan) || empty($jumlah)) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        exit;
    }

    // Cek apakah id_menu sudah ada di tabel stok_log
    $cek_query = "SELECT * FROM stok_log WHERE id_menu = '$id_menu'";
    $result = mysqli_query($koneksi, $cek_query);
    
    if (mysqli_num_rows($result) > 0) {
        // Jika sudah ada di stock_log, lakukan UPDATE pada tabel stock_log
        if ($jenis_perubahan === 'masuk') {
            $update_log_query = "UPDATE stok_log SET jumlah = jumlah + $jumlah WHERE id_menu = '$id_menu'";
            $update_menu_query = "UPDATE menu SET stok = stok + $jumlah WHERE id_menu = '$id_menu'";
        } elseif ($jenis_perubahan === 'keluar') {
            $update_log_query = "UPDATE stok_log SET jumlah = jumlah - $jumlah WHERE id_menu = '$id_menu'";
            $update_menu_query = "UPDATE menu SET stok = stok - $jumlah WHERE id_menu = '$id_menu'";
        }

        // Jalankan query untuk update stock_log dan menu
        $log_updated = mysqli_query($koneksi, $update_log_query);
        $menu_updated = mysqli_query($koneksi, $update_menu_query);

        if ($log_updated && $menu_updated) {
            echo json_encode(['success' => true, 'message' => 'Stok berhasil diperbarui']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui stok']);
        }

    } else {
        // Jika belum ada, lakukan INSERT entri baru
        $insert_query = "INSERT INTO stok_log (id_menu, jenis_perubahan, jumlah, keterangan) 
                         VALUES ('$id_menu', '$jenis_perubahan', '$jumlah', '$keterangan')";

        if (mysqli_query($koneksi, $insert_query)) {
            echo json_encode(['success' => true, 'message' => 'Stok berhasil ditambahkan']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan stok']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metode tidak valid']);
}
?>

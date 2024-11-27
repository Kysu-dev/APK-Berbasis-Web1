<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_karyawan = $_GET['id'];
    
    // Query untuk menghapus karyawan berdasarkan ID
    $query = "DELETE FROM karyawan WHERE id_karyawan = '$id_karyawan'";
    
    if ($koneksi->query($query) === TRUE) {
        header("Location: dashboard_admin.php"); // Redirect ke halaman dashboard setelah penghapusan
    } else {
        echo "Gagal menghapus data: " . $koneksi->error;
    }
} else {
    echo "ID tidak ditemukan.";
}
?>

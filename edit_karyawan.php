<?php
session_start();
include 'koneksi.php';

if (isset($_POST['id_karyawan'])) {
    $id_karyawan = $_POST['id_karyawan'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $jabatan = $_POST['jabatan'];
    $password = $_POST['password'];
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security

    // Query untuk mengupdate data karyawan
    $query = "UPDATE karyawan SET nama='$nama', username='$username', jabatan='$jabatan',password='$password' WHERE id_karyawan='$id_karyawan'";
    
    if ($koneksi->query($query) === TRUE) {
        header("Location: dashboard_admin.php");
    } else {
        echo "Gagal memperbarui data: " . $koneksi->error;
    }
} else {
    echo "Data tidak lengkap.";
}
?>

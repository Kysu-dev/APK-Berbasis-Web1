<?php
// koneksi.php digunakan untuk menyambungkan ke database
include 'koneksi.php';

// Ambil data menu dari database
$menus = mysqli_query($koneksi, "SELECT * FROM menu");
$menu_data = [];

while ($row = mysqli_fetch_assoc($menus)) {
    $menu_data[] = $row;
}

// Mengubah data menu menjadi format JSON
echo json_encode($menu_data);
?>

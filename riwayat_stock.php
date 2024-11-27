<?php
include 'koneksi.php';

// Mengambil riwayat perubahan stok
$query = "SELECT stok_log.*, menu.nama 
          FROM stok_log 
          JOIN menu ON stok_log.id_menu = menu.id_menu
          ORDER BY stok_log.tanggal DESC";
$result = mysqli_query($koneksi, $query);

$riwayat_stok = [];
while ($row = mysqli_fetch_assoc($result)) {
    $riwayat_stok[] = $row;
}

// Kirim data riwayat dalam format JSON
echo json_encode($riwayat_stok);
?>

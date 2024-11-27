<?php
// Pastikan koneksi ke database sudah dilakukan
include('koneksi.php');

// Ambil ID pesanan yang akan diedit
$id_pesanan = $_GET['id'];

// Ambil detail pesanan dari database
$query = "SELECT dp.id_menu, dp.qty, dp.harga, m.nama 
          FROM detail_pesanan dp 
          JOIN menu m ON dp.id_menu = m.id_menu 
          WHERE dp.id_pesanan = '$id_pesanan'";

$result = mysqli_query($koneksi, $query);
?>

<form method="POST" action="update_pesanan.php">
    <input type="hidden" name="id_pesanan" value="<?= $id_pesanan ?>">

    <?php
    // Loop untuk menampilkan setiap item pesanan
    while ($row = mysqli_fetch_assoc($result)) {
        echo "
        <div>
            <select name='menu_pesanan[]'>
                <option value='{$row['id_menu']}'>{$row['nama']}</option>
                <!-- Anda bisa menambahkan pilihan menu lainnya jika diperlukan -->
            </select>
            <input type='number' name='qty_pesanan[]' value='{$row['qty']}' min='1'>
            <input type='number' name='harga_pesanan[]' value='{$row['harga']}' readonly>
        </div>
        ";
    }
    ?>
    <button type="submit">Update Pesanan</button>
</form>

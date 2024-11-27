<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];
    $pesanan_query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_pesanan = '$id_pesanan'");
    $pesanan = mysqli_fetch_assoc($pesanan_query);

    // Ambil detail pesanan
    $detail_pesanan_query = mysqli_query($koneksi, "SELECT dp.*, m.nama FROM detail_pesanan dp JOIN menu m ON dp.id_menu = m.id_menu WHERE dp.id_pesanan = '$id_pesanan'");
}

// Menyediakan data menu untuk JavaScript
$menu_query = mysqli_query($koneksi, "SELECT * FROM menu");
$menu_data = [];
while ($menu = mysqli_fetch_assoc($menu_query)) {
    $menu_data[] = $menu;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nama pelanggan dari form
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $update_pesanan_query = "UPDATE pesanan SET nama_pelanggan = '$nama_pelanggan' WHERE id_pesanan = '$id_pesanan'";
    mysqli_query($koneksi, $update_pesanan_query);

    $total_harga_baru = 0; // Variabel untuk menyimpan total harga baru

    // Update detail pesanan dan hitung total harga
    foreach ($_POST['menu_pesanan'] as $index => $id_menu) {
        $qty = $_POST['qty_pesanan'][$index];

        // Ambil harga dari tabel menu
        $menu_query = mysqli_query($koneksi, "SELECT harga FROM menu WHERE id_menu = '$id_menu'");
        $menu = mysqli_fetch_assoc($menu_query);
        $harga = $menu['harga'];

        // Hitung total harga untuk item ini
        $subtotal = $qty * $harga;
        $total_harga_baru += $subtotal;

        // Update detail pesanan
        $update_detail_query = "UPDATE detail_pesanan SET qty = '$qty', harga = '$harga' WHERE id_pesanan = '$id_pesanan' AND id_menu = '$id_menu'";
        mysqli_query($koneksi, $update_detail_query);
    }

    // Update total harga pada tabel pesanan
    $update_total_query = "UPDATE pesanan SET total_harga = '$total_harga_baru' WHERE id_pesanan = '$id_pesanan'";
    mysqli_query($koneksi, $update_total_query);

    // Redirect ke halaman dashboard setelah berhasil
    header("Location: dashboard_karyawan.php");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan - Warkop Cahaya Asia</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Menambahkan Font Awesome -->
    <script>
    // Menyediakan array menu ke JavaScript
    const menus = <?php echo json_encode($menu_data); ?>;

    function addMenuItem() {
        const menuContainer = document.getElementById('menu-pesanan');
        
        const newMenuItem = document.createElement('div');
        newMenuItem.classList.add('flex', 'space-x-2');
        
        let options = "<option value=''>Pilih menu...</option>";
        menus.forEach(menu => {
            options += `<option value='${menu.id_menu}'>${menu.nama}</option>`;
        });

        newMenuItem.innerHTML = `
            <select name="menu_pesanan[]" class="flex-grow p-2 border rounded" required>
                ${options}
            </select>
            <input type="number" name="qty_pesanan[]" class="w-20 p-2 border rounded" placeholder="Qty" min="1" required>
            <button type="button" class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600" onclick="removeMenuItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;
        menuContainer.appendChild(newMenuItem);
    }

    function removeMenuItem(button) {
        button.parentElement.remove();
    }
    </script>
</head> 
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">Edit Pesanan</h2>
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama-pelanggan">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" id="nama-pelanggan" class="w-full p-2 border rounded" value="<?php echo $pesanan['nama_pelanggan']; ?>" required>
            </div>

            <!-- Daftar Menu Pesanan -->
            <div class="mb-4" id="menu-pesanan">
                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Menu Pesanan</label>
                <?php
                while ($detail = mysqli_fetch_assoc($detail_pesanan_query)) {
                    echo "
                    <div class='flex space-x-2'>
                        <select name='menu_pesanan[]' class='flex-grow p-2 border rounded'>
                            <option value='{$detail['id_menu']}'>{$detail['nama']}</option>
                        </select>
                        <input type='number' name='qty_pesanan[]' class='w-20 p-2 border rounded' value='{$detail['qty']}' required>
                    </div>";
                }
                ?>
            </div>

            <!-- Tombol untuk menambah menu -->
            <button type="button" class="mt-2 text-blue-500 hover:text-blue-600" onclick="addMenuItem()">
                <i class="fas fa-plus"></i> Tambah Menu
            </button>

            <!-- Button Simpan Perubahan -->
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 w-full mt-4">
                Simpan Perubahan
            </button>
        </form>
    </div>
</body>
</html>

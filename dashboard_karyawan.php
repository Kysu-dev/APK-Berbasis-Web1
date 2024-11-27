<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan - Warkop Cahaya Asia</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="text-xl font-bold">Warkop Cahaya Asia</div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">Selamat datang, <span id="nama-karyawan">Karyawan</span></span>
                    <a href="login.php" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Tombol Navigasi untuk Bergeser Antara Manajemen Pesanan dan Manajemen Stock -->
        <div class="mt-6 flex justify-center space-x-4 mb-6">
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="showSection('pesanan')">
                Manajemen Pesanan
            </button>
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="showSection('stock')">
                Manajemen Stock
            </button>
        </div>

        <!-- Manajemen Pesanan Section -->
        <div id="pesanan-section" class="section">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Manajemen Pesanan</h2>
                <form method="POST" action="pesanan.php">
                    <!-- Input Nama Pelanggan -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nama-pelanggan">
                            Nama Pelanggan
                        </label>
                        <input type="text" name="nama_pelanggan" id="nama-pelanggan" class="w-full p-2 border rounded" required>
                    </div>

                    <!-- Pilih Menu Pesanan (Dinamis) -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Menu Pesanan</label>
                        <div class="space-y-2" id="menu-pesanan"></div>
                        
                        <!-- Tombol untuk menambah menu -->
                        <button type="button" class="mt-2 text-blue-500 hover:text-blue-600" id="tambah-menu" onclick="addMenuItem()">
                            <i class="fas fa-plus"></i> Tambah Menu
                        </button>
                    </div>

                    <!-- Button Buat Pesanan -->
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 w-full">
                        Buat Pesanan
                    </button>
                </form>
            </div>

            <!-- Daftar Pesanan Terbaru -->
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Pesanan Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">ID</th>
                                <th class="px-6 py-3 text-left">Pelanggan</th>
                                <th class="px-6 py-3 text-left">Total</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            include 'koneksi.php';
                            $result = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY created_at DESC");
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td class='px-6 py-4'>{$row['id_pesanan']}</td>";
                                echo "<td class='px-6 py-4'>{$row['nama_pelanggan']}</td>";
                                echo "<td class='px-6 py-4'>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                                echo "<td class='px-6 py-4'>{$row['status']}</td>";
                                echo "<td class='px-6 py-4'>
                                        <a href='pesanan.php?selesai={$row['id_pesanan']}' class='text-green-600 hover:underline'>Selesai</a> |
                                        <a href='pesanan.php?hapus={$row['id_pesanan']}' class='text-red-600 hover:underline' onclick='return confirm(\"Hapus pesanan ini?\");'>Hapus</a> |
                                       <a href='edit_pesanan.php?id={$row['id_pesanan']}'>Edit</a>
                                      </td>";
                                echo "</tr>";
                                
                            }
                            ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Manajemen Stock Section (Awalnya Disembunyikan) -->
        <div id="stock-section" class="section hidden">
            <!-- Form untuk mencatat perubahan stok -->
          <!-- Form untuk menambah atau mengurangi stok menu -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-bold mb-4">Perubahan Stok Menu</h2>
    <form id="form-stok" method="POST">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="id_menu">
                Pilih Menu
            </label>
            <select name="id_menu" id="id_menu" class="w-full p-2 border rounded">
                <option value="">Pilih Menu...</option>
                <?php
                // Ambil menu dari database
                $menu_query = "SELECT id_menu, nama FROM menu";
                $menu_result = mysqli_query($koneksi, $menu_query);
                while ($menu = mysqli_fetch_assoc($menu_result)) {
                    echo "<option value='{$menu['id_menu']}'>{$menu['nama']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="jenis_perubahan">
                Jenis Perubahan
            </label>
            <select name="jenis_perubahan" id="jenis_perubahan" class="w-full p-2 border rounded">
                <option value="masuk">Masuk</option>
                <option value="keluar">Keluar</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="jumlah">
                Jumlah
            </label>
            <input type="number" name="jumlah" id="jumlah" class="w-full p-2 border rounded" min="1" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="keterangan">
                Keterangan
            </label>
            <input type="text" name="keterangan" id="keterangan" class="w-full p-2 border rounded">
        </div>

        <button type="submit" id="submit-stok" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Update Stok
        </button>
    </form>
</div>
            <!-- Riwayat Stok -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Riwayat Perubahan Stok</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">ID Log</th>
                                <th class="px-6 py-3 text-left">Menu</th>
                                <th class="px-6 py-3 text-left">Jenis Perubahan</th>
                                <th class="px-6 py-3 text-left">Jumlah</th>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-left">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Menampilkan riwayat perubahan stok
                            $stok_query = mysqli_query($koneksi, "SELECT s.*, m.nama FROM stok_log s JOIN menu m ON s.id_menu = m.id_menu ORDER BY s.tanggal DESC");
                            while ($row = mysqli_fetch_assoc($stok_query)) {
                                echo "<tr>";
                                echo "<td class='px-6 py-4'>{$row['id_log']}</td>";
                                echo "<td class='px-6 py-4'>{$row['nama']}</td>";
                                echo "<td class='px-6 py-4'>{$row['jenis_perubahan']}</td>";
                                echo "<td class='px-6 py-4'>{$row['jumlah']}</td>";
                                echo "<td class='px-6 py-4'>{$row['tanggal']}</td>";
                                echo "<td class='px-6 py-4'>{$row['keterangan']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk menambah menu dinamis dan bergeser antara bagian -->
<script>
    // Mengambil data menu menggunakan AJAX
    let menus = [];

    fetch('data_menu.php')
        .then(response => response.json())
        .then(data => {
            menus = data;
        })
        .catch(error => console.error("Error fetching menu data: ", error));

    function addMenuItem() {
        const menuContainer = document.getElementById('menu-pesanan');
        
        const newMenuItem = document.createElement('div');
        newMenuItem.classList.add('flex', 'space-x-2', 'menu-item');
        
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

    // Fungsi untuk menampilkan dan menyembunyikan bagian
    function showSection(section) {
        // Sembunyikan semua bagian
        document.querySelectorAll('.section').forEach(function(el) {
            el.classList.add('hidden');
        });

        // Tampilkan bagian yang dipilih
        document.getElementById(section + '-section').classList.remove('hidden');
    }

document.getElementById('form-stok').addEventListener('submit', function (e) {
    e.preventDefault(); // Mencegah form untuk reload halaman

    // Ambil data dari form
    const formData = new FormData(this);

    fetch('stock.php', {
    method: 'POST',
    body: formData
})
.then(response => {
    console.log("Status Response:", response.status);
    console.log("Content-Type:", response.headers.get('Content-Type'));

    // Cek apakah response bukan HTML
    return response.text();
})
.then(text => {
    console.log("Response Text dari PHP:", text);

    // Jika response berisi HTML, hentikan proses
    if (text.startsWith('<!DOCTYPE html>')) {
        throw new Error('Terima HTML alih-alih JSON');
    }

    // Coba parse ke JSON
    let data;
    try {
        data = JSON.parse(text);
    } catch (e) {
        throw new Error("Gagal parse JSON: " + e.message);
    }

    if (data.success) {
        alert('Perubahan stok berhasil');
        updateRiwayatStok();
    } else {
        alert('Terjadi kesalahan: ' + data.message);
    }
})
.catch(error => {
    console.error('Error:', error); 
    alert('Terjadi kesalahan saat mengirim data: ' + error.message);
});

});

// Fungsi untuk mengupdate riwayat stok secara dinamis
function updateRiwayatStok() {
    fetch('riwayat_stok.php')
    .then(response => response.json())
    .then(data => {
        const riwayatStokContainer = document.getElementById('riwayat-stok');
        riwayatStokContainer.innerHTML = ''; // Bersihkan data sebelumnya
        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4">${item.id_menu}</td>
                <td class="px-6 py-4">${item.nama}</td>
                <td class="px-6 py-4">${item.jenis_perubahan}</td>
                <td class="px-6 py-4">${item.jumlah}</td>
                <td class="px-6 py-4">${item.tanggal}</td>
                <td class="px-6 py-4">${item.keterangan || '-'}</td>
            `;
            riwayatStokContainer.appendChild(row);
        });
    })
    .catch(error => console.error('Error:', error));
}

// Panggil fungsi untuk load riwayat stok saat pertama kali halaman dimuat
updateRiwayatStok();


    </script>
</body>
</html>

<?php
session_start();
include 'koneksi.php';
// Ambil filter jabatan dari parameter GET
$filter_jabatan = isset($_GET['jabatan']) ? $_GET['jabatan'] : 'all';

// Query untuk mengambil data karyawan
$query = "SELECT id_karyawan, username, nama, jabatan FROM karyawan";
if ($filter_jabatan !== 'all' && $filter_jabatan !== '') {
    $query .= " WHERE jabatan = '$filter_jabatan'";
}

$result = $koneksi->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Warkop Cahaya Asia</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-indigo-700 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="text-xl font-bold">Admin Panel - Warkop Cahaya Asia</div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">Admin: <span id="nama-admin">Administrator</span></span>
                    <a href="login.php" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <!-- <div class="container mx-auto px-4 py-8"> -->
        <!-- Quick Stats -->
        <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-gray-500 text-sm">Total Karyawan</h3>
                <p class="text-2xl font-bold" id="total-karyawan">0</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-gray-500 text-sm">Karyawan Aktif Hari Ini</h3>
                <p class="text-2xl font-bold text-green-500" id="karyawan-aktif">0</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-gray-500 text-sm">Total Admin</h3>
                <p class="text-2xl font-bold text-blue-500" id="total-admin">0</p>
            </div>
        </div> -->

        <!-- Tambah Karyawan Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Tambah Karyawan Baru</h2>
            <form id="form-tambah-karyawan" action="tambah_karyawan.php" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nama">
                        Nama Lengkap
                    </label>
                    <input type="text" id="nama" name="nama" required
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Username
                    </label>
                    <input type="text" id="username" name="username" required
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input type="password" id="password" name="password" required
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="konfirmasi-password">
                        Konfirmasi Password
                    </label>
                    <input type="password" id="konfirmasi-password" name="konfirmasi-password" required
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="jabatan">
                        Jabatan
                    </label>
                    <select id="jabatan" name="jabatan" required
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Pilih Jabatan...</option>
                        <option value="admin">Admin</option>
                        <option value="karyawan">Karyawan</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" 
                        class="w-full bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        Tambah Karyawan
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Karyawan -->
        <!-- Bagian HTML -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Daftar Karyawan</h2>
        <div class="flex space-x-2">
            <!-- Filter pencarian dan jabatan -->
            <!-- <input type="text" id="search" placeholder="Cari karyawan..."
                class="p-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400"> -->

                <select id="jabatanFilter" class="p-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="all" <?= $filter_jabatan === 'all' ? 'selected' : '' ?>>Semua Jabatan</option>
                <option value="admin" <?= $filter_jabatan === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="karyawan" <?= $filter_jabatan === 'karyawan' ? 'selected' : '' ?>>Karyawan</option>
            </select>
        </div>
    </div>

    <!-- Tabel Data Karyawan -->
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="employeeTable">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo $row['id_karyawan']; ?></td>
                            <td class="px-6 py-4"><?php echo $row['nama']; ?></td>
                            <td class="px-6 py-4"><?php echo $row['username']; ?></td>
                            <td class="px-6 py-4"><?php echo $row['jabatan']; ?></td>
                            <td class="px-6 py-4">
                               <a href="update_karyawan.php?id=<?php echo $row['id_karyawan']; ?>" class="btn btn-info btn-sm me-2 text-blue-500 "  >Edit </a>
                                <button onclick="hapusKaryawan(<?php echo $row['id_karyawan']; ?>)" class="text-red-600 hover:text-red-900">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">Tidak ada data ditemukan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


    </div>

    <!-- Modal Edit Karyawan -->
    <div id="modal-edit" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Karyawan</h3>
                <form class="mt-4 text-left">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-nama">
                            Nama Lengkap
                        </label>
                        <input type="text" id="edit-nama" name="edit-nama" 
                            class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-username">
                            Username
                        </label>
                        <input type="text" id="edit-username" name="edit-username" 
                            class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-jabatan">
                            Jabatan
                        </label>
                        <select id="edit-jabatan" name="edit-jabatan" 
                            class="w-full p-2 border rounded">
                            <option value="admin">Admin</option>
                            <option value="karyawan">Karyawan</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modal-hapus" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus karyawan ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-end space-x-2">
                    <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                        Batal
                    </button>
                    <button class="px-4 py-2 bg-red-500 text-white rounded">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
// Filter berdasarkan jabatan
document.getElementById('jabatanFilter').addEventListener('change', function () {
    const jabatan = this.value;
    const url = new URL(window.location.href);

    if (jabatan === 'all') {
        // Hapus parameter 'jabatan' dari URL
        url.searchParams.delete('jabatan');
    } else {
        // Tambah atau perbarui parameter 'jabatan'
        url.searchParams.set('jabatan', jabatan);
    }
    window.location.href = url.toString();
});

function hapusKaryawan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus karyawan ini?')) {
        window.location.href = 'hapus_karyawan.php?id=' + id;
    }
}
</script>
</body>
</html>
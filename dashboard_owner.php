<?php
include 'koneksi.php';

// Query untuk mendapatkan total karyawan
$total_karyawan_query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM karyawan");
$total_karyawan = mysqli_fetch_assoc($total_karyawan_query)['total'];

// Query untuk mendapatkan total menu
$total_menu_query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM menu");
$total_menu = mysqli_fetch_assoc($total_menu_query)['total'];

// Query untuk mendapatkan kehadiran hari ini
$today = date('Y-m-d');
$kehadiran_query = mysqli_query($koneksi, "SELECT COUNT(*) AS hadir FROM absensi WHERE tanggal = '$today' AND status = 'Hadir'");
$kehadiran_hari_ini = mysqli_fetch_assoc($kehadiran_query)['hadir'];

// Query untuk mendapatkan data karyawan
$karyawan_query = mysqli_query($koneksi, "SELECT * FROM karyawan");

// Query untuk mendapatkan data menu (stok barang)
$menu_query = mysqli_query($koneksi, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - Warkop Cahaya Asia</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-title {
            color: #1a1a1a;
            margin-bottom: 24px;
            font-size: 28px;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .card-title {
            font-size: 16px;
            color: #666;
        }

        .card-value {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .tabs {
            margin-bottom: 20px;
        }

        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-button {
            padding: 10px 20px;
            border: none;
            background: #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .tab-button.active {
            background: #1a73e8;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #1a1a1a;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .chart-container {
            height: 300px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="dashboard-title">Dashboard Owner</h1>

        <div class="summary-cards">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total Karyawan</h3>
                </div>
                <div class="card-value"><?php echo $total_karyawan; ?></div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total Menu</h3>
                </div>
                <div class="card-value"><?php echo $total_menu; ?></div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kehadiran Hari Ini</h3>
                </div>
                <div class="card-value"><?php echo $kehadiran_hari_ini; ?></div>
            </div>
        </div>

        <div class="tabs">
            <div class="tab-buttons">
                <button class="tab-button active" onclick="openTab(event, 'employees')">Data Karyawan</button>
                <button class="tab-button" onclick="openTab(event, 'inventory')">Stok Barang</button>
            </div>

            <!-- Data Karyawan -->
            <div id="employees" class="tab-content card active">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Jabatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($karyawan = mysqli_fetch_assoc($karyawan_query)): ?>
                        <tr>
                            <td><?php echo $karyawan['id_karyawan']; ?></td>
                            <td><?php echo $karyawan['nama']; ?></td>
                            <td><?php echo $karyawan['username']; ?></td>
                            <td><?php echo $karyawan['jabatan']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Stok Barang -->
            <div id="inventory" class="tab-content card">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($menu = mysqli_fetch_assoc($menu_query)): ?>
                        <tr>
                            <td><?php echo $menu['id_menu']; ?></td>
                            <td><?php echo $menu['nama']; ?></td>
                            <td><?php echo $menu['stok']; ?></td>
                            <td>Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function openTab(evt, tabName) {
            const tabContents = document.getElementsByClassName("tab-content");
            for (let content of tabContents) {
                content.classList.remove("active");
            }

            const tabButtons = document.getElementsByClassName("tab-button");
            for (let button of tabButtons) {
                button.classList.remove("active");
            }

            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>
</html>

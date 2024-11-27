

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Update Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard Update Karyawan</h2>
        <?php
            include 'koneksi.php';
            $id = $_GET['id'];
            $data = mysqli_query($koneksi,"select * from karyawan where id_karyawan='$id'");
            while($d = mysqli_fetch_array($data)){
?>
        <!-- Form Update Karyawan -->
        <form action="edit_karyawan.php" method="POST">
        <input type="hidden" id="id_karyawan" name="id_karyawan"  value="<?php echo $d['id_karyawan'] ?>" >
            <div class="form-group">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama"  value="<?php echo $d['nama'] ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"  value="<?php echo $d['username'] ?>" required>
            </div>

            <div class="form-group">
                <label for="jabatan">Jabatan:</label>
                <input type="text" id="jabatan" name="jabatan"  value="<?php echo $d['jabatan'] ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password"  value="<?php echo $d['password'] ?>"  required>
            </div>

            <input type="hidden" id="id" name="id">
            <button type="submit" class="btn">Simpan Data</button>
        </form>
        <?php } ?>

        <!-- Tabel Data Karyawan -->
        <!-- <h3>Data Karyawan</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="employeeData"> -->
                <!-- Data akan diisi oleh PHP -->
            <!-- </tbody>
        </table> -->
    </div>
</body>
</html>
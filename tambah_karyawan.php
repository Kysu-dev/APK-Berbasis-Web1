<?php

include('koneksi.php');

// Get form data
$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
// $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
$jabatan = $_POST['jabatan'];

// Prepare SQL statement
$sql = "INSERT INTO karyawan (nama, username, password, jabatan) VALUES (?, ?, ?, ?)";

$stmt = $koneksi->prepare($sql);

$stmt->bind_param("ssss", $nama, $username, $password, $jabatan);

if ($stmt->execute()) {
  echo "Employee created successfully!";
  header("location:dashboard_admin.php");
} else {
  echo "Error: " . $koneksi->error;
}

$stmt->close();
$koneksi->close();

?>
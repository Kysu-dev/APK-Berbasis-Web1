<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'warkop_cahaya_asia';

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}

// Set karakter encoding
$koneksi->set_charset("utf8mb4");
?>
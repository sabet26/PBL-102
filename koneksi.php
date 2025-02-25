<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "pbl_102";

// Membuat koneksi
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die('Gagal Koneksi: ' . mysqli_connect_errno() . ' - ' . mysqli_connect_error());
}
?>

<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "pbl_102";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk memperbarui data kedaluwarsa
$updateQuery = "
    UPDATE paket
    SET status = 'kadaluwarsa',
        tanggal_kadaluwarsa = NOW()
    WHERE status != 'kadaluwarsa' 
    AND tanggal_daftar <= NOW() - INTERVAL 7 DAY;
";

if ($conn->query($updateQuery) === TRUE) {
    echo "Data paket kedaluwarsa berhasil diperbarui.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

<?php
session_start();

// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "pbl_102";

$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Update status otomatis untuk paket yang sudah kadaluwarsa
$query_update = "UPDATE paket SET status = 'kadaluwarsa', 
        tanggal_kadaluwarsa = IF(tanggal_daftar IS NOT NULL AND tanggal_daftar != '', DATE_ADD(tanggal_daftar, INTERVAL 7 DAY), NULL)
    WHERE DATEDIFF(CURDATE(), tanggal_daftar) > 7 
    AND status = 'tersedia'";
$conn->query($query_update);

// Query untuk mengambil data paket yang masih tersedia
$query = "SELECT * FROM paket WHERE status = 'kadaluwarsa'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
    <!-- Link ke Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Link ke CSS Kustom -->
    <link rel="stylesheet" href="style_halaman_utama.css">
</head>

<body>
    <!-- Bagian Header -->
    <div id="beranda" class="header"  style="box-shadow: 0px 12px 8px white;">
        <a class="navbar-brand" href="#">
            <img src="SIMBA with White Text Horizontal 2.png" class="img-fluid" alt="" width="190" height="65">
        </a>
        <div class="option">
            <ul>
                <!-- Tombol untuk membuka Modal Login -->
                <li><button class="btn btn-primary" data-toggle="modal" data-target="#loginModal">Masuk</button></li>
            </ul>
        </div>
    </div>

    <!-- Konten Utama -->
    <div id="content" class="content">
        <div class="image">
            <img src="Kampus-Polibatam-1-1024x385.png" alt="Polibatam">
        </div>
        <div class="welcome">
            <h1>Sistem Manajemen Paket Polibatam</h1>
        </div>
    </div>

    <!-- Modal Form Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- Form Login -->
                    <div class="containerform">
                        <form action="proses_login.php" class="registration-form" method="POST">
                            <div class="logo">
                                <img src="logo simba new.png" alt="logo simba">
                            </div>
                            <h1>SIMBA</h1>
                            <!-- Form Input -->
                            <div class="form-group">
                                <label for="NIK">NIK</label>
                                <input type="text" id="NIK" name="NIK" class="form-control" placeholder="Masukkan NIK pengguna" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Kata Sandi</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Kata Sandi" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Masuk</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <!-- Footer content goes here -->
    </div>

    <!-- Script untuk Bootstrap JS (jQuery, Popper.js, dan Bootstrap JS) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

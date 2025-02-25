<?php
session_start();
include "koneksi.php"; // Ganti dengan file koneksi Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NIK = mysqli_real_escape_string($koneksi, $_POST['NIK']);
    $nama_pamdal = mysqli_real_escape_string($koneksi, $_POST['nama_pamdal']);
    $password = $_POST['kata_sandi']; // Password yang dimasukkan pengguna
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    // Periksa apakah NIK sudah terdaftar
    $check_sql = "SELECT * FROM pamdal WHERE NIK = '$NIK'";
    $result = mysqli_query($koneksi, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        // Jika NIK sudah ada, tampilkan pesan error
        $_SESSION['message'] = "NIK sudah terdaftar.";
        header("Location: kelola_akun.php");
        exit();
    } else {
        // Enkripsi password menggunakan password_hash()
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk memasukkan data ke dalam tabel pamdal
        $sql = "INSERT INTO pamdal (NIK, nama_pamdal, kata_sandi, role) VALUES ('$NIK', '$nama_pamdal', '$password_hash', '$role')";

        // Menjalankan query
        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['message'] = "Akun pamdal berhasil ditambahkan.";
            header("Location: kelola_akun.php");
            exit();
        } else {
            // Menampilkan error jika query gagal
            $_SESSION['message'] = "Terjadi kesalahan saat menambahkan akun: " . mysqli_error($koneksi);
            header("Location: kelola_akun.php");
            exit();
        }
    }
}
?>

<?php
session_start();
include "koneksi.php";

$NIK = mysqli_real_escape_string($koneksi, $_POST['NIK']);
$password = $_POST['password'];

// Mengambil data pengguna dari database
$sql = "SELECT * FROM pamdal WHERE NIK = '$NIK'";
$hasil_query = mysqli_query($koneksi, $sql);

// Mengecek jika ada data yang ditemukan
if (mysqli_num_rows($hasil_query) == 1) {
    $data_user = mysqli_fetch_assoc($hasil_query);

    // Memverifikasi password dengan password_verify
    if (password_verify($password, $data_user['kata_sandi'])) {
        // Simpan data ke session
        $_SESSION['id_pamdal'] = $data_user['id_pamdal']; // Menyimpan id_pamdal ke session
        $_SESSION['NIK'] = $NIK;
        $_SESSION['role'] = $data_user['role']; // Simpan peran ke session
        $_SESSION['login_time'] = date('Y-m-d H:i:s');

        // Redirect ke halaman yang sesuai dengan role atau halaman utama
        header('location: tersedia.php');
        exit();
    } else {
        // Jika password salah
        $_SESSION['error_message'] = "Password Anda salah.";
        header('location: halaman_utama.php'); // Arahkan kembali ke halaman login
        exit();
    }
} else {
    // Jika NIK tidak ditemukan
    $_SESSION['error_message'] = "Gagal login: NIK tidak ditemukan.";
    header('location: halaman_utama.php'); // Arahkan kembali ke halaman login
    exit();
}
?>

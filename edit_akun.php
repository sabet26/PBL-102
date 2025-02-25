<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan sanitasi input
    $nik = mysqli_real_escape_string($koneksi, $_POST['NIK']);  // Menggunakan NIK sebagai identifier unik
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    $nama_pamdal = mysqli_real_escape_string($koneksi, $_POST['nama_pamdal']);
    
    // Cek apakah password baru diinput
    $password = !empty($_POST['kata_sandi']) ? password_hash($_POST['kata_sandi'], PASSWORD_BCRYPT) : null;

    // Buat query untuk update berdasarkan NIK
    $sql = "UPDATE pamdal SET nama_pamdal = ?, role = ?";  // Update nama_pamdal dan role

    // Jika password baru diinput, tambahkan kolom kata_sandi
    if ($password) {
        $sql .= ", kata_sandi = ?";
    }

    // Tambahkan WHERE untuk memastikan kita hanya mengubah data berdasarkan NIK
    $sql .= " WHERE NIK = ?";

    // Siapkan prepared statement
    if ($stmt = $koneksi->prepare($sql)) {
        // Jika ada password baru, bind parameter untuk nama_pamdal, role, password, dan NIK
        if ($password) {
            $stmt->bind_param("ssss", $nama_pamdal, $role, $password, $nik);
        } else {
            // Jika tidak ada password baru, bind parameter untuk nama_pamdal, role, dan NIK
            $stmt->bind_param("sss", $nama_pamdal, $role, $nik);
        }

        // Eksekusi query
        if ($stmt->execute()) {
            $_SESSION['message'] = "Akun pamdal berhasil diperbarui.";
        } else {
            $_SESSION['message'] = "Terjadi kesalahan saat memperbarui akun. Silakan coba lagi.";
        }

        // Tutup prepared statement
        $stmt->close();
    } else {
        $_SESSION['message'] = "Query gagal disiapkan. Silakan coba lagi.";
    }

    // Redirect kembali ke halaman kelola_akun.php
    header("Location: kelola_akun.php");
    exit();
}
?>

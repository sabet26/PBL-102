<?php
session_start();
include "koneksi.php";

// Cek apakah NIK ada di URL
if (isset($_GET['NIK'])) {
    $nik = mysqli_real_escape_string($koneksi, $_GET['NIK']);
    
    // Cek apakah NIK valid dan akun ada
    $sql = "SELECT * FROM pamdal WHERE NIK = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Jika akun ditemukan, hapus akun
        $stmt->close();

        // Cek apakah akun admin
        $sqlRole = "SELECT role FROM pamdal WHERE NIK = ?";
        $stmtRole = $koneksi->prepare($sqlRole);
        $stmtRole->bind_param("s", $nik);
        $stmtRole->execute();
        $stmtRole->bind_result($role);
        $stmtRole->fetch();
        $stmtRole->close();

        if ($role !== 'admin') {
            // Hapus akun jika bukan admin
            $deleteSql = "DELETE FROM pamdal WHERE NIK = ?";
            $stmtDelete = $koneksi->prepare($deleteSql);
            $stmtDelete->bind_param("s", $nik);
            if ($stmtDelete->execute()) {
                $_SESSION['message'] = "Akun pamdal berhasil dihapus.";
            } else {
                $_SESSION['message'] = "Terjadi kesalahan saat menghapus akun.";
            }
            $stmtDelete->close();
        } else {
            $_SESSION['message'] = "Admin tidak dapat dihapus.";
        }
    } else {
        $_SESSION['message'] = "Akun tidak ditemukan.";
    }

    // Redirect kembali ke halaman kelola_akun.php
    header("Location: kelola_akun.php");
    exit();
}
?>

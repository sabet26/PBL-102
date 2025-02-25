<?php
if (isset($_POST['ids'])) {
    $ids = $_POST['ids']; // ID paket yang dipilih

    // Koneksi ke database
    $conn = new mysqli('localhost', 'root', '', 'pbl_102');

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Menyiapkan query untuk memperbarui status
    $idList = implode(",", $ids);
    $sql = "UPDATE paket SET status = 'diambil' WHERE id_paket IN ($idList)";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil dipindahkan ke tabel Diambil!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<?php
session_start(); // Mulai session

// Koneksi ke Database
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "pbl_102"; 

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Ambil data dari form
$ekspedisi = trim($_POST['ekspedisi'] ?? '');
$nomorresi = trim($_POST['nomorresi'] ?? '');
$namapaket = trim($_POST['namapaket'] ?? '');
$namapemilik = trim($_POST['namapemilik'] ?? '');
$no_hp_pemilik = trim($_POST['no_hp_pemilik'] ?? ''); 
$tanggal_daftar = date('Y-m-d'); // Tanggal saat ini
$tanggal_diambil = null; // Awalnya null karena belum diambil
$status = 'tersedia'; // Status awal paket
$NIK = NULL; // ID pamdal default, sesuaikan jika perlu

// Validasi data input
if (empty($ekspedisi) || empty($nomorresi) || empty($namapaket) || empty($namapemilik) || empty($no_hp_pemilik)) {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Semua field harus diisi.';
    header("Location: pendaftaran_paket.php");
    exit();
}

// Validasi nomor resi harus unik
try {
    $checkQuery = "SELECT COUNT(*) FROM paket WHERE nomor_resi = :nomor_resi";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':nomor_resi', $nomorresi);
    $checkStmt->execute();

    if ($checkStmt->fetchColumn() > 0) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Nomor resi sudah terdaftar.';
        header("Location: pendaftaran_paket.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error saat validasi nomor resi: " . $e->getMessage());
}

// Query untuk menyimpan data ke database
$sql = "INSERT INTO paket (ekspedisi, nomor_resi, nama_paket, nama_pemilik, no_hp_pemilik, tanggal_daftar, tanggal_diambil, status, NIK) 
        VALUES (:ekspedisi, :nomor_resi, :nama_paket, :nama_pemilik, :no_hp_pemilik, :tanggal_daftar, :tanggal_diambil, :status, :NIK)";

$stmt = $pdo->prepare($sql);

// Bind parameter ke query
$stmt->bindParam(':ekspedisi', $ekspedisi);
$stmt->bindParam(':nomor_resi', $nomorresi);
$stmt->bindParam(':nama_paket', $namapaket);
$stmt->bindParam(':nama_pemilik', $namapemilik);
$stmt->bindParam(':no_hp_pemilik', $no_hp_pemilik);
$stmt->bindParam(':tanggal_daftar', $tanggal_daftar);
$stmt->bindValue(':tanggal_diambil', $tanggal_diambil, PDO::PARAM_NULL);
$stmt->bindParam(':status', $status);
$stmt->bindParam(':NIK', $NIK);

try {
    // Eksekusi query
    $stmt->execute();

    // Kirim notifikasi via API (Fonnte)
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $no_hp_pemilik,
            'message' => "Halo $namapemilik, paket Anda dengan nama \"$namapaket\" telah sampai, jika tidak diambil dalam 7 hari maka paket akan dimasukkan ke gudang. Nomor Resi: $nomorresi di ekspedisi $ekspedisi.",
            'countryCode' => '62',
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: qxTRduJh4otFwiT7LHV8'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    // Redirect ke halaman dengan status sukses
    $_SESSION['status'] = 'success';
    $_SESSION['message'] = 'Paket berhasil didaftarkan.';
    header("Location: pendaftaran_paket.php");
    exit();
} catch (PDOException $e) {
    // Tampilkan pesan error jika gagal
    die("Error saat menyimpan data: " . $e->getMessage());
} finally {
    // Tutup koneksi PDO
    $pdo = null;
}

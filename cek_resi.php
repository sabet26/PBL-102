<?php
header('Content-Type: application/json');


// Mendapatkan parameter nomor resi dan ekspedisi dari URL
$nomorResi = $_GET['nomor_resi'] ?? $_POST['nomor_resi'] ?? '';
$ekspedisi = $_GET['ekspedisi'] ?? $_POST['ekspedisi'] ?? '';

// Validasi input
if (empty($nomorResi) || empty($ekspedisi)) {
    echo json_encode([
        "status" => "error",
        "message" => "Nomor resi wajib diisi."
    ]);
    exit;
}

// URL API Binderbyte
$apiUrl = "https://api.binderbyte.com/v1/track?awb=$nomorResi&courier=$ekspedisi&api_key=409112c36c05808510ca256738c7278b0c66b3bd0d05113e3f410a6181a123a4";

// Mengambil data dari API Binderbyte
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
$response = curl_exec($ch);
curl_close($ch);

if ($response === FALSE) {
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan saat mengakses API Binderbyte."
    ]);
    exit;
}

// Mengkonversi JSON respons dari API
$data = json_decode($response, true);

// Cek apakah data dari API valid dan responsnya sukses
if (isset($data['status']) && $data['status'] == 200) {
    // Mengembalikan data yang diperlukan dalam format JSON
    echo json_encode([
        "status" => "success",
        "ekspedisi" => $data['data']['summary']['courier'] ?? '',
        "nomor_resi" => $data['data'] ['summary']['awb']?? '',
        "nama_pemilik" => $data['data'] ['detail'] ['receiver']?? '',
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $data['message'] ?? 'Resi tidak ditemukan atau ekspedisi salah.',
    'ekspedisi'=>$ekspedisi,'resi'=>$nomorResi,'apiUrl'=>$apiUrl
    ]);
}
?>

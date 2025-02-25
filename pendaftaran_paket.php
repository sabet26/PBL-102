<?php
session_start();
$status = $_SESSION['status'] ?? null;
$message = $_SESSION['message'] ?? null;

if ($status && $message) {
    echo "<div class='alert alert-{$status}'>{$message}</div>";
    unset($_SESSION['status'], $_SESSION['message']); // Hapus session setelah ditampilkan
}
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

if (isset($_GET['status']) && isset($_GET['message'])) {
    echo "<div class='alert alert-" . ($_GET['status'] === 'success' ? 'success' : 'danger') . "'>";
    echo htmlspecialchars($_GET['message']);
    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="style_registration_package.css" />
</head>

    <div class="content">
        <div class="container-regist-package">
            <div class="logo">
                <img src="logo simba new.png" alt="logo simba" />
                <h1 style="color:black;">SIMBA</h1>
            </div>
            <form action="proses_pendaftaran.php" class="registrationpackageform" method="POST" id="registpackage" onsubmit="return validateForm()">
                <div class="formpackage">
                    <h5>Masukkan Data Paket</h5>
                    <div class="package-form-group">
                        <label for="ekspedisi">Pilih Ekspedisi</label>
                        <select class="form-control" name="ekspedisi" id="ekspedisi">
                            <option value selected hidden>Pilih Ekspedisi Anda</option>
                            <option value="anteraja">AnterAja</option>
                            <option value="ide">ID Express</option>
                            <option value="jet">JET Express</option>
                            <option value="jne">JNE</option>
                            <option value="jnt">JNT</option>
                            <option value="jnt_cargo">JNT Cargo</option>
                            <option value="lion">Lion</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="sicepat">SiCepat</option>
                            <option value="spx">Shopee Express</option>
                            <option value="ninja">Ninja</option>
                            <option value="lex">Lazada Express</option>
                        </select>
                    </div>
                    <div class="package-form-group">
                        <label for="nomorresi">Nomor Resi</label>
                        <input type="text" class="form-control" id="nomorresi" name="nomorresi" required />
                        <a type="button" class="btn" id="cekResi">Cek Resi</a>
                    </div>
                    <div class="package-form-group">
                        <label for="namapaket">Nama Paket</label>
                        <input type="text" class="form-control" id="namapaket" name="namapaket" required />
                    </div>
                    <div class="package-form-group">
                        <label for="namapemilik">Nama Pemilik</label>
                        <input type="text" class="form-control" id="namapemilik" name="namapemilik" required />
                    </div>
                    <div class="package-form-group">
                        <label for="no_hp_pemilik">Nomor Handphone</label>
                        <input type="text" class="form-control" id="no_hp_pemilik" name="no_hp_pemilik" required />
                    </div>
                    <button type="submit" class="button-daftar">Daftar</button>
                </div>
            </form>
            <div id="alertArea" class="mt-3"></div>
        </div>
    </div>

    <script>
        // Ambil data dari localStorage
        const formData = JSON.parse(window.localStorage.getItem('formPaketData'));

        if (formData) {
            // Pastikan formData ada dan valid
            console.log('Data dari localStorage:', formData); // Debug: cek data dari localStorage
            document.getElementById('ekspedisi').value = formData.ekspedisi || '';
            document.getElementById('nomorresi').value = formData.nomor_resi || '';
            document.getElementById('namapaket').value = formData.nama_paket || '';
            document.getElementById('namapemilik').value = formData.nama_pemilik || '';
            document.getElementById('no_hp_pemilik').value = formData.no_hp_pemilik || '';
            // Hapus data setelah digunakan
            window.localStorage.removeItem('formPaketData');
        } else {
            console.log('Tidak ada data di localStorage');
        }

        function validateForm() {
            const ekspedisi = document.getElementById("ekspedisi").value.trim();
            const nomorresi = document.getElementById("nomorresi").value.trim();
            const namapaket = document.getElementById("namapaket").value.trim();
            const namapemilik = document.getElementById("namapemilik").value.trim();
            const no_hp_pemilik = document.getElementById("no_hp_pemilik").value.trim();

            if (!ekspedisi || !/^[a-zA-Z\s]+$/.test(ekspedisi)) {
                alert("Ekspedisi harus dipilih!");
                document.getElementById("ekspedisi").focus();
                return false;
            }

            if (!nomorresi || !/^[a-zA-Z0-9]{10,25}$/.test(nomorresi)) {
                alert("Nomor resi harus berupa 10-25 karakter alfanumerik!");
                document.getElementById("nomorresi").focus();
                return false;
            }

            if (!namapaket) {
                alert("Nama paket harus diisi!");
                document.getElementById("namapaket").focus();
                return false;
            }

            if (!namapemilik) {
                alert("Nama pemilik harus diisi!");
                document.getElementById("namapemilik").focus();
                return false;
            }

            if (!no_hp_pemilik || !/^\d{10,15}$/.test(no_hp_pemilik)) {
                alert("Nomor HP harus berupa angka 10-15 digit!");
                document.getElementById("no_hp_pemilik").focus();
                return false;
            }

            return confirm("Apakah data sudah benar?");
        }

    </script>
    <script>
        document.getElementById('cekResi').addEventListener('click', function () {
    const nomorResi = document.getElementById('nomorresi').value.trim();
    const ekspedisi = document.getElementById('ekspedisi').value.trim();
    const alertArea = document.getElementById('alertArea');

    // Validasi input
    if (!nomorResi || !ekspedisi) {
        alertArea.innerHTML = `<div class="alert alert-danger">Ekspedisi dan nomor resi harus diisi!</div>`;
        return;
    }

    // Panggil API cek_resi.php
    fetch(`cek_resi.php?nomor_resi=${encodeURIComponent(nomorResi)}&ekspedisi=${encodeURIComponent(ekspedisi)}`)
        .then(response => response.json())
        .then(data => {
            console.log(data); // Debug: lihat data dari API

            if (data.status === 'success') {
                // Isi form secara otomatis berdasarkan hasil dari API
                document.getElementById('namapaket').value = data.nama_paket || '';
                document.getElementById('namapemilik').value = data.nama_pemilik || '';
                document.getElementById('no_hp_pemilik').value = data.no_hp_pemilik || '';

                alertArea.innerHTML = `<div class="alert alert-success">Data ditemukan! Kolom telah diisi otomatis.</div>`;
            } else {
                alertArea.innerHTML = `<div class="alert alert-danger">${data.message || 'Resi tidak ditemukan.'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertArea.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan saat mencari resi.</div>`;
        });
});
</script>
</body>

</html>

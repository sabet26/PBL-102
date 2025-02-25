<?php
session_start();

// Cek login
if (!isset($_SESSION["NIK"])) {
    echo "<script>
            alert('Anda belum login!');
            window.location.href = 'halaman utama.php'; // Redirect ke halaman login
        </script>";
    exit;
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
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tersedia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="style_tabel.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
  <body>
  <nav class="navbar navbar-expand-lg card login-form">
        <div class="container-fluid">
        <a class="navbar-brand" href="#">
        <button onclick="openSidebar()" class="btn-side me-4">&#9776;</button>
            <img src="SIMBA with White Text Horizontal 2.png" class="img-fluid" alt="" width="190" height="65">
        </a>
        <a href="logout.php" onclick="return confirm('Anda yakin ingin keluar?');"><button type="button" class="btn btn-danger">LOG OUT</button></a>
        </div>
    </nav>  
      <!-- Sidebar -->
      <div class="sidebar" id="sidebar">
      <button class="close-btn" onclick="closeSidebar()">&times;</button>
      <ul>  
      <li><a href="tersedia.php">Tersedia</a></li>
        <li><a href="diambil.php">Diambil</a></li>
        <li><a href="kadaluwarsa.php">Kadaluwarsa</a></li>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <li><a href="kelola_akun.php">Kelola Akun</a></li>
        <?php endif; ?>
</ul>
      </div>
      <div class="content">
      <div class="card login-form">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h1 class="card-title mb-0">Daftar Paket Kadaluwarsa</h1>
                <div class="d-flex align-items-center gap-2">
                <div class="search-container"></div>
                </form>
            </div>
        </div>
        <form method="POST" action="tersedia1.php" id="paketForm">
        <div class="container mt-6 table-responsive">    
            <table class="table table-bordered display" id="example" style="width:100%">
            <div class="dropdown">
                 <img src="filter.png" alt="" style="height: 36px; width: 36px;">
                    <div class="dropdown-content">
                        <label for="filterBulan">Bulan:</label>
                            <select id="filterBulan">
                                <option value="">Semua Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        <label for="filterEkspedisi">Ekspedisi:</label>
                        <select id="filterEkspedisi">
                            <option value selected hidden>Pilih Ekspedisi Anda</option>
                            <option value="">Semua</option>
                            <option value="anteraja">AntarAja</option>
                            <option value="ide">ID Express</option>
                            <option value="jet">JET Express</option>
                            <option value="jne">JNE</option>
                            <option value="jnt">JNT</option>
                            <option value="jntcargo">JNT Cargo</option>
                            <option value="lion">Lion</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="sicepat">SiCepat</option>
                            <option value="spx">Shopee Express</option>
                            <option value="ninja">Ninja</option>
                            <option value="lex">Lazada Express</option>
                          </select>
                        </div>
                    </div>
                <thead>
                    <tr>
                        <th>Id Paket</th>
                        <th>Ekspedisi</th>
                        <th>Nomor Resi</th>
                        <th>Nama Paket</th>
                        <th>Nama Pemilik</th>
                        <th>No. HP Pemilik</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
        <?php
        $no = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['ekspedisi']}</td>
                            <td>{$row['nomor_resi']}</td>
                            <td>{$row['nama_paket']}</td>
                            <td>{$row['nama_pemilik']}</td>
                            <td>{$row['no_hp_pemilik']}</td>
                            <td>{$row['tanggal_kadaluwarsa']}</td>
                            <td>{$row['status']}</td>
                        </tr>";
                        $no++;
                }
            } else {
                echo "<tr><td colspan='8'>Tidak ada data!</td></tr>";
            }
            ?>
      </tbody>
    </table>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready(function () {
        let table = $("#example").DataTable(); // Inisialisasi DataTables


        $('#filterBulan, #filterEkspedisi').on('change', function() {
                const bulan = $('#filterBulan').val();
                const ekspedisi = $('#filterEkspedisi').val();

                table.column(6).search(bulan ? `-${bulan}-` : '', true, false); // Filter kolom tanggal (6)
                table.column(1).search(ekspedisi ? `^${ekspedisi}$` : '', true, false); // Filter kolom ekspedisi (1)

                table.draw();
            });
      });
    </script>
    <script src="../sidebar_datatable.js"></script>
    <script>
    function openSidebar() {
      document.getElementById("sidebar").style.width = "250px"; // Buka sidebar
      document.querySelector(".content").style.marginLeft = "250px"; // Pindahkan konten utama
      document.querySelector(".navbar").style.marginLeft = "250px"; // Pindahkan navbar
    }
    
    function closeSidebar() {
      document.getElementById("sidebar").style.width = "0"; // Tutup sidebar
      document.querySelector(".content").style.marginLeft = "0"; // Reset margin konten utama
      document.querySelector(".navbar").style.marginLeft = "0"; // Reset margin navbar
    }
    </script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
          const sidebarToggle = document.querySelector(".sidebar");
          const container = document.querySelector(".global-container");
    
          sidebarToggle.addEventListener("click", function () {
              this.classList.toggle("active");
              container.classList.toggle("sidebar-active");
          });
      });
    </script>    
    </script>
    <script>
      function customLogoutAlert() {
    return confirm('Anda yakin ingin keluar?');
    }
    </script>
  </body>
</html>

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

if (!isset($_SESSION['NIK'])) {
    die("NIK Pamdal tidak ditemukan. Silakan login ulang.");
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

// Query untuk mengambil data paket yang masih tersedia
$query = "SELECT * FROM paket WHERE status = 'tersedia'";
$result = $conn->query($query);

// Pindahkan paket yang dipilih ke 'diambil'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    $ids = $_POST['ids']; // Mendapatkan ID paket yang dipilih
    $NIK = $_SESSION['NIK']; // NIK pamdal dari session

    // Pastikan ID paket adalah angka
    $idList = implode(",", array_map('intval', $ids));

    // Query untuk memindahkan paket ke status 'diambil'
    $sql = "UPDATE paket 
            SET status = 'diambil', tanggal_diambil = NOW(), NIK = ? 
            WHERE id_paket IN ($idList)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $NIK);

    if ($stmt->execute()) {
        echo "Data berhasil dipindahkan!";
    } else {
        echo "Error: " . $stmt->error;
    }
    echo "NIK Pamdal yang login: " . $_SESSION['NIK'];
    // Memindahkan paket yang sudah kadaluarsa
    $sqlKadaluarsa = "UPDATE paket 
                      SET status = 'kadaluwarsa' 
                      WHERE status = 'tersedia' 
                      AND DATEDIFF(CURDATE(), tanggal_daftar) > 7";

    if ($conn->query($sqlKadaluarsa) === TRUE) {
        echo "Paket yang sudah kadaluwarsa telah diperbarui.";
    } else {
        echo "Error: " . $conn->error;
    }

    // Segarkan halaman setelah pemindahan
    header("Location: tersedia.php");
    exit();
}

$conn->close();
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
    <link rel="stylesheet" href="style_tabel.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
  <body>
      <nav class="navbar navbar-expand-lg card login-form" >
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
                <h1 class="card-title mb-0">Daftar Paket Tersedia</h1>
                <div class="d-flex align-items-center gap-2">
                <div class="search-container">
                      <button class="btn btn-warning" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" onclick="toggleEditMode()">Edit</button>
                    </div>
                </form>
            </div>
        </div>
        <form method="POST" action="tersedia.php" id="paketForm">
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
                        <th>No.</th>
                        <th>Ekspedisi</th>
                        <th>Nomor Resi</th>
                        <th>Nama Paket</th>
                        <th>Nama Pemilik</th>
                        <th>No. HP Pemilik</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                    </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $statusDisplay = $row['status'] == 'tersedia' ? 'block' : 'none';
                            echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$row['ekspedisi']}</td>
                                    <td>{$row['nomor_resi']}</td>
                                    <td>{$row['nama_paket']}</td>
                                    <td>{$row['nama_pemilik']}</td>
                                    <td>{$row['no_hp_pemilik']}</td>
                                    <td>{$row['tanggal_daftar']}</td>
                                    <td>
                                        <span class='status-text' style='display:{$statusDisplay};'>{$row['status']}</span>
                                        <input type='checkbox' class='status-checkbox' name='ids[]' value='{$row['id_paket']}' style='display:none;' />
                                    </td>
                                  </tr>";
                                  $no++;
                        }
                    } else {
                        echo "<tr><td colspan='8'>Tidak ada data!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <script>
  let isEditMode = false; // Menandakan mode edit

    // Fungsi untuk mengaktifkan mode edit
    function toggleEditMode() {
    isEditMode = !isEditMode;
    const checkboxes = document.querySelectorAll('.status-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.style.display = isEditMode ? 'block' : 'none';
    });
    const statusCells = document.querySelectorAll('.status-text');
    statusCells.forEach(cell => {
        cell.style.display = isEditMode ? 'none' : 'block';
    });
    document.getElementById('saveBtn').style.display = isEditMode ? 'block' : 'none';
}
</script>
<script>  
  // Fungsi untuk menyimpan perubahan status
  function saveChanges() {
    const checkedBoxes = document.querySelectorAll('.status-checkbox:checked');
    const selectedIds = Array.from(checkedBoxes).map(checkbox => checkbox.getAttribute('data-id'));
    
    if (selectedIds.length > 0) {
      // Kirim ID yang dipilih ke PHP untuk dipindahkan ke tabel 'diambil'
      $.ajax({
        url: 'pindah_ke_diambil.php', // Halaman untuk memindahkan data
        method: 'POST',
        data: { ids: selectedIds },
        success: function(response) {
          alert('Data berhasil dipindahkan ke tabel Diambil!');
          location.reload(); // Segarkan halaman setelah sukses
        }
      });
    } else {
      alert('Pilih paket terlebih dahulu!');
    }
}
</script>
<script>// Memperbarui data tabel secara otomatis
function refreshTable() {
    $.ajax({
        url: 'tersedia.php', // Halaman untuk mengambil data terbaru
        success: function(response) {
            $('#data-table').html(response); // Menyisipkan hasil respons ke dalam elemen dengan ID data-table
        }
    });
}
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
      function customLogoutAlert() {
    return confirm('Anda yakin ingin keluar?');
    }
    </script>
    <script>
    $(document).ready(function() {
    // Konfirmasi sebelum memindahkan paket
    $('#paketForm').submit(function(e) {
        if (!confirm('Pastikan data paket yang dipilih benar. Apakah Anda yakin?')) {
            e.preventDefault(); // Membatalkan pengiriman form jika pengguna tidak yakin
        } else {
            $('<div class="info-message">Data paket telah berhasil diproses.</div>').insertAfter('#paketForm'); // Tambahkan informasi setelah form
        }
    });
});
</script>
  </body>
</html>
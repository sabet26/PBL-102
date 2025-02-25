<?php
session_start();
// Cek pesan sukses atau error
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-success'>{$_SESSION['message']}</div>";
    unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
}
// Pastikan pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['NIK']) || $_SESSION['role'] !== 'admin') {
    echo "Akses ditolak! Anda tidak memiliki izin untuk membuka halaman ini.";
    exit;
}

include 'koneksi.php';

// Fungsi untuk memeriksa dan menyaring input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Validasi dan Hashing Password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun Pamdal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="style_kelola_akun.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<nav class="navbar navbar-expand-lg card login-form">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <button onclick="openSidebar()" class="btn-side me-4">&#9776;</button>
            <img src="SIMBA with White Text Horizontal 2.png" class="img-fluid" alt="" width="190" height="65">
        </a>
        <a href="logout.php"><button type="button" class="btn btn-danger">LOG OUT</button></a>
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
            <h1 class="card-title mb-0">Daftar Akun Pamdal</h1>
            <div class="d-flex align-items-center gap-2">
                <div class="search-container">
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#addPamdalModal">Tambah Akun</button>
                </div>
            </div>
        </div>
    <div class="form" style="background-color:  rgb(224, 234, 244); border-radius:6px; margin:10px; padding:5px;">
        <div class="container mt-6 table-responsive">
            <table class="table table-bordered display" id="example" style="width:100%">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Pamdal</th>
                        <th>Aksi</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM pamdal";
                    $result = mysqli_query($koneksi, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$no}</td>";
                            echo "<td>{$row['NIK']}</td>";
                            echo "<td>{$row['nama_pamdal']}</td>";
                            echo "<td>{$row['role']}</td>";
                            echo "<td>
                                <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editPamdalModal' data-NIK='{$row['NIK']}' data-nama='{$row['nama_pamdal']}'>Edit</button>
                                <form method='POST' action='hapus_akun.php' style='display:inline'>
                                    <input type='hidden' name='NIK' value='{$row['NIK']}'>
                                    <button type='button' class='btn btn-danger btn-sm' onclick='confirmDelete(\"{$row['NIK']}\")'>Hapus</button>
                                </form>
                            </td>";
                            echo "</tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Tidak ada data.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>

<!-- Modal Tambah Pamdal -->
<div class="modal fade" id="addPamdalModal" tabindex="-1" aria-labelledby="addPamdalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPamdalModalLabel">Tambah Akun Pamdal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="tambah_akun.php" method="POST">   
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="NIK" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="NIK" name="NIK" required>
                        </div>
                        <div class="mb-3">
                            <label for="namaPamdal" class="form-label">Nama Pamdal</label>
                            <input type="text" class="form-control" id="namaPamdal" name="nama_pamdal" required>
                        </div>
                        <div class="mb-3">
                            <label for="kataSandi" class="form-label">Kata Sandi</label>
                            <input type="password" class="form-control" id="kataSandi" name="kata_sandi" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="pamdal">Pamdal</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-label">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>   
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pamdal -->
<div class="modal fade" id="editPamdalModal" tabindex="-1" aria-labelledby="editPamdalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPamdalModalLabel">Edit Akun Pamdal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="edit_akun.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="editNIKPamdal" name="NIK">
                    <div class="mb-3">
                        <label for="editNik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="editNik" name="NIK" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editNamaPamdal" class="form-label">Nama Pamdal</label>
                        <input type="text" class="form-control" id="editNamaPamdal" name="nama_pamdal" required>
                    </div>
                    <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="pamdal">Pamdal</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    <div class="form-label">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#example").DataTable(); // Inisialisasi DataTables

        $('#editPamdalModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const nik = button.data('nik');
        const namaPamdal = button.data('nama');

        $('#editNIKPamdal').val(nik);
        $('#editNik').val(nik);
        $('#editNamaPamdal').val(namaPamdal);

        console.log('NIK:', nik);
        console.log('Nama Pamdal:', namaPamdal);
    });
});

    function openSidebar() {
        document.getElementById("sidebar").style.width = "250px"; // Buka sidebar
        document.querySelector(".content").style.marginLeft = "250px"; // Sesuaikan margin
    }

    function closeSidebar() {
        document.getElementById("sidebar").style.width = "0"; // Tutup sidebar
        document.querySelector(".content").style.marginLeft = "0"; // Kembalikan margin
    }
</script>
<script>
// Fungsi konfirmasi penghapusan akun
function confirmDelete(nik) {
    Swal.fire({
        title: 'Apakah Anda yakin ingin menghapus akun ini?',
        text: "Akun yang dihapus tidak dapat dipulihkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika hapus dipilih, kirimkan form penghapusan
            window.location.href = "hapus_akun.php?NIK=" + nik;  // Redirect ke hapus_akun.php
        }
    });
}
</script>
</body>
</html>

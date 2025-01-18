<?php
include 'template/header.php';

// Pastikan sesi dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';
$level = isset($_SESSION['level']) ? $_SESSION['level'] : null;
$peron = isset($_SESSION['peron']) ? $_SESSION['peron'] : null;

if (!$id_user) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = 'index.php';</script>";
    exit();
}
?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include 'template/navbar.php'; ?>
        <?php include 'template/sidebar.php'; ?>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Pelanggan</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPelangganModal">Tambah Pelanggan</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Lengkap</th>
                                            <th>Alamat</th>
                                            <th>No HP</th>
                                            <th>Tanggal Terdaftar</th>
                                            <th>Jenis Pelanggan</th>
                                            <th>Status Aktif</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include 'koneksi.php';
                                        $query = "SELECT * FROM pelanggan 
                                                  WHERE create_by IN (SELECT id_user FROM user WHERE peron = ?) 
                                                  ORDER BY tanggal_terdaftar DESC";
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param('s', $peron);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            $no = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . $row['nama_lengkap'] . "</td>";
                                                echo "<td>" . $row['alamat'] . "</td>";
                                                echo "<td>" . $row['no_hp'] . "</td>";
                                                echo "<td>" . $row['tanggal_terdaftar'] . "</td>";
                                                echo "<td>" . ucfirst($row['jenis_pelanggan']) . "</td>";
                                                echo "<td>" . ucfirst($row['status_aktif']) . "</td>";
                                                echo "<td>
                                                    <a href='?edit_id=" . $row['id_pelanggan'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                                    <a href='?delete_id=" . $row['id_pelanggan'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>Belum ada data</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="app-footer">
        </footer>
    </div>

    <!-- Modal Tambah Pelanggan -->
    <div class="modal fade" id="tambahPelangganModal" tabindex="-1" aria-labelledby="tambahPelangganModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="tambah_pelanggan.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahPelangganModalLabel">Tambah Pelanggan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_terdaftar" class="form-label">Tanggal Terdaftar</label>
                            <input type="date" class="form-control" id="tanggal_terdaftar" name="tanggal_terdaftar" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_pelanggan" class="form-label">Jenis Pelanggan</label>
                            <select class="form-control" id="jenis_pelanggan" name="jenis_pelanggan" required>
                                <option value="individu">Individu</option>
                                <option value="perusahaan">Perusahaan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status_aktif" class="form-label">Status Aktif</label>
                            <select class="form-control" id="status_aktif" name="status_aktif" required>
                                <option value="aktif">Aktif</option>
                                <option value="non aktif">Non Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pelanggan -->
    <?php
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $query = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
    ?>
            <div class="modal fade show" id="editPelangganModal" tabindex="-1" aria-labelledby="editPelangganModalLabel" aria-modal="true" style="display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="edit_pelanggan.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPelangganModalLabel">Edit Pelanggan</h5>
                                <button type="button" class="btn-close" onclick="window.location.href='pelanggan.php'" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_pelanggan" value="<?php echo $data['id_pelanggan']; ?>">
                                <div class="mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama_lengkap" value="<?php echo $data['nama_lengkap']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="3" required><?php echo $data['alamat']; ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label">No HP</label>
                                    <input type="text" class="form-control" name="no_hp" value="<?php echo $data['no_hp']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_terdaftar" class="form-label">Tanggal Terdaftar</label>
                                    <input type="date" class="form-control" name="tanggal_terdaftar" value="<?php echo $data['tanggal_terdaftar']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_pelanggan" class="form-label">Jenis Pelanggan</label>
                                    <select class="form-control" name="jenis_pelanggan" required>
                                        <option value="individu" <?php if ($data['jenis_pelanggan'] === 'individu') echo 'selected'; ?>>Individu</option>
                                        <option value="perusahaan" <?php if ($data['jenis_pelanggan'] === 'perusahaan') echo 'selected'; ?>>Perusahaan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status_aktif" class="form-label">Status Aktif</label>
                                    <select class="form-control" name="status_aktif" required>
                                        <option value="aktif" <?php if ($data['status_aktif'] === 'aktif') echo 'selected'; ?>>Aktif</option>
                                        <option value="non aktif" <?php if ($data['status_aktif'] === 'non aktif') echo 'selected'; ?>>Non Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='pelanggan.php'">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        } else {
            echo "<script>alert('Data tidak ditemukan!'); window.location.href='pelanggan.php';</script>";
        }

        $stmt->close();
    }
    ?>

</body>

</html>
<?php
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM pelanggan WHERE id_pealanggan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href = 'pelanngan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $stmt->error . "'); window.location.href = 'pelanggan.php';</script>";
    }

    $stmt->close();
}
?>

</html>
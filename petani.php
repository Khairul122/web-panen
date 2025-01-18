<?php
include 'template/header.php';
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = 'index.php';</script>";
    exit();
}

$id_user = $_SESSION['id_user'];
$name = $_SESSION['name'];
$level = $_SESSION['level'];
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
                            <h3 class="mb-0">Petani</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPetaniModal">Tambah Petani</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Lengkap</th>
                                            <th>Alamat</th>
                                            <th>No HP</th>
                                            <th>Tanggal Gabung</th>
                                            <th>Status Keanggotaan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include 'koneksi.php';
                                        $peron = $_SESSION['peron']; 

                                        $query = "SELECT * FROM petani 
                                                  WHERE create_by IN (SELECT id_user FROM user WHERE peron = ?) 
                                                  ORDER BY tgl_gabung DESC";
                                        
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
                                                echo "<td>" . $row['tgl_gabung'] . "</td>";
                                                echo "<td>" . $row['status_keanggotaan'] . "</td>";
                                                echo "<td>
                                                    <a href='?edit_id=" . $row['id_petani'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                                    <a href='?delete_id=" . $row['id_petani'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>Belum ada data</td></tr>";
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

    <!-- Modal Tambah Petani -->
    <div class="modal fade" id="tambahPetaniModal" tabindex="-1" aria-labelledby="tambahPetaniModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="tambah_petani.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahPetaniModalLabel">Tambah Petani</h5>
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
                            <label for="tgl_gabung" class="form-label">Tanggal Gabung</label>
                            <input type="date" class="form-control" id="tgl_gabung" name="tgl_gabung" required>
                        </div>
                        <div class="mb-3">
                            <label for="status_keanggotaan" class="form-label">Status Keanggotaan</label>
                            <select class="form-control" id="status_keanggotaan" name="status_keanggotaan" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Non Aktif">Non Aktif</option>
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

    <!-- Modal Edit Petani -->
    <?php
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $query = "SELECT * FROM petani WHERE id_petani = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
    ?>
            <div class="modal fade show" id="editPetaniModal" tabindex="-1" aria-labelledby="editPetaniModalLabel" aria-modal="true" style="display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="proses_edit_petani.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPetaniModalLabel">Edit Petani</h5>
                                <button type="button" class="btn-close" onclick="window.location.href='petani.php'" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_petani" value="<?php echo $data['id_petani']; ?>">
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
                                    <label for="tgl_gabung" class="form-label">Tanggal Gabung</label>
                                    <input type="date" class="form-control" name="tgl_gabung" value="<?php echo $data['tgl_gabung']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status_keanggotaan" class="form-label">Status Keanggotaan</label>
                                    <select class="form-control" name="status_keanggotaan" required>
                                        <option value="Aktif" <?php if ($data['status_keanggotaan'] === 'Aktif') echo 'selected'; ?>>Aktif</option>
                                        <option value="Non Aktif" <?php if ($data['status_keanggotaan'] === 'Non Aktif') echo 'selected'; ?>>Non Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='petani.php'">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        } else {
            echo "<script>alert('Data tidak ditemukan!'); window.location.href='petani.php';</script>";
        }

        $stmt->close();
    }
    ?>
</body>

</html>
<?php
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM petani WHERE id_petani = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href = 'petani.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $stmt->error . "'); window.location.href = 'petani.php';</script>";
    }

    $stmt->close();
}
?>

</html>
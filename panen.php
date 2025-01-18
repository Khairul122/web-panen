<?php
include 'template/header.php';
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
                            <h3 class="mb-0">Panen</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPanenModal">Tambah Panen</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Panen</th>
                                            <th>Kebun</th>
                                            <th>Berat Hasil (Kg)</th>
                                            <th>Jumlah Tandan</th>
                                            <th>Kondisi Panen</th>
                                            <th>Catatan</th>
                                            <th>Dibuat Oleh</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include 'koneksi.php';
                                        $peron = $_SESSION['peron']; 

                                        $query = "SELECT panen.*, user.name 
                                            FROM panen 
                                            JOIN user ON panen.create_by = user.id_user 
                                            WHERE user.peron = ? 
                                            ORDER BY tanggal_panen DESC";
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param('s', $peron);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            $no = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . $row['tanggal_panen'] . "</td>";
                                                echo "<td>" . $row['kebun'] . "</td>";
                                                echo "<td>" . $row['berat_hasil'] . "</td>";
                                                echo "<td>" . $row['jumlah_tandan'] . "</td>";
                                                echo "<td>" . $row['kondisi_panen'] . "</td>";
                                                echo "<td>" . $row['catatan'] . "</td>";
                                                echo "<td>" . $row['name'] . "</td>";
                                                echo "<td>
                                                    <a href='panen.php?edit_id=" . $row['id_panen'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                                     <a href='?delete_id=" . $row['id_panen'] . "' 
                                                        class='btn btn-danger btn-sm' 
                                                        onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
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

    <!-- Insert Modal -->
    <div class="modal fade" id="tambahPanenModal" tabindex="-1" aria-labelledby="tambahPanenModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="tambah_panen.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahPanenModalLabel">Tambah Panen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal_panen" class="form-label">Tanggal Panen</label>
                            <input type="date" class="form-control" id="tanggal_panen" name="tanggal_panen" required>
                        </div>
                        <div class="mb-3">
                            <label for="kebun" class="form-label">Kebun</label>
                            <input type="text" class="form-control" id="kebun" name="kebun" required>
                        </div>
                        <div class="mb-3">
                            <label for="berat_hasil" class="form-label">Berat Hasil (Kg)</label>
                            <input type="number" step="0.01" class="form-control" id="berat_hasil" name="berat_hasil" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_tandan" class="form-label">Jumlah Tandan</label>
                            <input type="number" class="form-control" id="jumlah_tandan" name="jumlah_tandan" required>
                        </div>
                        <div class="mb-3">
                            <label for="kondisi_panen" class="form-label">Kondisi Panen</label>
                            <input type="text" class="form-control" id="kondisi_panen" name="kondisi_panen">
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
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

    <!-- Edit Modal -->
    <?php
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $query = "SELECT * FROM panen WHERE id_panen = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
    ?>
            <div class="modal fade show" id="editPanenModal" tabindex="-1" aria-labelledby="editPanenModalLabel" aria-modal="true" style="display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="edit_panen.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPanenModalLabel">Edit Panen</h5>
                                <button type="button" class="btn-close" onclick="window.location.href='home.php'" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_panen" value="<?php echo $data['id_panen']; ?>">
                                <div class="mb-3">
                                    <label for="tanggal_panen" class="form-label">Tanggal Panen</label>
                                    <input type="date" class="form-control" name="tanggal_panen" value="<?php echo $data['tanggal_panen']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kebun" class="form-label">Kebun</label>
                                    <input type="text" class="form-control" name="kebun" value="<?php echo $data['kebun']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="berat_hasil" class="form-label">Berat Hasil (Kg)</label>
                                    <input type="number" step="0.01" class="form-control" name="berat_hasil" value="<?php echo $data['berat_hasil']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah_tandan" class="form-label">Jumlah Tandan</label>
                                    <input type="number" class="form-control" name="jumlah_tandan" value="<?php echo $data['jumlah_tandan']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kondisi_panen" class="form-label">Kondisi Panen</label>
                                    <input type="text" class="form-control" name="kondisi_panen" value="<?php echo $data['kondisi_panen']; ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <textarea class="form-control" name="catatan" rows="3"><?php echo $data['catatan']; ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='panen.php'">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        } else {
            echo "<script>alert('Data tidak ditemukan!'); window.location.href='home.php';</script>";
        }

        $stmt->close();
    }
    ?>
</body>
<?php
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM panen WHERE id_panen = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href = 'panen.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $stmt->error . "'); window.location.href = 'panen.php';</script>";
    }

    $stmt->close();
}
?>

</html>
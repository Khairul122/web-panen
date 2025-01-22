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
                            <h3 class="mb-0">Distribusi</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDistribusiModal">Tambah Distribusi</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Distribusi</th>
                                            <th>Jumlah Distribusi</th>
                                            <th>Peron</th>
                                            <th>Tujuan</th>
                                            <th>No Kendaraan</th>
                                            <th>Supir</th>
                                            <th>Status Pengiriman</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include 'koneksi.php';
                                        $id_peron = $_SESSION['id_peron'];

                                        $query = "SELECT distribusi.*, peron.nama_peron 
                                        FROM distribusi 
                                        JOIN peron ON distribusi.id_peron = peron.id_peron
                                        WHERE distribusi.id_peron = ? 
                                        ORDER BY distribusi.tanggal_distribusi DESC";
                              

                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param('i', $id_peron);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            $no = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . $row['tanggal_distribusi'] . "</td>";
                                                echo "<td>" . $row['jumlah_distribusi'] . "</td>";
                                                echo "<td>" . $row['nama_peron'] . "</td>";
                                                echo "<td>" . $row['tujuan'] . "</td>";
                                                echo "<td>" . $row['no_kendaraan'] . "</td>";
                                                echo "<td>" . $row['supir'] . "</td>";
                                                echo "<td>" . $row['status_pengiriman'] . "</td>";
                                                echo "<td>
                <a href='?edit_id=" . $row['id_distribusi'] . "' class='btn btn-warning btn-sm'>Edit</a>
                <a href='?delete_id=" . $row['id_distribusi'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
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

    <!-- Modal Tambah Distribusi -->
    <div class="modal fade" id="tambahDistribusiModal" tabindex="-1" aria-labelledby="tambahDistribusiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="tambah_distribusi.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahDistribusiModalLabel">Tambah Distribusi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal_distribusi" class="form-label">Tanggal Distribusi</label>
                            <input type="date" class="form-control" id="tanggal_distribusi" name="tanggal_distribusi" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_distribusi" class="form-label">Jumlah Distribusi</label>
                            <input type="number" class="form-control" id="jumlah_distribusi" name="jumlah_distribusi" required>
                        </div>
                        <div class="mb-3">
                            <label for="tujuan" class="form-label">Tujuan</label>
                            <input type="text" class="form-control" id="tujuan" name="tujuan" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_kendaraan" class="form-label">No Kendaraan</label>
                            <input type="text" class="form-control" id="no_kendaraan" name="no_kendaraan" required>
                        </div>
                        <div class="mb-3">
                            <label for="supir" class="form-label">Supir</label>
                            <input type="text" class="form-control" id="supir" name="supir" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_peron" class="form-label">Nama Peron</label>
                            <select class="form-control" id="id_peron" name="id_peron" required>
                                <option value="" disabled selected>Pilih Peron</option>
                                <?php
                                include 'koneksi.php';
                                $query = "SELECT id_peron, nama_peron FROM peron ORDER BY nama_peron ASC";
                                $result = $conn->query($query);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id_peron'] . "'>" . $row['nama_peron'] . "</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>Data Peron Kosong</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status_pengiriman" class="form-label">Status Pengiriman</label>
                            <select class="form-control" id="status_pengiriman" name="status_pengiriman" required>
                                <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Gagal">Gagal</option>
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

    <!-- Modal Edit Distribusi -->
    <?php
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $query = "SELECT * FROM distribusi WHERE id_distribusi = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
    ?>
            <div class="modal fade show" id="editDistribusiModal" tabindex="-1" aria-labelledby="editDistribusiModalLabel" aria-modal="true" style="display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="edit_distribusi.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editDistribusiModalLabel">Edit Distribusi</h5>
                                <button type="button" class="btn-close" onclick="window.location.href='distribusi.php'" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_distribusi" value="<?php echo $data['id_distribusi']; ?>">
                                <div class="mb-3">
                                    <label for="tanggal_distribusi" class="form-label">Tanggal Distribusi</label>
                                    <input type="date" class="form-control" name="tanggal_distribusi" value="<?php echo $data['tanggal_distribusi']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tujuan" class="form-label">Tujuan</label>
                                    <input type="text" class="form-control" name="tujuan" value="<?php echo $data['tujuan']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_kendaraan" class="form-label">No Kendaraan</label>
                                    <input type="text" class="form-control" name="no_kendaraan" value="<?php echo $data['no_kendaraan']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="supir" class="form-label">Supir</label>
                                    <input type="text" class="form-control" name="supir" value="<?php echo $data['supir']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah_distribusi" class="form-label">Jumlah Distribusi</label>
                                    <input type="number" class="form-control" name="jumlah_distribusi" value="<?php echo $data['jumlah_distribusi']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status_pengiriman" class="form-label">Status Pengiriman</label>
                                    <select class="form-control" name="status_pengiriman" required>
                                        <option value="Dalam Perjalanan" <?php if ($data['status_pengiriman'] === 'Dalam Perjalanan') echo 'selected'; ?>>Dalam Perjalanan</option>
                                        <option value="Selesai" <?php if ($data['status_pengiriman'] === 'Selesai') echo 'selected'; ?>>Selesai</option>
                                        <option value="Gagal" <?php if ($data['status_pengiriman'] === 'Gagal') echo 'selected'; ?>>Gagal</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_peron" class="form-label">Nama Peron</label>
                                    <select class="form-control" name="id_peron" required>
                                        <?php
                                        include 'koneksi.php';
                                        $query = "SELECT id_peron, nama_peron FROM peron ORDER BY nama_peron ASC";
                                        $result = $conn->query($query);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = ($row['id_peron'] == $data['id_peron']) ? 'selected' : '';
                                                echo "<option value='" . $row['id_peron'] . "' $selected>" . $row['nama_peron'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value='' disabled>Data Peron Kosong</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='distribusi.php'">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        } else {
            echo "<script>alert('Data tidak ditemukan!'); window.location.href='distribusi.php';</script>";
        }

        $stmt->close();
    }
    ?>
</body>
<?php
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM distribusi WHERE id_distribusi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href = 'distribusi.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $stmt->error . "'); window.location.href = 'distribusi.php';</script>";
    }

    $stmt->close();
}
?>

</html>
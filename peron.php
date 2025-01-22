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
                            <h3 class="mb-0">Kelola Peron</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPeronModal">Tambah Peron</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Peron</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM peron ORDER BY id_peron ASC";
                                        $result = $conn->query($query);

                                        if ($result->num_rows > 0) {
                                            $no = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . $row['nama_peron'] . "</td>";
                                                echo "<td>
                                                    <a href='?edit_id=" . $row['id_peron'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                                    <a href='?delete_id=" . $row['id_peron'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3' class='text-center'>Belum ada data</td></tr>";
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

    <!-- Modal Tambah Peron -->
    <div class="modal fade" id="tambahPeronModal" tabindex="-1" aria-labelledby="tambahPeronModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="tambah_peron.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahPeronModalLabel">Tambah Peron</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_peron" class="form-label">Nama Peron</label>
                            <input type="text" class="form-control" id="nama_peron" name="nama_peron" required>
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

    <!-- Modal Edit Peron -->
    <?php
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $query = "SELECT * FROM peron WHERE id_peron = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
    ?>
            <div class="modal fade show" id="editPeronModal" tabindex="-1" aria-labelledby="editPeronModalLabel" aria-modal="true" style="display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="edit_peron.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPeronModalLabel">Edit Peron</h5>
                                <button type="button" class="btn-close" onclick="window.location.href='peron.php'" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_peron" value="<?php echo $data['id_peron']; ?>">
                                <div class="mb-3">
                                    <label for="nama_peron" class="form-label">Nama Peron</label>
                                    <input type="text" class="form-control" name="nama_peron" value="<?php echo $data['nama_peron']; ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='peron.php'">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        } else {
            echo "<script>alert('Data tidak ditemukan!'); window.location.href='peron.php';</script>";
        }

        $stmt->close();
    }
    ?>
</body>

</html>
<?php
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM peron WHERE id_peron = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href = 'peron.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $stmt->error . "'); window.location.href = 'peron.php';</script>";
    }

    $stmt->close();
}
?>

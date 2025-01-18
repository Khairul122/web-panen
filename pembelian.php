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
                            <h3 class="mb-0">Pembelian</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPembelianModal">Tambah Pembelian</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Pembelian</th>
                                            <th>Nama Pembeli</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga Satuan</th>
                                            <th>Total Harga</th>
                                            <th>Metode Pembayaran</th>
                                            <th>Status Pembayaran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include 'koneksi.php';
                                        $query = "SELECT * FROM pembelian 
                                                  WHERE create_by IN (SELECT id_user FROM user WHERE peron = ?) 
                                                  ORDER BY tanggal_pembelian DESC";
                                        $stmt = $conn->prepare($query);
                                        $stmt->bind_param('s', $peron);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            $no = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $no++ . "</td>";
                                                echo "<td>" . $row['tanggal_pembelian'] . "</td>";
                                                echo "<td>" . $row['nama_pembeli'] . "</td>";
                                                echo "<td>" . $row['nama_produk'] . "</td>";
                                                echo "<td>" . $row['jumlah'] . "</td>";
                                                echo "<td>" . number_format($row['harga_satuan'], 2) . "</td>";
                                                echo "<td>" . number_format($row['total_harga'], 2) . "</td>";
                                                echo "<td>" . ucfirst($row['metode_pembayaran']) . "</td>";
                                                echo "<td>" . ucfirst($row['status_pembayaran']) . "</td>";
                                                echo "<td>
                                                    <a href='?edit_id=" . $row['id_pembelian'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                                    <a href='?delete_id=" . $row['id_pembelian'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='10' class='text-center'>Belum ada data</td></tr>";
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

    <!-- Modal Tambah Pembelian -->
    <div class="modal fade" id="tambahPembelianModal" tabindex="-1" aria-labelledby="tambahPembelianModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="tambah_pembelian.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahPembelianModalLabel">Tambah Pembelian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                            <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_pembeli" class="form-label">Nama Pembeli</label>
                            <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_satuan" class="form-label">Harga Satuan</label>
                            <input type="number" step="0.01" class="form-control" id="harga_satuan" name="harga_satuan" required>
                        </div>
                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                            <select class="form-control" id="metode_pembayaran" name="metode_pembayaran" required>
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                            <select class="form-control" id="status_pembayaran" name="status_pembayaran" required>
                                <option value="selesai">Selesai</option>
                                <option value="dp">DP</option>
                                <option value="pending">Pending</option>
                            </select>
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
</body>

<!-- Modal Edit -->
<?php
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $query = "SELECT * FROM pembelian WHERE id_pembelian = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        ?>
        <div class="modal fade show" id="editPembelianModal" tabindex="-1" aria-labelledby="editPembelianModalLabel" aria-modal="true" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="edit_pembelian.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPembelianModalLabel">Edit Pembelian</h5>
                            <button type="button" class="btn-close" onclick="window.location.href='pembelian.php'" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_pembelian" value="<?php echo $data['id_pembelian']; ?>">
                            <div class="mb-3">
                                <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                                <input type="date" class="form-control" name="tanggal_pembelian" value="<?php echo $data['tanggal_pembelian']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama_pembeli" class="form-label">Nama Pembeli</label>
                                <input type="text" class="form-control" name="nama_pembeli" value="<?php echo $data['nama_pembeli']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" name="nama_produk" value="<?php echo $data['nama_produk']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" name="jumlah" value="<?php echo $data['jumlah']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="harga_satuan" class="form-label">Harga Satuan</label>
                                <input type="number" step="0.01" class="form-control" name="harga_satuan" value="<?php echo $data['harga_satuan']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                <select class="form-control" name="metode_pembayaran" required>
                                    <option value="tunai" <?php if ($data['metode_pembayaran'] === 'tunai') echo 'selected'; ?>>Tunai</option>
                                    <option value="transfer" <?php if ($data['metode_pembayaran'] === 'transfer') echo 'selected'; ?>>Transfer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                                <select class="form-control" name="status_pembayaran" required>
                                    <option value="selesai" <?php if ($data['status_pembayaran'] === 'selesai') echo 'selected'; ?>>Selesai</option>
                                    <option value="dp" <?php if ($data['status_pembayaran'] === 'dp') echo 'selected'; ?>>DP</option>
                                    <option value="pending" <?php if ($data['status_pembayaran'] === 'pending') echo 'selected'; ?>>Pending</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan" rows="3"><?php echo $data['catatan']; ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='pembelian.php'">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='pembelian.php';</script>";
    }

    $stmt->close();
}
?>


</html>
<?php
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM pembelian WHERE id_pembelian = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href = 'pembelian.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $stmt->error . "'); window.location.href = 'pembelian.php';</script>";
    }

    $stmt->close();
}

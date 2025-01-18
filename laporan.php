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
                            <h3 class="mb-0">Dashboard</h3>
                        </div>
                        <div class="col-sm-6 text-end">
                            <p class="mb-0">Welcome, <b><?php echo $name; ?></b>!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <!-- Laporan Hasil Panen -->
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title">Laporan Hasil Panen</h5>
                            </div>
                            <div class="card-body">
                                <!-- Form Filter -->
                                <form method="GET" action="" class="row g-3">
                                    <div class="col-md-3">
                                        <label for="tanggal" class="form-label">Tanggal</label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="bulan" class="form-label">Bulan</label>
                                        <select class="form-control" id="bulan" name="bulan">
                                            <option value="">Pilih Bulan</option>
                                            <?php
                                            for ($i = 1; $i <= 12; $i++) {
                                                $bulan = str_pad($i, 2, "0", STR_PAD_LEFT);
                                                $selected = (isset($_GET['bulan']) && $_GET['bulan'] == $bulan) ? 'selected' : '';
                                                echo "<option value='$bulan' $selected>$bulan</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tahun" class="form-label">Tahun</label>
                                        <input type="number" class="form-control" id="tahun" name="tahun" placeholder="Masukkan Tahun" value="<?php echo isset($_GET['tahun']) ? $_GET['tahun'] : ''; ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="peron" class="form-label">Peron</label>
                                        <select class="form-control" id="peron" name="peron">
                                            <option value="">Pilih Peron</option>
                                            <?php
                                            include 'koneksi.php';
                                            $queryPeron = "SELECT DISTINCT peron FROM user WHERE peron IS NOT NULL";
                                            $resultPeron = $conn->query($queryPeron);
                                            while ($rowPeron = $resultPeron->fetch_assoc()) {
                                                $selected = (isset($_GET['peron']) && $_GET['peron'] == $rowPeron['peron']) ? 'selected' : '';
                                                echo "<option value='" . $rowPeron['peron'] . "' $selected>" . $rowPeron['peron'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                                        <button type="button" class="btn btn-success" onclick="window.open('laporan_panen.php?<?php echo http_build_query($_GET); ?>', '_blank')">Cetak</button>
                                    </div>
                                </form>
                                <!-- Tabel Data -->
                                <div class="table-responsive mt-4">
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
                                                <th>Penanggung Jawab</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Query Filter
                                            $query = "
                                                SELECT panen.*, user.name 
                                                FROM panen 
                                                JOIN user ON panen.create_by = user.id_user 
                                                WHERE 1=1";
                                            $params = [];

                                            if (!empty($_GET['tanggal'])) {
                                                $query .= " AND DATE(tanggal_panen) = ?";
                                                $params[] = $_GET['tanggal'];
                                            }
                                            if (!empty($_GET['bulan'])) {
                                                $query .= " AND MONTH(tanggal_panen) = ?";
                                                $params[] = $_GET['bulan'];
                                            }
                                            if (!empty($_GET['tahun'])) {
                                                $query .= " AND YEAR(tanggal_panen) = ?";
                                                $params[] = $_GET['tahun'];
                                            }
                                            if (!empty($_GET['peron'])) {
                                                $query .= " AND create_by IN (SELECT id_user FROM user WHERE peron = ?)";
                                                $params[] = $_GET['peron'];
                                            }

                                            $stmt = $conn->prepare($query);

                                            if (!empty($params)) {
                                                $stmt->bind_param(str_repeat('s', count($params)), ...$params);
                                            }

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
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center'>Tidak ada data</td></tr>";
                                            }

                                            $stmt->close();
                                            $conn->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Laporan Distrubusi -->
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title">Laporan Distribusi</h5>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="" class="row g-3">
                                    <div class="col-md-3">
                                        <label for="tanggal_distribusi" class="form-label">Tanggal</label>
                                        <input type="date" class="form-control" id="tanggal_distribusi" name="tanggal_distribusi" value="<?php echo isset($_GET['tanggal_distribusi']) ? $_GET['tanggal_distribusi'] : ''; ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="bulan_distribusi" class="form-label">Bulan</label>
                                        <select class="form-control" id="bulan_distribusi" name="bulan_distribusi">
                                            <option value="">Pilih Bulan</option>
                                            <?php
                                            for ($i = 1; $i <= 12; $i++) {
                                                $bulan = str_pad($i, 2, "0", STR_PAD_LEFT);
                                                $selected = (isset($_GET['bulan_distribusi']) && $_GET['bulan_distribusi'] == $bulan) ? 'selected' : '';
                                                echo "<option value='$bulan' $selected>$bulan</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tahun_distribusi" class="form-label">Tahun</label>
                                        <input type="number" class="form-control" id="tahun_distribusi" name="tahun_distribusi" placeholder="Masukkan Tahun" value="<?php echo isset($_GET['tahun_distribusi']) ? $_GET['tahun_distribusi'] : ''; ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="peron" class="form-label">Peron</label>
                                        <select class="form-control" id="peron" name="peron">
                                            <option value="">Pilih Peron</option>
                                            <?php
                                            include 'koneksi.php';
                                            $queryPeron = "SELECT DISTINCT peron FROM user WHERE peron IS NOT NULL";
                                            $resultPeron = $conn->query($queryPeron);
                                            while ($rowPeron = $resultPeron->fetch_assoc()) {
                                                $selected = (isset($_GET['peron']) && $_GET['peron'] == $rowPeron['peron']) ? 'selected' : '';
                                                echo "<option value='" . $rowPeron['peron'] . "' $selected>" . $rowPeron['peron'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                                        <button type="button" class="btn btn-success" onclick="window.open('laporan_distribusi.php?<?php echo http_build_query($_GET); ?>', '_blank')">Cetak</button>
                                    </div>
                                </form>
                                <!-- Tabel Data Distribusi -->
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered">
                                        <thead>
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal Distribusi</th>
                                                    <th>Peron</th>
                                                    <th>Jumlah Distribusi</th>
                                                    <th>No Kendaraan</th>
                                                    <th>Supir</th>
                                                    <th>Status Pengiriman</th>
                                                    <th>Penanggung Jawab</th>
                                                </tr>
                                            </thead>
                                        </thead>
                                        <tbody>
                                            <?php
                                            include 'koneksi.php';

                                            // Query untuk mengambil data distribusi
                                            $queryDistribusi = "
                                                SELECT distribusi.*, user.name AS peron_name 
                                                FROM distribusi
                                                JOIN user ON distribusi.create_by = user.id_user
                                                WHERE 1=1";
                                            $paramsDistribusi = [];

                                            if (!empty($_GET['tanggal_distribusi'])) {
                                                $queryDistribusi .= " AND DATE(tanggal_distribusi) = ?";
                                                $paramsDistribusi[] = $_GET['tanggal_distribusi'];
                                            }
                                            if (!empty($_GET['bulan_distribusi'])) {
                                                $queryDistribusi .= " AND MONTH(tanggal_distribusi) = ?";
                                                $paramsDistribusi[] = $_GET['bulan_distribusi'];
                                            }
                                            if (!empty($_GET['tahun_distribusi'])) {
                                                $queryDistribusi .= " AND YEAR(tanggal_distribusi) = ?";
                                                $paramsDistribusi[] = $_GET['tahun_distribusi'];
                                            }
                                            if (!empty($_GET['peron'])) {
                                                $queryDistribusi .= " AND user.peron = ?";
                                                $paramsDistribusi[] = $_GET['peron'];
                                            }

                                            $stmtDistribusi = $conn->prepare($queryDistribusi);
                                            if (!empty($paramsDistribusi)) {
                                                $stmtDistribusi->bind_param(str_repeat('s', count($paramsDistribusi)), ...$paramsDistribusi);
                                            }
                                            $stmtDistribusi->execute();
                                            $resultDistribusi = $stmtDistribusi->get_result();

                                            if ($resultDistribusi->num_rows > 0) {
                                                $noDistribusi = 1;
                                                while ($rowDistribusi = $resultDistribusi->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . $noDistribusi++ . "</td>";
                                                    echo "<td>" . $rowDistribusi['tanggal_distribusi'] . "</td>";
                                                    echo "<td>" . $rowDistribusi['tujuan'] . "</td>";
                                                    echo "<td>" . $rowDistribusi['no_kendaraan'] . "</td>";
                                                    echo "<td>" . $rowDistribusi['supir'] . "</td>";
                                                    echo "<td>" . $rowDistribusi['jumlah_distribusi'] . "</td>";
                                                    echo "<td>" . $rowDistribusi['status_pengiriman'] . "</td>";
                                                    echo "<td>" . $rowDistribusi['peron_name'] . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='8' class='text-center'>Tidak ada data</td></tr>";
                                            }

                                            $stmtDistribusi->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="card-title">Laporan Pembelian</h5>
                                        </div>
                                        <div class="card-body">
                                            <!-- Form Filter Pembelian -->
                                            <form method="GET" action="" class="row g-3">
                                                <div class="col-md-3">
                                                    <label for="tanggal_pembelian" class="form-label">Tanggal</label>
                                                    <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" value="<?php echo isset($_GET['tanggal_pembelian']) ? $_GET['tanggal_pembelian'] : ''; ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="bulan_pembelian" class="form-label">Bulan</label>
                                                    <select class="form-control" id="bulan_pembelian" name="bulan_pembelian">
                                                        <option value="">Pilih Bulan</option>
                                                        <?php
                                                        for ($i = 1; $i <= 12; $i++) {
                                                            $bulan = str_pad($i, 2, "0", STR_PAD_LEFT);
                                                            $selected = (isset($_GET['bulan_pembelian']) && $_GET['bulan_pembelian'] == $bulan) ? 'selected' : '';
                                                            echo "<option value='$bulan' $selected>$bulan</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="tahun_pembelian" class="form-label">Tahun</label>
                                                    <input type="number" class="form-control" id="tahun_pembelian" name="tahun_pembelian" placeholder="Masukkan Tahun" value="<?php echo isset($_GET['tahun_pembelian']) ? $_GET['tahun_pembelian'] : ''; ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="peron" class="form-label">Peron</label>
                                                    <select class="form-control" id="peron" name="peron">
                                                        <option value="">Pilih Peron</option>
                                                        <?php
                                                        include 'koneksi.php';
                                                        $queryPeron = "SELECT DISTINCT peron FROM user WHERE peron IS NOT NULL";
                                                        $resultPeron = $conn->query($queryPeron);
                                                        while ($rowPeron = $resultPeron->fetch_assoc()) {
                                                            $selected = (isset($_GET['peron']) && $_GET['peron'] == $rowPeron['peron']) ? 'selected' : '';
                                                            echo "<option value='" . $rowPeron['peron'] . "' $selected>" . $rowPeron['peron'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                                    <button type="button" class="btn btn-success" onclick="window.open('laporan_pembelian.php?<?php echo http_build_query($_GET); ?>', '_blank')">Cetak</button>
                                                </div>
                                            </form>
                                            <!-- Tabel Data Pembelian -->
                                            <div class="table-responsive mt-4">
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
                                                            <th>Catatan</th>
                                                            <th>Penanggung Jawab</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $queryPembelian = "
                                                            SELECT pembelian.*, user.peron, user.name
                                                            FROM pembelian
                                                            JOIN user ON pembelian.create_by = user.id_user
                                                            WHERE 1=1";
                                                        $paramsPembelian = [];

                                                        if (!empty($_GET['tanggal_pembelian'])) {
                                                            $queryPembelian .= " AND DATE(tanggal_pembelian) = ?";
                                                            $paramsPembelian[] = $_GET['tanggal_pembelian'];
                                                        }
                                                        if (!empty($_GET['bulan_pembelian'])) {
                                                            $queryPembelian .= " AND MONTH(tanggal_pembelian) = ?";
                                                            $paramsPembelian[] = $_GET['bulan_pembelian'];
                                                        }
                                                        if (!empty($_GET['tahun_pembelian'])) {
                                                            $queryPembelian .= " AND YEAR(tanggal_pembelian) = ?";
                                                            $paramsPembelian[] = $_GET['tahun_pembelian'];
                                                        }
                                                        if (!empty($_GET['peron'])) {
                                                            $queryPembelian .= " AND user.peron = ?";
                                                            $paramsPembelian[] = $_GET['peron'];
                                                        }

                                                        $stmtPembelian = $conn->prepare($queryPembelian);

                                                        if (!empty($paramsPembelian)) {
                                                            $stmtPembelian->bind_param(str_repeat('s', count($paramsPembelian)), ...$paramsPembelian);
                                                        }

                                                        $stmtPembelian->execute();
                                                        $resultPembelian = $stmtPembelian->get_result();

                                                        if ($resultPembelian->num_rows > 0) {
                                                            $noPembelian = 1;
                                                            while ($rowPembelian = $resultPembelian->fetch_assoc()) {
                                                                echo "<tr>";
                                                                echo "<td>" . $noPembelian++ . "</td>";
                                                                echo "<td>" . $rowPembelian['tanggal_pembelian'] . "</td>";
                                                                echo "<td>" . $rowPembelian['nama_pembeli'] . "</td>";
                                                                echo "<td>" . $rowPembelian['nama_produk'] . "</td>";
                                                                echo "<td>" . $rowPembelian['jumlah'] . "</td>";
                                                                echo "<td>" . $rowPembelian['harga_satuan'] . "</td>";
                                                                echo "<td>" . $rowPembelian['total_harga'] . "</td>";
                                                                echo "<td>" . $rowPembelian['metode_pembayaran'] . "</td>";
                                                                echo "<td>" . $rowPembelian['status_pembayaran'] . "</td>";
                                                                echo "<td>" . $rowPembelian['catatan'] . "</td>";
                                                                echo "<td>" . $rowPembelian['name'] . "</td>";
                                                                echo "</tr>";
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='9' class='text-center'>Tidak ada data</td></tr>";
                                                        }

                                                        $stmtPembelian->close();
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </main>
    <footer class="app-footer">
    </footer>
    </div>
</body>

</html>
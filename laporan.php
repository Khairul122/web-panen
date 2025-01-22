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
                                            <option value="" disabled selected>Pilih Peron</option>
                                            <?php
                                            $query = "SELECT id_peron, nama_peron FROM peron ORDER BY nama_peron ASC";
                                            $result = $conn->query($query);
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='{$row['id_peron']}'>{$row['nama_peron']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                                        <button type="button" class="btn btn-success" onclick="window.open('laporan_panen.php?<?php echo http_build_query($_GET); ?>', '_blank')">Cetak</button>
                                    </div>
                                </form>
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

                                            $query = "
                                                SELECT panen.*, user.name, peron.nama_peron 
                                                FROM panen 
                                                JOIN user ON panen.create_by = user.id_user 
                                                JOIN peron ON panen.id_peron = peron.id_peron";
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
                                                $query .= " AND peron.id_peron = ?";
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
                                                    echo "<td>" . htmlspecialchars($row['tanggal_panen']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_peron']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['kebun']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['berat_hasil']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['jumlah_tandan']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['kondisi_panen']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['catatan']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='9' class='text-center'>Tidak ada data</td></tr>";
                                            }

                                            $stmt->close();
                                            $conn->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title">Laporan Distribusi</h5>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="">
                                        <div class="row mb-4 align-items-end">
                                            <div class="col-md-2">
                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : ''; ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="bulan" class="form-label">Bulan</label>
                                                <select class="form-control" id="bulan" name="bulan">
                                                    <option value="">Pilih Bulan</option>
                                                    <?php
                                                    for ($i = 1; $i <= 12; $i++) {
                                                        $selected = (isset($_GET['bulan']) && $_GET['bulan'] == $i) ? 'selected' : '';
                                                        echo "<option value='$i' $selected>" . date('F', mktime(0, 0, 0, $i, 10)) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tahun" class="form-label">Tahun</label>
                                                <input type="number" class="form-control" id="tahun" name="tahun" value="<?php echo isset($_GET['tahun']) ? $_GET['tahun'] : ''; ?>" placeholder="Masukkan Tahun">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="id_peron" class="form-label">Peron</label>
                                                <select class="form-control" id="id_peron" name="id_peron">
                                                    <option value="">Pilih Peron</option>
                                                    <?php
                                                    include 'koneksi.php';
                                                    $queryPeron = "SELECT id_peron, nama_peron FROM peron ORDER BY nama_peron ASC";
                                                    $resultPeron = $conn->query($queryPeron);

                                                    if ($resultPeron->num_rows > 0) {
                                                        while ($peron = $resultPeron->fetch_assoc()) {
                                                            $selected = (isset($_GET['id_peron']) && $_GET['id_peron'] == $peron['id_peron']) ? 'selected' : '';
                                                            echo "<option value='" . $peron['id_peron'] . "' $selected>" . $peron['nama_peron'] . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 d-flex">
                                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                                <button type="button" class="btn btn-success" onclick="window.open('laporan_distribusi.php?<?php echo http_build_query($_GET); ?>', '_blank')">Cetak</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive mt-4">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal Distribusi</th>
                                                    <th>Nama Peron</th>
                                                    <th>Tujuan</th>
                                                    <th>No Kendaraan</th>
                                                    <th>Supir</th>
                                                    <th>Jumlah Distribusi</th>
                                                    <th>Status Pengiriman</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $conditions = [];
                                                $params = [];
                                                $types = '';

                                                // Filter berdasarkan input
                                                if (!empty($_GET['tanggal'])) {
                                                    $conditions[] = "DATE(distribusi.tanggal_distribusi) = ?";
                                                    $params[] = $_GET['tanggal'];
                                                    $types .= 's';
                                                }

                                                if (!empty($_GET['bulan'])) {
                                                    $conditions[] = "MONTH(distribusi.tanggal_distribusi) = ?";
                                                    $params[] = $_GET['bulan'];
                                                    $types .= 'i';
                                                }

                                                if (!empty($_GET['tahun'])) {
                                                    $conditions[] = "YEAR(distribusi.tanggal_distribusi) = ?";
                                                    $params[] = $_GET['tahun'];
                                                    $types .= 'i';
                                                }

                                                if (!empty($_GET['id_peron'])) { // Ganti filter menjadi id_peron
                                                    $conditions[] = "distribusi.id_peron = ?";
                                                    $params[] = $_GET['id_peron'];
                                                    $types .= 'i';
                                                }

                                                // Membuat query dengan kondisi
                                                $query = "SELECT distribusi.*, peron.id_peron, peron.nama_peron 
              FROM distribusi 
              JOIN peron ON distribusi.id_peron = peron.id_peron";

                                                if (!empty($conditions)) {
                                                    $query .= " WHERE " . implode(' AND ', $conditions);
                                                }

                                                $query .= " ORDER BY distribusi.tanggal_distribusi DESC";

                                                $stmt = $conn->prepare($query);

                                                if ($stmt) {
                                                    if (!empty($conditions)) {
                                                        $stmt->bind_param($types, ...$params);
                                                    }
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();

                                                    if ($result->num_rows > 0) {
                                                        $no = 1;
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>";
                                                            echo "<td>" . $no++ . "</td>";
                                                            echo "<td>" . $row['tanggal_distribusi'] . "</td>";
                                                            echo "<td>" . $row['nama_peron'] . "</td>";
                                                            echo "<td>" . $row['tujuan'] . "</td>";
                                                            echo "<td>" . $row['no_kendaraan'] . "</td>";
                                                            echo "<td>" . $row['supir'] . "</td>";
                                                            echo "<td>" . $row['jumlah_distribusi'] . "</td>";
                                                            echo "<td>" . $row['status_pengiriman'] . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='8' class='text-center'>Belum ada data distribusi.</td></tr>";
                                                    }

                                                    $stmt->close();
                                                } else {
                                                    echo "<tr><td colspan='8' class='text-center'>Terjadi kesalahan pada query.</td></tr>";
                                                }

                                                $conn->close();
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

        </main>

    </div>
</body>

</html>
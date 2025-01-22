<?php
include 'template/header.php';
include 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';
$level = isset($_SESSION['level']) ? $_SESSION['level'] : null;
$id_peron_session = isset($_SESSION['id_peron']) ? $_SESSION['id_peron'] : null;

if (!$id_user) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = 'index.php';</script>";
    exit();
}

$peronData = [];

if ($level == 2) {
    $query = "SELECT id_peron, nama_peron FROM peron";
    $resultPeron = $conn->query($query);

    while ($row = $resultPeron->fetch_assoc()) {
        $id_peron = $row['id_peron'];

        $dataQuery = $conn->prepare("
            SELECT 
                DATE_FORMAT(tanggal_panen, '%Y-%m') AS bulan, 
                kebun, 
                SUM(berat_hasil) AS total_berat, 
                SUM(jumlah_tandan) AS total_tandan
            FROM panen 
            WHERE id_peron = ? 
            GROUP BY bulan, kebun 
            ORDER BY bulan
        ");
        $dataQuery->bind_param('i', $id_peron);
        $dataQuery->execute();
        $resultData = $dataQuery->get_result();

        $data = [];
        while ($dataRow = $resultData->fetch_assoc()) {
            $data[] = $dataRow;
        }

        $peronData[$row['nama_peron']] = $data;
    }
} else {
    $dataQuery = $conn->prepare("
        SELECT 
            DATE_FORMAT(tanggal_panen, '%Y-%m') AS bulan, 
            kebun, 
            SUM(berat_hasil) AS total_berat, 
            SUM(jumlah_tandan) AS total_tandan
        FROM panen 
        WHERE id_peron = ? 
        GROUP BY bulan, kebun 
        ORDER BY bulan
    ");
    $dataQuery->bind_param('i', $id_peron_session);
    $dataQuery->execute();
    $resultData = $dataQuery->get_result();

    $data = [];
    while ($dataRow = $resultData->fetch_assoc()) {
        $data[] = $dataRow;
    }

    $queryPeronName = $conn->prepare("SELECT nama_peron FROM peron WHERE id_peron = ?");
    $queryPeronName->bind_param('i', $id_peron_session);
    $queryPeronName->execute();
    $resultPeronName = $queryPeronName->get_result();
    $peronName = $resultPeronName->fetch_assoc()['nama_peron'] ?? 'Peron Tidak Diketahui';

    $peronData[$peronName] = $data;
}
?>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include 'template/navbar.php'; ?>
        <?php include 'template/sidebar.php'; ?>
        <main class="app-main">
            <div class="app-content-header">

            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <?php foreach ($peronData as $peronName => $data): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title"><?php echo $peronName; ?></h5>
                            </div>
                            <div class="card-body">
                                <canvas id="chart-<?php echo str_replace(' ', '-', $peronName); ?>" style="height: 400px;"></canvas>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
        <footer class="app-footer">
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const peronData = <?php echo json_encode($peronData); ?>;

        Object.keys(peronData).forEach(peronName => {
            const data = peronData[peronName];

            const labels = data.map(item => item.bulan);
            const beratData = data.map(item => item.total_berat);
            const tandanData = data.map(item => item.total_tandan);

            const ctx = document.getElementById(`chart-${peronName.replace(/\s+/g, '-')}`).getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Total Berat Hasil (kg)',
                            data: beratData,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 2
                        },
                        {
                            label: 'Total Jumlah Tandan',
                            data: tandanData,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: `Grafik Panen - ${peronName}`
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
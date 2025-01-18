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
                            <h3 class="mb-0">Halaman Utama</h3>
                        </div>
                        <div class="col-sm-6 text-end">
                            <p class="mb-0">Welcome, <b><?php echo $name; ?></b>!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="text-center mt-5">
                    <h1 style="font-size: 54px; font-weight: bold;">
                        PERANCANGAN WEBSITE PEMANTAUAN HASIL PANEN DAN DISTRIBUSI HARIAN BUAH KELAPA SAWIT PADA UD GALANG
                    </h1>
                    <br>
                    <h2 style="font-size: 38px;">
                        Oleh :
                    </h2>
                    <h2 style="font-size: 42px; font-weight: bold;">
                        MESA KAMELIA <br>
                        21101152610434
                    </h2>
                </div>
            </div>
        </main>
        <footer class="app-footer">
        </footer>
    </div>
</body>

</html>
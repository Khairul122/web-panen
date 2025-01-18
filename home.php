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

                </div>
            </div>
            <div class="app-content">
                <div class="text-center mt-5">
                    <img src="src/images/sawit.jpg" alt="Centered Image"
                        style="max-width: 100%; height: auto; margin-bottom: 20px; margin-top: 7rem; transform: scale(2);">
                    <br>
                    <div class="app-content">
                        <div class="text-center mt-5">
                            <h1 style="font-size: 30px; font-weight: bold; margin-top:9rem;">
                                Selamat datang, <b><?php echo htmlspecialchars($name); ?></b>
                                di halaman <?php echo $level == 1 ? "administrator" : ($level == 2 ? "pimpinan" : ""); ?>
                                sistem informasi pemantauan hasil panen dan distribusi kelapa sawit.
                                Melalui halaman ini dapat dilakukan pengelolaan data hasil panen dan distribusi kelapa sawit serta pengelolaan laporan hasil panen dan pendistribusian kelapa sawit.
                            </h1>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </main>
        <footer class="app-footer">
        </footer>
    </div>
</body>

</html>
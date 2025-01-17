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
                            <h3 class="mb-0">Dashboard</h3>
                        </div>
                        <div class="col-sm-6 text-end">
                            <p class="mb-0">Welcome, <b><?php echo $name; ?></b>!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <!-- Content utama di sini -->
            </div>
        </main>
        <footer class="app-footer">
        </footer>
    </div>
</body>

</html>
<!-- Sidebar Component -->
<aside class="app-sidebar shadow" style="background-color:rgb(83, 175, 255); color: white;" data-bs-theme="dark">
    <!-- Brand Logo -->
    <div class="sidebar-brand">
        <a href="./index.html" class="brand-link" style="color: white;">
            <span class="brand-text fw-light">UD GALANG</span>
        </a>
    </div>

    <!-- Sidebar Navigation -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!-- Navigation Items -->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <?php if ($level == 1): ?>
                    <!-- Home -->
                    <li class="nav-item">
                        <a href="home.php" class="nav-link">
                            <i class="nav-icon bi bi-house-fill"></i>
                            <p>Halaman Utama</p>
                        </a>
                    </li>

                    <!-- Harvest -->
                    <li class="nav-item">
                        <a href="panen.php" class="nav-link">
                            <i class="nav-icon bi bi-basket-fill"></i>
                            <p>Panen</p>
                        </a>
                    </li>

                    <!-- Distribution -->
                    <li class="nav-item">
                        <a href="distribusi.php" class="nav-link">
                            <i class="nav-icon bi bi-truck"></i>
                            <p>Distribusi</p>
                        </a>
                    </li>

                    <!-- Farmers -->
                    <li class="nav-item">
                        <a href="petani.php" class="nav-link">
                            <i class="nav-icon bi bi-person-fill"></i>
                            <p>Petani</p>
                        </a>
                    </li>

                    <!-- Customers -->
                    <li class="nav-item">
                        <a href="pelanggan.php" class="nav-link">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>Pelanggan</p>
                        </a>
                    </li>

                    <!-- Suppliers -->
                    <li class="nav-item">
                        <a href="supplier.php" class="nav-link">
                            <i class="nav-icon bi bi-shop"></i>
                            <p>Supplier</p>
                        </a>
                    </li>

                    <!-- Purchases -->
                    <li class="nav-item">
                        <a href="pembelian.php" class="nav-link">
                            <i class="nav-icon bi bi-cart-fill"></i>
                            <p>Pembelian</p>
                        </a>
                    </li>
                <?php elseif ($level == 2): ?>
                    <!-- Home -->
                    <li class="nav-item">
                        <a href="home.php" class="nav-link">
                            <i class="nav-icon bi bi-house-fill"></i>
                            <p>Halaman Utama</p>
                        </a>
                    </li>
                    
                    <!-- Manage User -->
                    <li class="nav-item">
                        <a href="user.php" class="nav-link">
                            <i class="nav-icon bi bi-gear-fill"></i>
                            <p>Kelola User</p>
                        </a>
                    </li>
                  
                    <!-- Print Laporan -->
                    <li class="nav-item">
                        <a href="laporan.php" class="nav-link">
                            <i class="nav-icon bi bi-file-earmark-arrow-down"></i>
                            <p>Laporan</p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>
    .app-sidebar .nav-link {
        color: white;
    }
    .app-sidebar .nav-link:hover {
        color: #cce5ff;
    }
    .app-sidebar .sidebar-brand a {
        color: white;
    }
</style>

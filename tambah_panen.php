<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_panen = $_POST['tanggal_panen'];
    $kebun = $_POST['kebun'];
    $berat_hasil = $_POST['berat_hasil'];
    $jumlah_tandan = $_POST['jumlah_tandan'];
    $kondisi_panen = $_POST['kondisi_panen'];
    $catatan = $_POST['catatan'];
    $create_by = $_SESSION['id_user'];

    $query = "INSERT INTO panen (tanggal_panen, kebun, berat_hasil, jumlah_tandan, kondisi_panen, catatan, create_by) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdiisi', $tanggal_panen, $kebun, $berat_hasil, $jumlah_tandan, $kondisi_panen, $catatan, $create_by);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href = 'panen.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "'); window.location.href = 'panen.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

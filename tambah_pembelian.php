<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $nama_pembeli = $_POST['nama_pembeli'];
    $nama_produk = $_POST['nama_produk'];
    $jumlah = $_POST['jumlah'];
    $harga_satuan = $_POST['harga_satuan'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status_pembayaran = $_POST['status_pembayaran'];
    $catatan = $_POST['catatan'];
    $create_by = $_SESSION['id_user'];

    $query = "INSERT INTO pembelian (tanggal_pembelian, nama_pembeli, nama_produk, jumlah, harga_satuan, metode_pembayaran, status_pembayaran, catatan, create_by) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssiisssi', $tanggal_pembelian, $nama_pembeli, $nama_produk, $jumlah, $harga_satuan, $metode_pembayaran, $status_pembayaran, $catatan, $create_by);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href = 'pembelian.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "'); window.location.href = 'pembelian.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

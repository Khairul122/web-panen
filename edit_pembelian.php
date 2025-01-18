<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pembelian = $_POST['id_pembelian'];
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $nama_pembeli = $_POST['nama_pembeli'];
    $nama_produk = $_POST['nama_produk'];
    $jumlah = $_POST['jumlah'];
    $harga_satuan = $_POST['harga_satuan'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status_pembayaran = $_POST['status_pembayaran'];
    $catatan = $_POST['catatan'];

    $query = "UPDATE pembelian 
              SET tanggal_pembelian = ?, nama_pembeli = ?, nama_produk = ?, jumlah = ?, harga_satuan = ?, metode_pembayaran = ?, status_pembayaran = ?, catatan = ? 
              WHERE id_pembelian = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssiisssi', $tanggal_pembelian, $nama_pembeli, $nama_produk, $jumlah, $harga_satuan, $metode_pembayaran, $status_pembayaran, $catatan, $id_pembelian);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'pembelian.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "'); window.location.href = 'pembelian.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

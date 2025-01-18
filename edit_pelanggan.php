<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pelanggan = $_POST['id_pelanggan'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $tanggal_terdaftar = $_POST['tanggal_terdaftar'];
    $jenis_pelanggan = $_POST['jenis_pelanggan'];
    $status_aktif = $_POST['status_aktif'];

    $query = "UPDATE pelanggan 
              SET nama_lengkap = ?, alamat = ?, no_hp = ?, tanggal_terdaftar = ?, jenis_pelanggan = ?, status_aktif = ? 
              WHERE id_pelanggan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssi', $nama_lengkap, $alamat, $no_hp, $tanggal_terdaftar, $jenis_pelanggan, $status_aktif, $id_pelanggan);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'pelanggan.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "'); window.location.href = 'pelanggan.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

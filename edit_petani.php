<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_petani = $_POST['id_petani'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $tgl_gabung = $_POST['tgl_gabung'];
    $status_keanggotaan = $_POST['status_keanggotaan'];

    $query = "UPDATE petani 
              SET nama_lengkap = ?, alamat = ?, no_hp = ?, tgl_gabung = ?, status_keanggotaan = ? 
              WHERE id_petani = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssi', $nama_lengkap, $alamat, $no_hp, $tgl_gabung, $status_keanggotaan, $id_petani);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'petani.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "'); window.location.href = 'petani.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

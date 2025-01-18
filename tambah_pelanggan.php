<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $tanggal_terdaftar = $_POST['tanggal_terdaftar'];
    $jenis_pelanggan = $_POST['jenis_pelanggan'];
    $status_aktif = $_POST['status_aktif'];
    $create_by = $_SESSION['id_user'];

    $query = "INSERT INTO pelanggan (nama_lengkap, alamat, no_hp, tanggal_terdaftar, jenis_pelanggan, status_aktif, create_by) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssi', $nama_lengkap, $alamat, $no_hp, $tanggal_terdaftar, $jenis_pelanggan, $status_aktif, $create_by);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href = 'pelanggan.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "'); window.location.href = 'pelanggan.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

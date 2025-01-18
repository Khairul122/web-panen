<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $tgl_gabung = $_POST['tgl_gabung'];
    $status_keanggotaan = $_POST['status_keanggotaan'];
    $create_by = $_SESSION['id_user'];

    $query = "INSERT INTO petani (nama_lengkap, alamat, no_hp, tgl_gabung, status_keanggotaan, create_by) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssi', $nama_lengkap, $alamat, $no_hp, $tgl_gabung, $status_keanggotaan, $create_by);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href = 'petani.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "'); window.location.href = 'petani.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

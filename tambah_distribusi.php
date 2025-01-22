<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_distribusi = $_POST['tanggal_distribusi'];
    $tujuan = $_POST['tujuan'];
    $jumlah_distribusi = $_POST['jumlah_distribusi'];
    $no_kendaraan = $_POST['no_kendaraan'];
    $supir = $_POST['supir'];
    $status_pengiriman = $_POST['status_pengiriman'];
    $id_peron = $_POST['id_peron']; 
    $create_by = $_SESSION['id_user'];

    $query = "INSERT INTO distribusi (tanggal_distribusi, tujuan, no_kendaraan, supir, jumlah_distribusi, status_pengiriman, id_peron, create_by) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssisis', $tanggal_distribusi, $tujuan, $no_kendaraan, $supir, $jumlah_distribusi, $status_pengiriman, $id_peron, $create_by);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href = 'distribusi.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "'); window.location.href = 'distribusi.php';</script>";
    }

    $stmt->close();
    $conn->close();
}

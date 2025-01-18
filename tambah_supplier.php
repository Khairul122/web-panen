<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_supplier = $_POST['nama_supplier'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $create_by = $_SESSION['id_user'];

    $query = "INSERT INTO supplier (nama_supplier, alamat, no_hp, email, status, create_by) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssi', $nama_supplier, $alamat, $no_hp, $email, $status, $create_by);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href = 'supplier.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . $stmt->error . "'); window.location.href = 'supplier.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

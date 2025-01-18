<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_supplier = $_POST['id_supplier'];
    $nama_supplier = $_POST['nama_supplier'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    $query = "UPDATE supplier 
              SET nama_supplier = ?, alamat = ?, no_hp = ?, email = ?, status = ? 
              WHERE id_supplier = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssi', $nama_supplier, $alamat, $no_hp, $email, $status, $id_supplier);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'supplier.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "'); window.location.href = 'supplier.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

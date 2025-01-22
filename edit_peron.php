<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_peron = $_POST['id_peron']; 
    $nama_peron = $_POST['nama_peron']; 

    $query = "UPDATE peron SET nama_peron = ? WHERE id_peron = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $nama_peron, $id_peron);

    if ($stmt->execute()) {
        echo "<script>alert('Data peron berhasil diperbarui!'); window.location.href = 'peron.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data peron: " . $stmt->error . "'); window.location.href = 'peron.php';</script>";
    }

    $stmt->close();
}
?>

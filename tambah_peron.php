<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_peron = $_POST['nama_peron']; 

    $query = "INSERT INTO peron (nama_peron) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $nama_peron);

    if ($stmt->execute()) {
        echo "<script>alert('Data peron berhasil ditambahkan!'); window.location.href = 'peron.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data peron: " . $stmt->error . "'); window.location.href = 'peron.php';</script>";
    }

    $stmt->close();
}
?>

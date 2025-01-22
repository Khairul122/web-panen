<?php
session_start();
include 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal_panen = $_POST['tanggal_panen'];
    $kebun = $_POST['kebun'];
    $berat_hasil = $_POST['berat_hasil'];
    $id_peron = $_POST['id_peron'];
    $jumlah_tandan = $_POST['jumlah_tandan'];
    $kondisi_panen = $_POST['kondisi_panen'];
    $catatan = isset($_POST['catatan']) ? $_POST['catatan'] : null; 
    $create_by = $_SESSION['id_user']; 

    $query = "INSERT INTO panen (tanggal_panen, kebun, berat_hasil, id_peron, jumlah_tandan, kondisi_panen, catatan, create_by) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssdiissi", $tanggal_panen, $kebun, $berat_hasil, $id_peron, $jumlah_tandan, $kondisi_panen, $catatan, $create_by);
        if ($stmt->execute()) {
            echo "<script>alert('Data panen berhasil ditambahkan!'); window.location.href='panen.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data panen: " . $stmt->error . "'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Terjadi kesalahan pada database.'); window.history.back();</script>";
    }
    $conn->close();
} else {
    echo "<script>alert('Invalid Request'); window.history.back();</script>";
}
?>

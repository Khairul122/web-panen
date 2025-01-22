<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Anda belum login.'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_panen = $_POST['id_panen'];
    $tanggal_panen = $_POST['tanggal_panen'];
    $kebun = $_POST['kebun'];
    $berat_hasil = $_POST['berat_hasil'];
    $id_peron = $_POST['id_peron'];
    $jumlah_tandan = $_POST['jumlah_tandan'];
    $kondisi_panen = $_POST['kondisi_panen'];
    $catatan = isset($_POST['catatan']) ? $_POST['catatan'] : null; 
    $update_by = $_SESSION['id_user'];

    $query = "UPDATE panen 
              SET tanggal_panen = ?, 
                  kebun = ?, 
                  berat_hasil = ?, 
                  id_peron = ?, 
                  jumlah_tandan = ?, 
                  kondisi_panen = ?, 
                  catatan = ?, 
                  create_by = ? 
              WHERE id_panen = ?";

    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssdiissii", $tanggal_panen, $kebun, $berat_hasil, $id_peron, $jumlah_tandan, $kondisi_panen, $catatan, $update_by, $id_panen);

        if ($stmt->execute()) {
            echo "<script>alert('Data panen berhasil diperbarui!'); window.location.href='panen.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui data panen: " . $stmt->error . "'); window.history.back();</script>";
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

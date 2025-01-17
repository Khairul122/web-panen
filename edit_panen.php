<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_panen = $_POST['id_panen'];
    $tanggal_panen = $_POST['tanggal_panen'];
    $kebun = $_POST['kebun'];
    $berat_hasil = $_POST['berat_hasil'];
    $jumlah_tandan = $_POST['jumlah_tandan'];
    $kondisi_panen = $_POST['kondisi_panen'];
    $catatan = $_POST['catatan'];

    $query = "UPDATE panen SET tanggal_panen = ?, kebun = ?, berat_hasil = ?, jumlah_tandan = ?, kondisi_panen = ?, catatan = ? WHERE id_panen = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdiisi', $tanggal_panen, $kebun, $berat_hasil, $jumlah_tandan, $kondisi_panen, $catatan, $id_panen);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'panen.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "'); window.location.href = 'panen.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

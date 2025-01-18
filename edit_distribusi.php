<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_distribusi = $_POST['id_distribusi'];
    $tanggal_distribusi = $_POST['tanggal_distribusi'];
    $tujuan = $_POST['tujuan'];
    $no_kendaraan = $_POST['no_kendaraan'];
    $supir = $_POST['supir'];
    $jumlah_distribusi = $_POST['jumlah_distribusi'];
    $status_pengiriman = $_POST['status_pengiriman'];
    
    if (empty($id_distribusi) || empty($tanggal_distribusi) || empty($tujuan) || empty($no_kendaraan) || empty($supir) || empty($jumlah_distribusi) || empty($status_pengiriman)) {
        echo "<script>alert('Harap isi semua data!'); window.location.href = 'distribusi.php';</script>";
        exit;
    }

    $query = "UPDATE distribusi 
              SET tanggal_distribusi = ?, tujuan = ?, no_kendaraan = ?, supir = ?, jumlah_distribusi = ?, status_pengiriman = ? 
              WHERE id_distribusi = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('ssssisi', $tanggal_distribusi, $tujuan, $no_kendaraan, $supir, $jumlah_distribusi, $status_pengiriman, $id_distribusi);

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'distribusi.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "'); window.location.href = 'distribusi.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Gagal mempersiapkan statement: " . $conn->error . "'); window.location.href = 'distribusi.php';</script>";
    }

    $conn->close();
}
?>

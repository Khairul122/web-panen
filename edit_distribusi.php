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
    $id_peron = $_POST['id_peron'];

    // Query untuk memperbarui data
    $query = "UPDATE distribusi 
              SET tanggal_distribusi = ?, 
                  tujuan = ?, 
                  no_kendaraan = ?, 
                  supir = ?, 
                  jumlah_distribusi = ?, 
                  status_pengiriman = ?, 
                  id_peron = ?
              WHERE id_distribusi = ?";

    // Siapkan statement
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param(
            'ssssissi',
            $tanggal_distribusi,
            $tujuan,
            $no_kendaraan,
            $supir,
            $jumlah_distribusi,
            $status_pengiriman,
            $id_peron,
            $id_distribusi
        );

        // Eksekusi statement
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'distribusi.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "'); window.location.href = 'distribusi.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Gagal mempersiapkan query: " . $conn->error . "'); window.location.href = 'distribusi.php';</script>";
    }

    // Tutup koneksi
    $conn->close();
} else {
    echo "<script>alert('Akses tidak diizinkan!'); window.location.href = 'distribusi.php';</script>";
}

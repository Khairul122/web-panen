<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $peron = $_POST['peron'] ? $_POST['peron'] : null;
    $password = $_POST['password'];
    $level = $_POST['level'];

    if (!empty($password)) {
        $query = "UPDATE user 
                  SET name = ?, username = ?, peron = ?, password = SHA(?), level = ? 
                  WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssii', $name, $username, $peron, $password, $level, $id_user);
    } else {
        $query = "UPDATE user 
                  SET name = ?, username = ?, peron = ?, level = ? 
                  WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssii', $name, $username, $peron, $level, $id_user);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'user.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . $stmt->error . "'); window.location.href = 'user.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

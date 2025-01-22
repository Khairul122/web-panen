<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $id_peron = !empty($_POST['id_peron']) ? $_POST['id_peron'] : null; 
    $password = $_POST['password'];
    $level = $_POST['level'];

    if (!empty($password)) {
        $query = "UPDATE user 
                  SET name = ?, username = ?, id_peron = ?, password = SHA1(?), level = ? 
                  WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param('ssissi', $name, $username, $id_peron, $password, $level, $id_user);
    } else {
        $query = "UPDATE user 
                  SET name = ?, username = ?, id_peron = ?, level = ? 
                  WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param('ssiii', $name, $username, $id_peron, $level, $id_user);
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

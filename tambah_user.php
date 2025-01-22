<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $id_peron = !empty($_POST['id_peron']) ? $_POST['id_peron'] : null;
    $password = $_POST['password'];
    $level = $_POST['level'];

    $query = "INSERT INTO user (name, username, id_peron, password, level) VALUES (?, ?, ?, SHA1(?), ?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param('ssisi', $name, $username, $id_peron, $password, $level);

    if ($stmt->execute()) {
        echo "<script>alert('User berhasil ditambahkan!'); window.location.href = 'user.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan user: " . $stmt->error . "'); window.location.href = 'user.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

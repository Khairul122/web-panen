<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $peron = $_POST['peron'] ? $_POST['peron'] : null;
    $password = $_POST['password'];
    $level = $_POST['level'];

    $query = "INSERT INTO user (name, username, peron, password, level) VALUES (?, ?, ?, SHA(?), ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssi', $name, $username, $peron, $password, $level);

    if ($stmt->execute()) {
        echo "<script>alert('User berhasil ditambahkan!'); window.location.href = 'user.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan user: " . $stmt->error . "'); window.location.href = 'user.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

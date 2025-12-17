<?php
session_start();

// Hapus token di database
if (isset($_SESSION['user_id'])) {
    include 'koneksi.php';

    $sql = "UPDATE user
            SET remember_token = NULL, remember_expire = NULL
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
}

// Hapus cookie
setcookie("remember_me", "", time() - 3600, "/");

// Hapus session
$_SESSION = [];
session_destroy();

header("Location: ../index.php");
exit;

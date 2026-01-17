<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'] ?? $_SESSION['id'];

    $layanan_id = $_POST['layanan_id'];
    $tanggal    = $_POST['tanggal'];
    $jam        = $_POST['jam'];
    $status     = 'pending';

    $sql = "INSERT INTO janji (user_id, layanan_id, tanggal, jam, status) 
            VALUES ('$user_id', '$layanan_id', '$tanggal', '$jam', '$status')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../public/history.php?status=success");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    header("Location: ../page/dashboard.php");
}

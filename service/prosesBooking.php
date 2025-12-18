<?php
session_start();
include 'koneksi.php'; // Pastikan path ini benar ke file koneksi databasemu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    // Pastikan session user_id sudah diset saat login di file prosesLogin.php
    // Jika belum, gunakan: $user_id = $_SESSION['id']; (sesuaikan dengan nama session loginmu)
    $user_id = $_SESSION['user_id'] ?? $_SESSION['id']; 
    
    $layanan_id = $_POST['layanan_id'];
    $tanggal    = $_POST['tanggal'];
    $jam        = $_POST['jam'];
    $status     = 'pending'; // Default status sesuai struktur DB

    // Query Insert ke tabel janji
    $sql = "INSERT INTO janji (user_id, layanan_id, tanggal, jam, status) 
            VALUES ('$user_id', '$layanan_id', '$tanggal', '$jam', '$status')";

    if ($conn->query($sql) === TRUE) {
        // Jika sukses, arahkan ke halaman riwayat
        header("Location: ../public/history.php?status=success");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Jika akses langsung tanpa POST
    header("Location: ../page/dashboard.php");
}
?>
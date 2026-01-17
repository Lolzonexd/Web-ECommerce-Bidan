<?php
session_start();
include '../service/koneksi.php';

// Cek login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../public/login.php");
    exit;
}

if (isset($_POST['update_profile'])) {
    // Pastikan session ID user tersedia
    $id = $_SESSION['user_id'] ?? $_SESSION['id'];

    $username = $conn->real_escape_string($_POST['username']);
    $email    = $conn->real_escape_string($_POST['email']);

    $nama_lengkap  = $conn->real_escape_string($_POST['nama_lengkap']);
    $nik           = $conn->real_escape_string($_POST['nik']);
    $tgl_lahir     = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_hp         = $conn->real_escape_string($_POST['no_hp']);
    $alamat        = $conn->real_escape_string($_POST['alamat']);

    $sqlUser = "UPDATE user SET username = '$username', email = '$email' WHERE id = '$id'";
    $conn->query($sqlUser);

    $_SESSION['username'] = $username;

    $checkBio = $conn->query("SELECT id FROM biodata WHERE user_id = '$id'");

    if ($checkBio->num_rows > 0) {
        $sqlBio = "UPDATE biodata SET 
                   nama_lengkap = '$nama_lengkap',
                   nik = '$nik',
                   tanggal_lahir = '$tgl_lahir',
                   jenis_kelamin = '$jenis_kelamin',
                   no_hp = '$no_hp',
                   alamat = '$alamat'
                   WHERE user_id = '$id'";
    } else {
        $sqlBio = "INSERT INTO biodata (user_id, nama_lengkap, nik, tanggal_lahir, jenis_kelamin, no_hp, alamat)
                   VALUES ('$id', '$nama_lengkap', '$nik', '$tgl_lahir', '$jenis_kelamin', '$no_hp', '$alamat')";
    }

    if ($conn->query($sqlBio)) {
        header("Location: ../public/profile.php?status=success");
    } else {
        header("Location: ../public/profile.php?status=error&msg=" . urlencode($conn->error));
    }
}

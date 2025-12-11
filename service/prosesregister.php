<?php
session_start();
include 'koneksi.php';

// Cek Input dari page/register.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['email'], ENT_QUOTES, 'UTF-8'));
    $level = 'user';
    $password = $_POST['password'];

    // Cek Panjang Password
    if (strlen($password) < 8) {
        $_SESSION['registration_error'] = "Password minimal 8 karakter.";
        header("Location: ../page/register.php");
        exit;
    }

    // Cek Email
    $sqlEmail = "SELECT email 
                FROM user 
                WHERE email = ?";

    $check = $conn->prepare($sqlEmail);
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['registration_error'] = "Email sudah terdaftar.";
        header("Location: ../page/register.php");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);


    $sql = "INSERT INTO user (username, email, password, level) 
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Database Error
    if (!$stmt) {
        $_SESSION['registration_error'] = "Terjadi Kesalahan pada server, coba beberapa saat lagi: ";
        header("Location: ../page/register.php");
        exit;
    }

    $stmt->bind_param("ssss", $username, $email, $hashed_password, $level);

    // Pendaftaran Berhasil
    if ($stmt->execute()) {
        $_SESSION['registration_success'] = "Pendaftaran berhasil! Silakan login.";
        header("Location: ../page/login.php");
        exit();
    } else {
        $_SESSION['registration_error'] = "Terjadi Kesalahan pada server, coba beberapa saat lagi: ";
        header("Location: ../page/register.php");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../page/register.php");
    exit();
}

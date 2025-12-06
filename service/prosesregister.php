<?php
session_start();
    
include 'koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $level = 'user'; 
    
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO user (username, email, password, level) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $level);
    
    if ($stmt->execute()) {
        $_SESSION['registration_success'] = "Pendaftaran berhasil! Silakan login.";
        header("Location: ../page/login.php"); 
        exit();
    } else {
        echo "Error saat pendaftaran: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: ../page/register.php"); 
    exit();
}
?>
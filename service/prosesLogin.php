<?php
session_start();
include 'koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];
    
    $sql = "SELECT id, username, email, password, level FROM user WHERE username = ? OR email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        

        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];
            
            header("Location: ../index.php"); 
            exit;
        } else {
            $_SESSION['login_error'] = "Username/Email atau Password salah.";
        }
    } else {
        $_SESSION['login_error'] = "Username/Email tidak ditemukan.";
    }
    
    $stmt->close();
    $conn->close();

    header("Location: ../page/login.php");
    exit;

} else {
    header("Location: ../page/login.php");
    exit;
}
?>
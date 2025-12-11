<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="../asset/style.css">
</head>
<body>
    <h2>Login your account</h2>
    
    <?php
    if (isset($_SESSION['registration_success'])) {
        echo "<p style='color:green;'>".$_SESSION['registration_success']."</p>";
        unset($_SESSION['registration_success']);
    }
    if (isset($_SESSION['login_error'])) {
        echo "<p style='color:red;'>".$_SESSION['login_error']."</p>";
        unset($_SESSION['login_error']);
    }
    ?>

    <form action="../service/proseslogin.php" method="POST"> 
        <label for="identifier">Username atau Email:</label>
        <input type="text" id="identifier" name="identifier" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
    <p>Belum punya akun?</p>
    <a href="register.php">Daftar di sini</a> 
</body>
</html>
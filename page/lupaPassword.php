<?php
session_start();
include '../helper/redirectIfLoggedIn.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - PMB Nurhasanah</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../asset/style.css">
</head>
<body>
    <header>
        <div class="logo"><i class="fas fa-heartbeat"></i> PMB Nurhasanah</div>
        <nav><ul><li><a href="login.php">Kembali ke Login</a></li></ul></nav>
    </header>

    <div class="auth-wrapper">
        <div class="auth-box">
            <div class="auth-header">
                <h2>Reset Password</h2>
                <p>Masukkan email yang terdaftar untuk mereset kata sandi.</p>
            </div>

            <?php
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-error' style='background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;'>" . htmlspecialchars($_SESSION['error']) . "</div>";
                unset($_SESSION['error']);
            }
            ?>

            <form action="../service/prosesLupaPassword.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Terdaftar</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="contoh@email.com" required>
                </div>
                <button type="submit" class="btn-submit">Kirim Link Reset</button>
            </form>
        </div>
    </div>
    <?php include '../layout/footer.html'; ?>
</body>
</html>
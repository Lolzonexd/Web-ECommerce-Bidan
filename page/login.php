<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PMB Nurhasanah</title>
    <!-- POPINS FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- AWESOME FONT -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="../asset/style.css">
</head>

<body>
    <!-- NAVBAR -->
    <header>
        <div class="logo">
            <i class="fas fa-heartbeat"></i> PMB Nurhasanah
        </div>
        <nav>
            <ul>
                <li><a href="../index.php">Kembali ke Beranda</a></li>
            </ul>
        </nav>
    </header>
    <!-- END NAVBAR -->

    <!-- CARD LOGIN -->
    <div class="auth-wrapper">
        <div class="auth-box">
            <div class="auth-header">
                <h2>Selamat Datang</h2>
                <p>Silakan masuk untuk melanjutkan</p>
            </div>

            <?php
            if (isset($_SESSION['registration_success'])) {
                echo "<div class='alert alert-success'>" . $_SESSION['registration_success'] . "</div>";
                unset($_SESSION['registration_success']);
            }
            if (isset($_SESSION['login_error'])) {
                echo "<div class='alert alert-error'>" . $_SESSION['login_error'] . "</div>";
                unset($_SESSION['login_error']);
            }
            ?>

            <form action="../service/proseslogin.php" method="POST">
                <div class="form-group">
                    <label for="identifier">Username atau Email</label>
                    <input type="text" id="identifier" name="identifier" class="form-control" placeholder="Masukkan username/email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn-submit">Login</button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </div>
        </div>
    </div>
    <!-- END CARD LOGIN -->

    <!-- FOOTER -->
    <?php include '../layout/footer.html'; ?>

</body>

</html>
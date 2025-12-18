<?php
session_start();
include '../helper/authChecker.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - PMB Nurhasanah</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
</head>

<body>
    <nav class="navbar-dashboard">
        <a href="dashboard.php" class="brand">
            <i class="fas fa-heartbeat"></i> PMB Nurhasanah
        </a>

        <div class="nav-right">
            <span class="user-greeting">
                Halo, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
            </span>
            <a href="../service/logout.php" class="btn-logout-nav">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <div class="dashboard-container">

        <div class="welcome-banner">
            <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Anda telah berhasil login ke Sistem Pelayanan Bidan. Silakan pilih menu di bawah ini untuk melanjutkan.</p>
        </div>

        <div class="menu-section">
            <h3>Menu Navigasi</h3>

            <div class="menu-grid">

                <div class="menu-card">
                    <div>
                        <h4>Buat Janji Baru</h4>
                        <p>Daftar layanan pemeriksaan kehamilan, imunisasi, atau KB secara online.</p>
                    </div>
                    <a href="booking_form.php" class="btn-card">Masuk</a>
                </div>

                <div class="menu-card">
                    <div>
                        <h4>Riwayat Booking</h4>
                        <p>Lihat status janji temu Anda dan riwayat pemeriksaan sebelumnya.</p>
                    </div>
                    <a href="history.php" class="btn-card">Masuk</a>
                </div>

                <div class="menu-card">
                    <div>
                        <h4>Pembayaran</h4>
                        <p>Konfirmasi pembayaran atau upload bukti transfer layanan.</p>
                    </div>
                    <a href="payment_list.php" class="btn-card">Masuk</a>
                </div>

                <div class="menu-card">
                    <div>
                        <h4>Data Profil</h4>
                        <p>Kelola data diri, alamat, dan informasi akun Anda.</p>
                    </div>
                    <a href="profile.php" class="btn-card">Masuk</a>
                </div>

            </div>
        </div>

    </div>

    <?php include '../layout/footer.html'; ?>
</body>

</html>
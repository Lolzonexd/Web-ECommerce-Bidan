<?php
session_start();
include '../service/koneksi.php'; // Panggil database untuk ambil daftar layanan

// Cek Login & Level Admin
if (!isset($_SESSION['loggedin']) || $_SESSION['level'] !== 'admin') {
    header("location: ../page/login.php");
    exit;
}

// Ambil Daftar Layanan Aktif untuk dijadikan Menu
$queryLayanan = $conn->query("SELECT * FROM layanan WHERE aktif=1");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - PMB Nurhasanah</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
</head>

<body>

    <nav class="navbar-dashboard">
        <a href="dashboard_admin.php" class="brand"><i class="fas fa-user-nurse"></i> Mode Atmin</a>
        <div class="nav-right">
            <span class="user-greeting">Halo, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span>
            <a href="../service/logout.php" class="btn-logout-nav"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>

    <div class="dashboard-container">

        <div class="welcome-banner" style="border-left-color: #e64848;">
            <h2>Selamat Datang, Administrator!</h2>
            <p>Pilih kategori layanan di bawah ini untuk mengelola data pasien/janji temu.</p>
        </div>

        <div class="menu-section">

            <h3 style="margin-top:0;">Master Data</h3>
            <div class="menu-grid" style="margin-bottom: 40px;">
                <div class="menu-card" style="border-left: 5px solid #333;">
                    <div>
                        <h4><i class="fas fa-database"></i> Master Layanan</h4>
                        <p>Tambah/Edit jenis layanan & harga.</p>
                    </div>
                    <a href="managePelayanan.php" class="btn-card" style="background-color: #333;">Atur Layanan</a>
                </div>
                <div class="menu-card" style="border-left: 5px solid #333;">
                    <div>
                        <h4><i class="fas fa-users"></i> Data Pengguna</h4>
                        <p>Lihat semua akun user terdaftar.</p>
                    </div>
                    <a href="manageUser.php" class="btn-card" style="background-color: #333;">Lihat User</a>
                </div>
            </div>

            <h3>Daftar Pasien per Layanan</h3>
            <div class="menu-grid">

                <?php while ($row = $queryLayanan->fetch_assoc()): ?>
                    <div class="menu-card">
                        <div>
                            <h4><i class="fas fa-notes-medical" style="color:var(--primary);"></i> <?php echo $row['nama_layanan']; ?></h4>
                            <p>Kelola data janji temu & pasien <?php echo strtolower($row['nama_layanan']); ?>.</p>
                        </div>
                        <a href="manageJanji.php?layanan_id=<?php echo $row['id']; ?>" class="btn-card">Buka Data</a>
                    </div>
                <?php endwhile; ?>

                <div class="menu-card" style="background-color: #f4f9f4;">
                    <div>
                        <h4><i class="fas fa-list-alt" style="color:var(--primary);"></i> Semua Janji</h4>
                        <p>Lihat gabungan semua data booking masuk.</p>
                    </div>
                    <a href="manageJanji.php" class="btn-card">Lihat Semua</a>
                </div>

            </div>
        </div>

    </div>

    <?php include '../layout/footer.html'; ?>
</body>

</html>
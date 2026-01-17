<?php
session_start();
include '../service/koneksi.php';
include '../helper/authUser.php';

$user_id = $_SESSION['user_id'] ?? $_SESSION['id'];

// Ambil janji yang statusnya 'pending' milik user ini
$sql = "SELECT janji.*, layanan.nama_layanan, layanan.harga 
        FROM janji 
        JOIN layanan ON janji.layanan_id = layanan.id 
        WHERE janji.user_id = '$user_id' AND janji.status = 'pending'
        ORDER BY janji.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Tagihan - PMB Nurhasanah</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <nav class="navbar-dashboard">
        <a href="dashboardUser.php" class="brand"><i class="fas fa-heartbeat"></i> PMB Nurhasanah</a>
        <div class="nav-right">
            <span class="user-greeting">Halo, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span>
            <a href="dashboardUser.php" class="btn-logout-nav" style="background:transparent; border:1px solid white;">Kembali</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="welcome-banner" style="background: white; border-left: 5px solid #f39c12;">
            <h2 style="margin: 0; font-size: 1.5rem; color: #333;">Tagihan Saya</h2>
            <p style="margin: 5px 0 0; color: #666;">Silakan lunasi tagihan layanan di bawah ini.</p>
        </div>

        <div class="card-box" style="background:white; padding:20px; border-radius:10px;">
            <table class="custom-table" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:var(--primary); color:white;">
                        <th style="padding:15px;">No</th>
                        <th>Layanan</th>
                        <th>Jadwal</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr style="border-bottom:1px solid #eee;">
                                <td style="padding:15px;"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                                <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                                <td style="font-weight:bold;">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="payment.php?id=<?= $row['id'] ?>" class="btn-card" style="padding:5px 15px; font-size:0.9rem;">
                                        <i class="fas fa-wallet"></i> Bayar
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding:30px;'>Tidak ada tagihan pending.</td></tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
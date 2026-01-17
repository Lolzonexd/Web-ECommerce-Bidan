<?php
session_start();
include '../service/koneksi.php';
include '../helper/authUser.php';

$user_id = $_SESSION['user_id'] ?? $_SESSION['id'];

// Ambil data janji
$sql = "SELECT janji.*, layanan.nama_layanan, layanan.harga 
        FROM janji 
        JOIN layanan ON janji.layanan_id = layanan.id 
        WHERE janji.user_id = '$user_id' 
        ORDER BY janji.tanggal DESC, janji.jam DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking - PMB Nurhasanah</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">

    <style>
        /* TEMA SAGE GREEN & CREAM (Sesuai Dashboard User) */
        body {
            background-color: #fdfbf5; /* Cream Lembut */
            font-family: 'Poppins', sans-serif;
        }

        /* Override Navbar agar konsisten Hijau Sage */
        .navbar-dashboard {
            background-color: #6b9080;
        }
        .navbar-dashboard .brand, 
        .navbar-dashboard .user-greeting,
        .navbar-dashboard .btn-logout-nav {
            color: white !important;
            border-color: white !important;
        }

        /* Tabel & Card */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .custom-table th {
            background-color: #6b9080; /* Hijau Sage */
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .custom-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
        }

        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
        }

        /* Badge Status */
        .badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        .bg-pending { background-color: #fff3cd; color: #856404; }
        .bg-dibayar { background-color: #d1ecf1; color: #0c5460; }
        .bg-selesai { background-color: #e8f5e9; color: #2e7d32; } /* Hijau Sukses */
        .bg-batal { background-color: #f8d7da; color: #721c24; }

        /* Tombol Lihat Hasil */
        .btn-hasil {
            background-color: #6b9080;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border: 1px solid #6b9080;
        }
        .btn-hasil:hover {
            background-color: white;
            color: #6b9080;
        }
    </style>
</head>

<body>

    <nav class="navbar-dashboard">
        <a href="dashboardUser.php" class="brand">
            <i class="fas fa-heartbeat"></i> PMB Nurhasanah
        </a>
        <div class="nav-right">
            <span class="user-greeting">Halo, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span>
            <a href="dashboardUser.php" class="btn-logout-nav">Kembali</a>
        </div>
    </nav>

    <div class="dashboard-container">

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> Pembayaran berhasil!
            </div>
        <?php endif; ?>

        <div class="welcome-banner" style="background: white; border-left: 5px solid #6b9080; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h2 style="margin: 0; font-family: 'Playfair Display', serif; color: #6b9080;">Riwayat Kunjungan</h2>
            <p style="margin: 5px 0 0; color: #777;">Pantau status janji temu dan hasil pemeriksaan kesehatan Anda.</p>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Layanan</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th> </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            $status = $row['status'];
                            
                            // Logika Warna Status
                            $statusClass = 'bg-pending';
                            if ($status == 'dibayar') $statusClass = 'bg-dibayar';
                            if ($status == 'selesai') $statusClass = 'bg-selesai';
                            if ($status == 'batal') $statusClass = 'bg-batal';
                    ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <strong style="color:#333;"><?php echo htmlspecialchars($row['nama_layanan']); ?></strong>
                                    <div style="font-size:0.85rem; color:#888;">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
                                </td>
                                <td>
                                    <div><i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($row['tanggal'])); ?></div>
                                    <div style="font-size:0.85rem; margin-top:3px;"><i class="far fa-clock"></i> <?php echo date('H:i', strtotime($row['jam'])); ?> WIB</div>
                                </td>
                                <td>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($status == 'selesai') { ?>
                                        <a href="detailPemeriksaan.php?id=<?= $row['id'] ?>" class="btn-hasil">
                                            <i class="fas fa-file-medical"></i> Lihat Hasil
                                        </a>
                                    <?php } else if ($status == 'pending') { ?>
                                        <a href="payment.php?id=<?= $row['id'] ?>" style="color:#e67e22; text-decoration:none; font-size:0.9rem; font-weight:600;">
                                            <i class="fas fa-wallet"></i> Bayar
                                        </a>
                                    <?php } else { ?>
                                        <span style="color:#ccc;">-</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 40px; color: #999;">
                                Belum ada riwayat kunjungan.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

    <?php include '../layout/footer.html'; ?>
</body>
</html>
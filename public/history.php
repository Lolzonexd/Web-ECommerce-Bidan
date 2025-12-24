<?php
session_start();
include '../service/koneksi.php';
include '../helper/authUser.php';

$user_id = $_SESSION['user_id'] ?? $_SESSION['id'];

// Query Join: Mengambil data janji + nama layanan dari tabel layanan
$sql = "SELECT janji.*, layanan.nama_layanan, layanan.harga 
        FROM janji 
        JOIN layanan ON janji.layanan_id = layanan.id 
        WHERE janji.user_id = '$user_id' 
        ORDER BY janji.created_at DESC";

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
        /* CSS Tambahan Khusus Tabel */
        .table-responsive {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .custom-table th,
        .custom-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .custom-table th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
        }

        /* Badge Status */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .bg-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .bg-dibayar {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .bg-selesai {
            background-color: #d4edda;
            color: #155724;
        }

        .bg-batal {
            background-color: #f8d7da;
            color: #721c24;
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
            <a href="dashboardUser.php" class="btn-logout-nav" style="background:transparent; border:1px solid white;">Kembali</a>
        </div>
    </nav>

    <div class="dashboard-container">

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #28a745;">
                <i class="fas fa-check-circle"></i> Booking berhasil dibuat! Silakan tunggu konfirmasi atau lakukan pembayaran.
            </div>
        <?php endif; ?>

        <div class="welcome-banner" style="background: white; border-left: 5px solid var(--primary); box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h2 style="margin: 0; font-size: 1.5rem; color: #333;">Riwayat Kunjungan & Booking</h2>
            <p style="margin: 5px 0 0; color: #666;">Daftar semua janji temu yang pernah Anda buat.</p>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Layanan</th>
                        <th>Jadwal</th>
                        <th>Estimasi Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            // Logika Warna Status
                            $statusClass = 'bg-pending';
                            if ($row['status'] == 'dibayar') $statusClass = 'bg-dibayar';
                            if ($row['status'] == 'selesai') $statusClass = 'bg-selesai';
                            if ($row['status'] == 'batal') $statusClass = 'bg-batal';
                    ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['nama_layanan']); ?></strong>
                                </td>
                                <td>
                                    <i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($row['tanggal'])); ?> <br>
                                    <i class="far fa-clock"></i> <?php echo date('H:i', strtotime($row['jam'])); ?> WIB
                                </td>
                                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 40px; color: #777;">
                                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 10px; opacity: 0.5;"></i><br>
                                Belum ada riwayat booking.
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
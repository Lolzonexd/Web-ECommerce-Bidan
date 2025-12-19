<?php
session_start();
include '../service/koneksi.php';

// Cek Admin
if (!isset($_SESSION['loggedin']) || $_SESSION['level'] !== 'admin') {
    header("location: login.php");
    exit;
}

// 1. LOGIKA UPDATE STATUS (Terima/Tolak/Selesai)
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id_janji = $_GET['id'];
    $status_baru = $_GET['aksi']; // pending, dibayar, selesai, batal

    $conn->query("UPDATE janji SET status='$status_baru' WHERE id='$id_janji'");

    // Redirect kembali agar URL bersih (tetap bawa filter layanan jika ada)
    $redirect_url = "manage_janji.php";
    if (isset($_GET['layanan_id'])) $redirect_url .= "?layanan_id=" . $_GET['layanan_id'];
    header("Location: " . $redirect_url);
    exit;
}

// 2. LOGIKA FILTER LAYANAN
$whereClause = "";
$judulHalaman = "Semua Data Janji Temu";

if (isset($_GET['layanan_id'])) {
    $layanan_id = $_GET['layanan_id'];
    $whereClause = "WHERE janji.layanan_id = '$layanan_id'";

    // Ambil nama layanan untuk judul
    $cekLayanan = $conn->query("SELECT nama_layanan FROM layanan WHERE id='$layanan_id'")->fetch_assoc();
    if ($cekLayanan) {
        $judulHalaman = "Data Pasien: " . $cekLayanan['nama_layanan'];
    }
}

// 3. QUERY DATA (JOIN TABEL JANJI, USER, & LAYANAN)
$sql = "SELECT janji.*, user.username, layanan.nama_layanan 
        FROM janji 
        JOIN user ON janji.user_id = user.id 
        JOIN layanan ON janji.layanan_id = layanan.id 
        $whereClause 
        ORDER BY janji.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Janji - Admin</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <style>
        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            color: white;
        }

        .bg-pending {
            background: #f39c12;
        }

        .bg-dibayar {
            background: #3498db;
        }

        .bg-selesai {
            background: #27ae60;
        }

        .bg-batal {
            background: #c0392b;
        }

        .btn-action {
            text-decoration: none;
            padding: 5px 8px;
            border-radius: 4px;
            color: white;
            font-size: 0.8rem;
            margin-right: 2px;
        }

        .btn-acc {
            background: #27ae60;
        }

        .btn-reject {
            background: #c0392b;
        }

        .btn-finish {
            background: #2980b9;
        }
    </style>
</head>

<body>

    <nav class="navbar-dashboard">
        <a href="../admin/dashboard_admin.php" class="brand"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        <div class="nav-right"><span class="user-greeting">Atmin</span></div>
    </nav>

    <div class="dashboard-container">

        <div class="card-box">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h3 class="card-title" style="margin:0; border:none;"><?php echo $judulHalaman; ?></h3>
                <a href="manage_janji.php" class="btn-card" style="padding: 5px 15px; font-size: 0.9rem;">Reset Filter</a>
            </div>

            <div class="table-responsive">
                <table class="custom-table" style="width:100%">
                    <thead>
                        <tr style="background:var(--primary); color:white;">
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Layanan</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                // Warna Badge
                                $bg = 'bg-pending';
                                if ($row['status'] == 'selesai') $bg = 'bg-selesai';
                                if ($row['status'] == 'batal') $bg = 'bg-batal';
                                if ($row['status'] == 'dibayar') $bg = 'bg-dibayar';

                                // Pertahankan layanan_id di URL saat klik aksi
                                $linkFilter = isset($_GET['layanan_id']) ? "&layanan_id=" . $_GET['layanan_id'] : "";
                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['username']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                                    <td>
                                        <?= date('d M Y', strtotime($row['tanggal'])) ?><br>
                                        <small><i class="far fa-clock"></i> <?= date('H:i', strtotime($row['jam'])) ?></small>
                                    </td>
                                    <td><span class="badge <?= $bg ?>"><?= ucfirst($row['status']) ?></span></td>
                                    <td>
                                        <?php if ($row['status'] == 'pending' || $row['status'] == 'dibayar'): ?>
                                            <a href="manage_janji.php?aksi=selesai&id=<?= $row['id'] ?><?= $linkFilter ?>" class="btn-action btn-finish" title="Tandai Selesai" onclick="return confirm('Tandai pelayanan ini selesai?')"><i class="fas fa-check-double"></i> Selesai</a>

                                            <a href="manage_janji.php?aksi=batal&id=<?= $row['id'] ?><?= $linkFilter ?>" class="btn-action btn-reject" title="Batalkan" onclick="return confirm('Batalkan janji ini?')"><i class="fas fa-times"></i> Batal</a>
                                        <?php else: ?>
                                            <span style="color:#ccc; font-size:0.8rem;">Tidak ada aksi</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="6" style="text-align:center;">Tidak ada data janji temu.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>

</html>
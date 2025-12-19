<?php
session_start();
include '../service/koneksi.php';

// Cek Admin
if (!isset($_SESSION['loggedin']) || $_SESSION['level'] !== 'admin') {
    header("location: ../page/login.php");
    exit;
}

// --- LOGIKA 1: TAMBAH LAYANAN (CREATE) ---
if (isset($_POST['simpan_layanan'])) {
    $nama      = $_POST['nama_layanan'];
    $harga     = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    // Default aktif = 1 (True)
    $sql = "INSERT INTO layanan (nama_layanan, deskripsi, harga, aktif) VALUES ('$nama', '$deskripsi', '$harga', 1)";

    if ($conn->query($sql)) {
        header("Location: managePelayanan.php?status=sukses");
        exit;
    } else {
        $error = "Gagal menambah data: " . $conn->error;
    }
}

// --- LOGIKA 2: HAPUS LAYANAN (DELETE) ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM layanan WHERE id='$id'");
    header("Location: managePelayanan.php?status=hapus");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Layanan - Admin</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <style>
        /* Layout Grid Khusus Halaman Ini */
        .admin-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .admin-grid {
                grid-template-columns: 1fr;
            }
        }

        .btn-action {
            padding: 6px 10px;
            border-radius: 5px;
            color: white;
            margin-right: 5px;
            font-size: 0.8rem;
        }

        .btn-edit {
            background: #f39c12;
        }

        .btn-del {
            background: #e74c3c;
        }
    </style>
</head>

<body>

    <nav class="navbar-dashboard">
        <a href="dashboardAdmin.php" class="brand"><i class="fas fa-arrow-left"></i> Kembali</a>
        <div class="nav-right"><span class="user-greeting">Master Layanan</span></div>
    </nav>

    <div class="dashboard-container">

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
            <div style="background:#d4edda; color:#155724; padding:15px; border-radius:10px; margin-bottom:20px;">
                Berhasil menambah layanan baru!
            </div>
        <?php endif; ?>

        <div class="admin-grid">

            <div class="card-box">
                <h3 class="card-title"><i class="fas fa-plus-circle"></i> Tambah Layanan</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Nama Layanan</label>
                        <input type="text" name="nama_layanan" class="form-control" required placeholder="Contoh: Pijat Bayi">
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" required placeholder="Contoh: 75000">
                    </div>
                    <div class="form-group">
                        <label>Keterangan Singkat (Opsional)</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan singkat layanan..."></textarea>
                    </div>
                    <button type="submit" name="simpan_layanan" class="btn-submit">Simpan Data</button>
                </form>
            </div>

            <div class="card-box">
                <h3 class="card-title"><i class="fas fa-list"></i> Daftar Layanan Klinik</h3>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Layanan</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM layanan ORDER BY id DESC");
                            $no = 1;
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['nama_layanan']) ?></strong><br>
                                        <small style="color:#888;"><?= htmlspecialchars($row['deskripsi']) ?></small>
                                    </td>
                                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($row['aktif'] == 1): ?>
                                            <span style="color:green; font-weight:bold; font-size:0.8rem;">Aktif</span>
                                        <?php else: ?>
                                            <span style="color:red; font-weight:bold; font-size:0.8rem;">Non-Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="editPelayanan.php?id=<?= $row['id'] ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>
                                        <a href="managePelayanan.php?hapus=<?= $row['id'] ?>" class="btn-action btn-del" onclick="return confirm('Yakin ingin menghapus layanan ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <?php include '../layout/footer.html'; ?>
</body>

</html>
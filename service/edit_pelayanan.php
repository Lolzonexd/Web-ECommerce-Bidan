<?php
session_start();
include '../service/koneksi.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['level'] !== 'admin') {
    header("location: ../page/login.php");
    exit;
}

// Ambil ID dari URL
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM layanan WHERE id='$id'")->fetch_assoc();

// Jika form disubmit
if (isset($_POST['update_layanan'])) {
    $nama      = $_POST['nama_layanan'];
    $harga     = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $aktif     = $_POST['aktif']; // 1 atau 0

    $sql = "UPDATE layanan SET 
            nama_layanan='$nama', 
            harga='$harga', 
            deskripsi='$deskripsi', 
            aktif='$aktif' 
            WHERE id='$id'";

    if ($conn->query($sql)) {
        header("Location: manage_pelayanan.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Layanan - Admin</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <nav class="navbar-dashboard">
        <a href="../service/manage_pelayanan.php" class="brand"><i class="fas fa-arrow-left"></i> Batal & Kembali</a>
        <div class="nav-right"><span class="user-greeting">Edit Data</span></div>
    </nav>

    <div class="dashboard-container" style="max-width: 600px;">
        <div class="card-box">
            <h3 class="card-title"><i class="fas fa-edit"></i> Edit Layanan</h3>
            
            <form method="POST">
                <div class="form-group">
                    <label>Nama Layanan</label>
                    <input type="text" name="nama_layanan" class="form-control" required value="<?= htmlspecialchars($data['nama_layanan']) ?>">
                </div>
                
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" required value="<?= $data['harga'] ?>">
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Status Layanan</label>
                    <select name="aktif" class="form-control">
                        <option value="1" <?= $data['aktif'] == 1 ? 'selected' : '' ?>>Aktif (Bisa Dipilih User)</option>
                        <option value="0" <?= $data['aktif'] == 0 ? 'selected' : '' ?>>Non-Aktif (Sembunyikan)</option>
                    </select>
                </div>

                <button type="submit" name="update_layanan" class="btn-submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>

</body>
</html>
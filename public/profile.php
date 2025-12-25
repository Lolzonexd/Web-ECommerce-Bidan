<?php
session_start();
include '../service/koneksi.php';         // Panggil koneksi database ($conn)

// Ambil ID User dari Session
$id = $_SESSION['user_id'] ?? $_SESSION['id'];

// QUERY: Gabungkan data User dan Biodata
// Menggunakan $conn->query
$query = "SELECT user.username, user.email, biodata.* FROM user 
          LEFT JOIN biodata ON user.id = biodata.user_id 
          WHERE user.id = '$id'";

$result = $conn->query($query);
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - PMB Nurhasanah</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
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
        
        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> Data profil berhasil diperbarui!
            </div>
        <?php elseif(isset($_GET['status']) && $_GET['status'] == 'error'): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                <i class="fas fa-exclamation-circle"></i> Gagal menyimpan: <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <div class="card-box" style="max-width: 800px; margin: 0 auto;">
            <div style="border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
                <h3 class="card-title" style="margin:0;"><i class="fas fa-user-edit"></i> Edit Profil</h3>
                <p style="color:#777; font-size:0.9rem; margin-top:5px;">Lengkapi data diri Anda untuk keperluan administrasi.</p>
            </div>

            <form action="../service/prosesprofile.php" method="POST">
                
                <h4 style="color:var(--primary); margin-bottom:15px;">Informasi Akun</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($data['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px dashed #ddd; margin: 20px 0;">

                <h4 style="color:var(--primary); margin-bottom:15px;">Biodata Diri</h4>
                
                <div class="form-group">
                    <label>Nama Lengkap (Sesuai KTP)</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?php echo htmlspecialchars($data['nama_lengkap'] ?? ''); ?>" placeholder="Masukkan nama lengkap">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="number" name="nik" class="form-control" value="<?php echo htmlspecialchars($data['nik'] ?? ''); ?>" placeholder="16 digit NIK">
                    </div>
                    <div class="form-group">
                        <label>Nomor HP (WhatsApp)</label>
                        <input type="number" name="no_hp" class="form-control" value="<?php echo htmlspecialchars($data['no_hp'] ?? ''); ?>" placeholder="08xxxxxxxx">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo htmlspecialchars($data['tanggal_lahir'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="Perempuan" <?php echo ($data['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                            <option value="Laki-Laki" <?php echo ($data['jenis_kelamin'] ?? '') == 'Laki-Laki' ? 'selected' : ''; ?>>Laki-Laki</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Nama jalan, RT/RW, Kelurahan..."><?php echo htmlspecialchars($data['alamat'] ?? ''); ?></textarea>
                </div>

                <button type="submit" name="update_profile" class="btn-submit" style="margin-top: 20px; width: 100%;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <?php include '../layout/footer.html'; ?>
</body>
</html>
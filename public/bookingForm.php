<?php
session_start();
include '../service/koneksi.php';
include '../helper/authUser.php';

// Ambil data layanan dari database untuk dropdown
$queryLayanan = "SELECT * FROM layanan WHERE aktif = 1";
$resultLayanan = $conn->query($queryLayanan);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Janji - PMB Nurhasanah</title>
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
            <span class="user-greeting">Halo, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span>
            <a href="dashboardUser.php" class="btn-logout-nav" style="background:transparent; border:1px solid white;">Kembali</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="card-box" style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <h3 class="card-title" style="color: var(--primary); margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;">
                <i class="fas fa-calendar-plus"></i> Form Buat Janji
            </h3>

            <form action="../service/prosesBooking.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Pilih Layanan</label>
                    <select name="layanan_id" class="form-control" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
                        <option value="">-- Silakan Pilih --</option>
                        <?php
                        if ($resultLayanan->num_rows > 0) {
                            while ($row = $resultLayanan->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['nama_layanan'] . ' - Rp ' . number_format($row['harga'], 0, ',', '.') . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Tanggal Rencana</label>
                    <input type="date" name="tanggal" class="form-control" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Jam</label>
                    <input type="time" name="jam" class="form-control" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
                </div>

                <button type="submit" class="btn-submit" style="width:100%; background-color: var(--primary); color: white; padding: 12px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                    Kirim Booking
                </button>
            </form>
        </div>
    </div>

    <?php include '../layout/footer.html'; ?>
</body>

</html>
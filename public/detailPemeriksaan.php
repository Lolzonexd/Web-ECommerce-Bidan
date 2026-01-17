<?php
session_start();
include '../service/koneksi.php';
include '../helper/authUser.php';

// Validasi ID
if (!isset($_GET['id'])) {
    header("Location: history.php");
    exit;
}

$id_janji = $_GET['id'];
$user_id = $_SESSION['user_id'] ?? $_SESSION['id'];

// Ambil data Rekam Medis + Info Janji + Biodata User
$sql = "SELECT rekam_medis.*, janji.tanggal, janji.jam, layanan.nama_layanan, biodata.nama_lengkap
        FROM rekam_medis
        JOIN janji ON rekam_medis.janji_id = janji.id
        JOIN layanan ON janji.layanan_id = layanan.id
        LEFT JOIN biodata ON janji.user_id = biodata.user_id
        WHERE janji.id = '$id_janji' AND janji.user_id = '$user_id'";

$result = $conn->query($sql);
$data = $result->fetch_assoc();

// Jika data tidak ditemukan (belum diperiksa atau ID salah)
if (!$data) {
    echo "<script>alert('Data pemeriksaan belum tersedia atau tidak ditemukan.'); window.location='history.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pemeriksaan - PMB Nurhasanah</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #fdfbf5;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 50px;
        }

        .navbar-dashboard {
            background-color: #6b9080;
        }

        .navbar-dashboard a,
        .navbar-dashboard span {
            color: white !important;
            border-color: white !important;
        }

        .report-container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            position: relative;
        }

        .report-header {
            background-color: #6b9080;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .report-header h2 {
            font-family: 'Playfair Display', serif;
            margin: 0;
            font-size: 1.8rem;
            color: white !important;
        }

        .report-header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
            color: white !important;
        }

        .report-body {
            padding: 40px;
        }

        /* Grid Info Pasien */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #eee;
        }

        .info-item label {
            display: block;
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 5px;
        }

        .info-item div {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
            font-family: 'Playfair Display', serif;
        }

        /* Kotak Vital Sign */
        .vital-box {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .vital-item {
            flex: 1;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .vital-tensi {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .vital-berat {
            background: #e3f2fd;
            color: #1565c0;
        }

        .vital-value {
            font-size: 1.3rem;
            font-weight: bold;
            margin-top: 5px;
        }

        .vital-label {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        /* Section Medis */
        .medical-section {
            margin-bottom: 25px;
        }

        .medical-title {
            color: #6b9080;
            font-weight: 600;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .medical-content {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            color: #555;
            line-height: 1.6;
            border-left: 4px solid #6b9080;
        }

        .btn-back-report {
            display: block;
            text-align: center;
            background: #f1f1f1;
            color: #666;
            padding: 15px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-back-report:hover {
            background: #e0e0e0;
            color: #333;
        }

        @media print {

            .navbar-dashboard,
            .btn-back-report {
                display: none;
            }

            body {
                background: white;
            }

            .report-container {
                box-shadow: none;
                border: 1px solid #ccc;
                margin-top: 0;
            }
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
        </div>
    </nav>

    <div class="dashboard-container">

        <a href="history.php" style="text-decoration:none; color:#6b9080; font-weight:600; display:inline-block; margin-bottom:10px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
        </a>

        <div class="report-container">
            <div class="report-header">
                <i class="fas fa-file-medical-alt" style="font-size: 3rem; margin-bottom: 15px;"></i>
                <h2>Hasil Pemeriksaan Medis</h2>
                <p>Layanan: <?php echo htmlspecialchars($data['nama_layanan']); ?></p>
            </div>

            <div class="report-body">

                <div class="info-grid">
                    <div class="info-item">
                        <label>Nama Pasien</label>
                        <div><?php echo htmlspecialchars($data['nama_lengkap'] ?? $_SESSION['username']); ?></div>
                    </div>
                    <div class="info-item">
                        <label>Tanggal Periksa</label>
                        <div><?php echo date('d F Y', strtotime($data['tanggal'])); ?></div>
                    </div>
                </div>

                <div class="vital-box">
                    <div class="vital-item vital-tensi">
                        <div class="vital-label"><i class="fas fa-heartbeat"></i> Tensi Darah</div>
                        <div class="vital-value"><?php echo htmlspecialchars($data['tensi_darah']); ?> mmHg</div>
                    </div>
                    <div class="vital-item vital-berat">
                        <div class="vital-label"><i class="fas fa-weight"></i> Berat Badan</div>
                        <div class="vital-value"><?php echo htmlspecialchars($data['berat_badan']); ?> Kg</div>
                    </div>
                </div>

                <div class="medical-section">
                    <div class="medical-title"><i class="fas fa-head-side-cough"></i> Keluhan Pasien</div>
                    <div class="medical-content">
                        <?php echo nl2br(htmlspecialchars($data['keluhan'])); ?>
                    </div>
                </div>

                <div class="medical-section">
                    <div class="medical-title"><i class="fas fa-stethoscope"></i> Diagnosa Bidan</div>
                    <div class="medical-content" style="background-color:#e8f5e9; border-color:#2e7d32;">
                        <strong><?php echo nl2br(htmlspecialchars($data['diagnosa'])); ?></strong>
                    </div>
                </div>

                <div class="medical-section">
                    <div class="medical-title"><i class="fas fa-syringe"></i> Tindakan Medis</div>
                    <div class="medical-content">
                        <?php echo nl2br(htmlspecialchars($data['tindakan'])); ?>
                    </div>
                </div>

                <div class="medical-section">
                    <div class="medical-title"><i class="fas fa-pills"></i> Resep Obat & Vitamin</div>
                    <div class="medical-content">
                        <?php echo nl2br(htmlspecialchars($data['resep_obat'])); ?>
                    </div>
                </div>

            </div>

            <a href="history.php" class="btn-back-report">Kembali ke Daftar Riwayat</a>
        </div>
    </div>

    <?php include '../layout/footer.html'; ?>
</body>

</html>
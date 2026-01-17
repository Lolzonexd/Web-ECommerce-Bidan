<?php
// bidan/inputPemeriksaan.php
session_start();
include '../service/koneksi.php';

// Cek Keamanan
if (!isset($_SESSION['loggedin']) || $_SESSION['level'] !== 'bidan') {
    header("Location: ../page/login.php");
    exit;
}

if (!isset($_GET['id_janji'])) {
    header("Location: dashboardBidan.php");
    exit;
}

$id_janji = $_GET['id_janji'];

// Ambil info pasien untuk ditampilkan di atas form
$sql = "SELECT janji.*, biodata.nama_lengkap, layanan.nama_layanan 
        FROM janji 
        JOIN biodata ON janji.user_id = biodata.user_id 
        JOIN layanan ON janji.layanan_id = layanan.id
        WHERE janji.id = '$id_janji'";
$data = $conn->query($sql)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pemeriksaan - PMB Nurhasanah</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../asset/style.css">

    <style>
        body {
            background-color: #fdfbf5;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding-bottom: 50px;
        }

        .navbar-bidan {
            background-color: #6b9080;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            color: white;
        }

        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info {
            color: white;
            font-size: 0.95rem;
        }

        /* Container Form */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .card-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-top: 10px;
        }

        /* Judul Form */
        h2.form-title {
            font-family: 'Playfair Display', serif;
            color: #6b9080;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 15px;
            margin-top: 0;
            margin-bottom: 25px;
        }

        /* Info Pasien Box */
        .patient-info {
            background-color: #e8f5e9;
            border-left: 5px solid #6b9080;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            gap: 40px;
        }

        .info-item label {
            display: block;
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 3px;
        }

        .info-item span {
            font-weight: 600;
            font-size: 1.1rem;
            color: #2c3e50;
            font-family: 'Playfair Display', serif;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #6b9080;
            outline: none;
            box-shadow: 0 0 0 3px rgba(107, 144, 128, 0.1);
        }

        /* Tombol Simpan */
        .btn-save {
            background-color: #6b9080;
            color: white;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-save:hover {
            background-color: #557c67;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(107, 144, 128, 0.3);
        }

        /* Link Kembali */
        .link-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #666;
            margin-bottom: 15px;
            font-weight: 500;
            transition: 0.3s;
        }

        .link-back:hover {
            color: #6b9080;
            transform: translateX(-5px);
        }
    </style>
</head>

<body>

    <nav class="navbar-bidan">
        <a href="dashboardBidan.php" class="brand-logo">
            <i class="fa-solid fa-heart-pulse"></i> PMB Nurhasanah
        </a>
        <div class="user-info">
            <span>Bidan <b><?= htmlspecialchars($_SESSION['username']) ?></b></span>
        </div>
    </nav>

    <div class="container">
        <a href="dashboardBidan.php" class="link-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Antrian
        </a>

        <div class="card-box">
            <h2 class="form-title">
                <i class="fa-solid fa-file-medical" style="margin-right:10px;"></i>
                Form Pemeriksaan Medis
            </h2>

            <div class="patient-info">
                <div class="info-item">
                    <label>Nama Pasien</label>
                    <span><?= htmlspecialchars($data['nama_lengkap']) ?></span>
                </div>
                <div class="info-item">
                    <label>Layanan yang Dipilih</label>
                    <span><?= htmlspecialchars($data['nama_layanan']) ?></span>
                </div>
            </div>

            <form action="../service/prosesInputPemeriksaan.php" method="POST">
                <input type="hidden" name="janji_id" value="<?= $id_janji ?>">

                <div class="form-group">
                    <label><i class="fa-solid fa-head-side-cough"></i> Keluhan Pasien</label>
                    <textarea name="keluhan" rows="3" required placeholder="Jelaskan keluhan yang dirasakan pasien..."></textarea>
                </div>

                <div style="display:flex; gap:20px; flex-wrap:wrap;">
                    <div class="form-group" style="flex:1; min-width:200px;">
                        <label><i class="fa-solid fa-heart-pulse"></i> Tensi Darah (mmHg)</label>
                        <input type="text" name="tensi" placeholder="Contoh: 120/80">
                    </div>
                    <div class="form-group" style="flex:1; min-width:200px;">
                        <label><i class="fa-solid fa-weight-scale"></i> Berat Badan (Kg)</label>
                        <input type="number" name="berat" placeholder="Contoh: 60">
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-stethoscope"></i> Diagnosa / Hasil Periksa</label>
                    <textarea name="diagnosa" rows="3" required placeholder="Tuliskan hasil diagnosa medis..."></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-syringe"></i> Tindakan</label>
                    <textarea name="tindakan" rows="2" placeholder="Tindakan medis yang dilakukan (Suntik, Infus, dll)..."></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-pills"></i> Resep Obat / Vitamin</label>
                    <textarea name="resep" rows="3" placeholder="Daftar obat yang diberikan kepada pasien..."></textarea>
                </div>

                <button type="submit" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan & Selesaikan Pemeriksaan
                </button>
            </form>
        </div>
    </div>

</body>

</html>
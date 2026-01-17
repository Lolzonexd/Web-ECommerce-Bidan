<?php
session_start();
include '../service/koneksi.php';

// 1. CEK KEAMANAN
if (!isset($_SESSION['loggedin']) || $_SESSION['level'] !== 'bidan') {
    header("Location: ../page/login.php");
    exit;
}

// 2. AMBIL DATA ANTRIAN
$sql = "SELECT janji.*, biodata.nama_lengkap, layanan.nama_layanan 
        FROM janji 
        JOIN biodata ON janji.user_id = biodata.user_id 
        JOIN layanan ON janji.layanan_id = layanan.id
        WHERE janji.status != 'selesai' AND janji.status != 'batal'
        ORDER BY janji.tanggal ASC, janji.jam ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bidan - PMB Nurhasanah</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../asset/style.css">

    <style>
        /* --- TEMA SESUAI GAMBAR USER --- */
        body {
            background-color: #fdfbf5; /* Cream Lembut */
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding-bottom: 50px;
        }

        /* NAVBAR HIJAU SAGE (SESUAI REQUEST) */
        .navbar-bidan {
            background-color: #6b9080; /* Warna Hijau Dashboard User */
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            color: white;
        }

        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: white; /* Teks Putih */
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 0.95rem;
            color: white; /* Teks Putih */
        }

        /* Tombol Logout Putih Transparan */
        .btn-logout {
            color: white;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid white;
            padding: 6px 20px;
            border-radius: 50px;
            transition: 0.3s;
            font-size: 0.85rem;
            background: rgba(255, 255, 255, 0.1);
        }
        .btn-logout:hover {
            background: white;
            color: #6b9080; /* Teks jadi hijau saat hover */
        }

        /* Container Utama */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Judul Halaman */
        h2.page-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #6b9080; /* Hijau Judul */
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        /* Wrapper Tabel */
        .table-wrapper {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            padding: 5px;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        .styled-table thead tr {
            background-color: white;
            color: #444;
            text-align: left;
            border-bottom: 2px solid #6b9080; /* Garis Hijau di Header */
        }

        .styled-table th {
            padding: 18px 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .styled-table td {
            padding: 18px 20px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        /* Zebra Striping */
        .styled-table tbody tr:nth-of-type(even) {
            background-color: #fcfcfc;
        }

        /* Hover Effect */
        .styled-table tbody tr:hover {
            background-color: #fff9e6; /* Kuning tipis saat di-hover */
        }

        /* Badge Status */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .badge-dibayar {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .badge-pending {
            background-color: #fff8e1;
            color: #f57f17;
        }

        /* Tombol Aksi Hijau */
        .btn-periksa {
            background-color: #6b9080; /* Hijau Sage */
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 2px 5px rgba(107, 144, 128, 0.3);
        }
        .btn-periksa:hover {
            background-color: #557c67;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(107, 144, 128, 0.4);
        }
    </style>
</head>

<body>

    <nav class="navbar-bidan">
        <a href="#" class="brand-logo">
            <i class="fa-solid fa-heart-pulse" style="margin-right:10px;"></i> PMB Nurhasanah
        </a>
        <div class="user-info">
            <span>Halo, Bidan <b><?= htmlspecialchars($_SESSION['username']) ?></b></span>
            <a href="../service/logout.php" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </nav>

    <div class="container">
        
        <h2 class="page-title">Antrian Pasien Hari Ini</h2>

        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama Pasien</th>
                        <th width="20%">Layanan</th>
                        <th width="20%">Jadwal</th>
                        <th width="15%">Status</th>
                        <th width="15%" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) { 
                            $statusClass = ($row['status'] == 'dibayar') ? 'badge-dibayar' : 'badge-pending';
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div style="font-weight: 600; color: #333;">
                                <?= htmlspecialchars($row['nama_lengkap']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                        <td>
                            <div style="color: #444;">
                                <?= date('d M Y', strtotime($row['tanggal'])) ?>
                            </div>
                            <div style="font-size: 0.85rem; color: #888;">
                                <?= date('H:i', strtotime($row['jam'])) ?> WIB
                            </div>
                        </td>
                        <td>
                            <span class="badge <?= $statusClass ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <a href="inputPemeriksaan.php?id_janji=<?= $row['id'] ?>" class="btn-periksa">
                                Periksa Pasien
                            </a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else { 
                    ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 50px;">
                            <p style="color: #888; font-style:italic;">Belum ada antrian pasien saat ini.</p>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
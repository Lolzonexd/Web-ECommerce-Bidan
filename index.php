<?php
session_start();
include 'service/koneksi.php';

if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $hashedToken = hash('sha256', $token);

    $sql = "SELECT id, username, level, remember_expire 
            FROM user 
            WHERE remember_token = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $hashedToken);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (strtotime($user['remember_expire']) > time()) {
            session_regenerate_id(true);

            $_SESSION['loggedin'] = true;
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level']    = $user['level'];

            switch ($user['level']) {
                case 'admin':
                    header("Location: admin/dashboard.php");
                    exit;

                case 'user':
                    header("Location: public/dashboard.php");
                    exit;

                default:
                    $_SESSION['login_error'] = "Terjadi Kesalahan pada server, coba beberapa saat lagi: ";
                    header("Location: page/login.php");
                    exit;
            }
        } else {
            setcookie("remember_me", "", time() - 3600, "/");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMB Nurhasanah</title>
    <!-- POPPINS FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <!-- AWESOME FONT -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="asset/style.css">
</head>

<body>
    <!-- NAVBAR -->
    <?php include 'layout/header.html'; ?>

    <!-- HERO -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Pendamping Setia Kehamilan & Kelahiran sang Buah Hati</h1>
            <p>Melayani dengan hati, profesional, dan mengutamakan kenyamanan ibu serta keselamatan bayi.</p>
            <a href="https://wa.me/6285267444236" target="_blank" class="cta-btn" style="font-size: 1.1rem;">
                <i class="fa-brands fa-whatsapp"></i> Konsultasi Gratis
            </a>
        </div>
    </section>
    <!-- END HERO -->

    <!-- FEATURES -->
    <section class="features">
        <div class="feature-card">
            <i class="fa-solid fa-user-nurse"></i>
            <h3>Profesional & Berizin</h3>
            <p>Bidan bersertifikasi dan serta terlatih.</p>
        </div>
        <div class="feature-card">
            <i class="fa-solid fa-house-chimney-medical"></i>
            <h3>Nyaman & Aman</h3>
            <p>Klinik bersih serasa di rumah sendiri.</p>
        </div>
        <div class="feature-card">
            <i class="fa-solid fa-clock"></i>
            <h3>Siaga 24 Jam</h3>
            <p>Layanan gawat darurat dan persalinan kapan saja.</p>
        </div>
    </section>
    <!-- END FEATURES -->

    <!-- SERVICES -->
    <section class="services" id="services">
        <div class="section-title">
            <h2>Layanan Kami</h2>
            <p>Solusi kesehatan terpadu untuk Ibu dan Bayi</p>
        </div>
        <div class="service-grid">
            <div class="service-item">
                <h3><i class="fa-solid fa-notes-medical"></i> Cek Kehamilan</h3>
                <p>Pemeriksaan rutin dengan alat USG modern untuk memantau tumbuh kembang janin.</p>
            </div>
            <div class="service-item">
                <h3><i class="fa-solid fa-baby-carriage"></i> Pelayanan Persalinan</h3>
                <p>Persalinan normal minim trauma, tenang, dan penuh kasih sayang.</p>
            </div>
            <div class="service-item">
                <h3><i class="fa-solid fa-syringe"></i> Imunisasi</h3>
                <p>Vaksinasi dasar dan lanjutan lengkap untuk perlindungan buah hati.</p>
            </div>
            <div class="service-item">
                <h3><i class="fa-solid fa-baby"></i> Mom & Baby Spa</h3>
                <p>Pijat relaksasi ibu hamil/nifas dan stimulasi motorik bayi.</p>
            </div>
            <div class="service-item">
                <h3><i class="fa-solid fa-baby-carriage"></i> Pelayanan Keluarga Berencana (KB)</h3>
                <p>TEst</p>
            </div>
            <div class="service-item">
                <h3><i class="fa-solid fa-droplet"></i> Pelayanan ibu Nifas</h3>
                <p>Test</p>
            </div>
        </div>
    </section>
    <!-- END SERVICES -->

    <!-- PROFILE -->
    <section class="profile" id="profile">
        <img src="img/fotoProfile1.svg" alt="Bidan Ana" class="profile-img">
        <div class="profile-text">
            <h2>Bidan Nurhasanah, S.S.T., M.Kes.</h2>
            <div class="quote">
                "Test"
            </div>
            <p>LOREM IPSUM</p>
        </div>
    </section>
    <!-- END PROFILE -->

    <!-- FOOTER -->
    <?php include 'layout/footer.html'; ?>
</body>

</html>
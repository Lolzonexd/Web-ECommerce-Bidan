<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../service/koneksi.php';
require_once '../config/midtrans_config.php';
$midtransclient = require_once '../config/midtransClient.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['id'])) {
    header("Location: ../public/login.php");
    exit;
}
$user_id_session = $_SESSION['user_id'] ?? $_SESSION['id'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<h3 style='text-align:center; margin-top:50px;'>Error: ID Tagihan tidak ditemukan.</h3>");
}
$id_janji = $_GET['id'];

$query = "SELECT janji.*, layanan.nama_layanan, layanan.harga, user.username, user.email 
          FROM janji 
          JOIN layanan ON janji.layanan_id = layanan.id 
          JOIN user ON janji.user_id = user.id
          WHERE janji.id = '$id_janji'";

$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("<h3 style='text-align:center; margin-top:50px;'>Error: Data tagihan tidak ditemukan.</h3>");
}

$data = $result->fetch_assoc();

$order_id = "TRX-" . $data['id'] . "-" . time();

$params = array(
    'transaction_details' => array(
        'order_id' => $order_id,
        'gross_amount' => (int) $data['harga'],
    ),
    'customer_details' => array(
        'first_name' => $data['username'],
        'email'      => $data['email'],
    ),
    'item_details' => array(
        array(
            'id'       => $data['layanan_id'],
            'price'    => (int) $data['harga'],
            'quantity' => 1,
            'name'     => $data['nama_layanan']
        )
    ),
);

$snapToken = "";
try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
} catch (Exception $e) {
    echo "<div style='text-align:center; padding:50px;'>";
    echo "<h3>Koneksi ke Payment Gateway Gagal</h3>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "</div>";
    exit;
}

$conn->query("INSERT INTO transaksi (janji_id, order_id, gross_amount, status) 
              VALUES ('$id_janji', '$order_id', '{$data['harga']}', 'pending')");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - PMB Nurhasanah</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../asset/style.css">

    <script type="text/javascript"
        src="https://app.midtrans.com/snap/snap.js"
        data-client-key="<?= htmlspecialchars($midtransclient['client_key']) ?>"></script>

    <style>
        body {
            background-color: #f9f9f9;
        }

        .payment-section {
            padding: 120px 20px 80px;
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .payment-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 500px;
            text-align: center;
            border-top: 5px solid var(--primary-color, #ff6b6b);
        }

        .payment-card h2 {
            font-family: 'Playfair Display', serif;
            color: #333;
            margin-bottom: 10px;
        }

        .price-tag {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2ecc71;
            font-family: 'Poppins', sans-serif;
            margin: 20px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #eee;
            color: #666;
            font-family: 'Poppins', sans-serif;
        }

        .detail-row strong {
            color: #333;
        }

        .btn-pay {
            margin-top: 30px;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(to right, #ff6b6b, #ff8e8e);
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 107, 0.4);
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #888;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-link:hover {
            color: #333;
        }
    </style>
</head>

<body>

    <?php include '../layout/header.html'; ?>

    <section class="payment-section">
        <div class="payment-card">
            <div class="icon-header" style="font-size: 3rem; color: #ff6b6b; margin-bottom: 20px;">
                <i class="fa-solid fa-receipt"></i>
            </div>

            <h2>Konfirmasi Pembayaran</h2>
            <p style="color:#777;">Mohon selesaikan pembayaran untuk booking layanan Anda.</p>

            <div class="price-tag">
                Rp <?= number_format($data['harga'], 0, ',', '.') ?>
            </div>

            <div class="order-details" style="text-align: left; margin-top: 20px;">
                <div class="detail-row">
                    <span>Layanan</span>
                    <strong><?= htmlspecialchars($data['nama_layanan']) ?></strong>
                </div>
                <div class="detail-row">
                    <span>Pasien</span>
                    <strong><?= htmlspecialchars($data['username']) ?></strong>
                </div>
                <div class="detail-row">
                    <span>No. Order</span>
                    <strong style="font-size:0.85rem;"><?= $order_id ?></strong>
                </div>
            </div>

            <button id="pay-button" class="cta-btn btn-pay">
                <i class="fa-solid fa-lock"></i> Bayar Sekarang
            </button>

            <a href="payment_list.php" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tagihan
            </a>
        </div>
    </section>

    <?php include '../layout/footer.html'; ?>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            window.snap.pay('<?php echo $snapToken; ?>', {
                onSuccess: function(result) {
                    alert("Pembayaran Berhasil! Terima kasih.");
                    window.location.href = "history.php?status=success";
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran! Silakan cek riwayat Anda.");
                    window.location.href = "history.php";
                },
                onError: function(result) {
                    alert("Pembayaran gagal! Silakan coba lagi.");
                    window.location.href = "history.php";
                },
                onClose: function() {
                    alert('Anda menutup halaman pembayaran.');
                }
            })
        });
    </script>
</body>

</html>
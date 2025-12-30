<?php
session_start();
include '../service/koneksi.php';
include '../service/midtrans_config.php'; 

// Cek Login
if (!isset($_SESSION['loggedin'])) { header("location: login.php"); exit; }

// Ambil ID Janji dari URL
$id_janji = $_GET['id'];

// Ambil data Janji & Harga Layanan dari Database
$query = "SELECT janji.*, layanan.nama_layanan, layanan.harga, user.username, user.email, user.id as id_user 
          FROM janji 
          JOIN layanan ON janji.layanan_id = layanan.id 
          JOIN user ON janji.user_id = user.id
          WHERE janji.id = '$id_janji'";
$data = $conn->query($query)->fetch_assoc();

// Buat Order ID Unik (Misal: TRX-IDJANJI-TIMESTAMP)
$order_id = "TRX-" . $data['id'] . "-" . time();

// Siapkan Parameter untuk Midtrans
$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' => (int) $data['harga'],
);

$customer_details = array(
    'first_name'    => $data['username'],
    'email'         => $data['email'],
);

$item_details = array(
    array(
        'id'       => $data['layanan_id'],
        'price'    => (int) $data['harga'],
        'quantity' => 1,
        'name'     => $data['nama_layanan']
    )
);

// Gabungkan Parameter
$params = array(
    'transaction_details' => $transaction_details,
    'customer_details'    => $customer_details,
    'item_details'        => $item_details,
);

// Dapatkan Snap Token dari Midtrans
try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}

// SIMPAN DULU transaksi 'pending' ke database kamu
// (Agar nanti pas notifikasi masuk, kita tahu ini transaksi yang mana)
$sqlTrans = "INSERT INTO transaksi (janji_id, order_id, gross_amount, status) 
             VALUES ('$id_janji', '$order_id', '{$data['harga']}', 'pending')";
$conn->query($sqlTrans);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran - PMB Kasih Bunda</title>
    <link rel="stylesheet" href="../asset/style.css">
    <script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
      data-client-key="Mid-client-mnCbZ4tP97KE8-Cy"></script>
</head>
<body>
    <div class="dashboard-container" style="text-align:center; margin-top:50px;">
        <div class="card-box" style="max-width: 500px; margin: 0 auto;">
            <h3>Konfirmasi Pembayaran</h3>
            <p>Layanan: <b><?= $data['nama_layanan'] ?></b></p>
            <h2 style="color:var(--primary);">Rp <?= number_format($data['harga'],0,',','.') ?></h2>
            <br>
            <button id="pay-button" class="btn-submit" style="width:100%">Bayar Sekarang</button>
            <br><br>
            <a href="../public/history.php">Kembali</a>
        </div>
    </div>

    <script type="text/javascript">
      var payButton = document.getElementById('pay-button');
      payButton.addEventListener('click', function () {
        // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
        window.snap.pay('<?php echo $snapToken; ?>', {
          onSuccess: function(result){
            // Jika sukses, arahkan ke history (atau halaman sukses)
            alert("Pembayaran Berhasil!");
            window.location.href = "history.php?status=success";
          },
          onPending: function(result){
            alert("Menunggu pembayaran!");
            window.location.href = "history.php";
          },
          onError: function(result){
            alert("Pembayaran gagal!");
            window.location.href = "history.php";
          },
          onClose: function(){
            alert('Anda menutup popup tanpa menyelesaikan pembayaran');
          }
        })
      });
    </script>
</body>
</html>
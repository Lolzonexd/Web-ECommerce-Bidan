<?php
// Panggil konfigurasi Midtrans & Database
require_once dirname(__FILE__) . '/../service/midtrans_config.php';
include '../service/koneksi.php';

try {
    // 1. Ambil Notifikasi dari Midtrans
    $notif = new \Midtrans\Notification();
} catch (\Exception $e) {
    exit($e->getMessage());
}

// 2. Ambil parameter penting
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

// -- LOGGING (Opsional: Untuk Cek Apakah Notif Masuk) --
$log_msg = date("Y-m-d H:i:s") . " | Order: $order_id | Status: $transaction \n";
file_put_contents('midtrans.log', $log_msg, FILE_APPEND);
// -----------------------------------------------------

// 3. Tentukan Status Transaksi
$status_transaksi = 'pending'; // Default

if ($transaction == 'capture') {
    // Untuk pembayaran kartu kredit
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            $status_transaksi = 'pending';
        } else {
            $status_transaksi = 'dibayar';
        }
    }
} else if ($transaction == 'settlement') {
    // Sukses Bayar (Transfer, GoPay, dll)
    $status_transaksi = 'dibayar';
} else if ($transaction == 'pending') {
    // Menunggu Pembayaran
    $status_transaksi = 'pending';
} else if ($transaction == 'deny') {
    // Ditolak
    $status_transaksi = 'batal';
} else if ($transaction == 'expire') {
    // Kadaluarsa (User tidak bayar tepat waktu)
    $status_transaksi = 'batal';
} else if ($transaction == 'cancel') {
    // Dibatalkan User
    $status_transaksi = 'batal';
}

// 4. Update Database (Tabel Transaksi & Janji)

// Cari dulu ID Janji berdasarkan Order ID
$cek_data = $conn->query("SELECT janji_id FROM transaksi WHERE order_id = '$order_id'");

if ($cek_data->num_rows > 0) {
    $data = $cek_data->fetch_assoc();
    $id_janji = $data['janji_id'];

    // A. Update Status di Tabel TRANSAKSI
    // (Perhatikan: kita gunakan prepare statement agar lebih aman)
    $updateTrans = $conn->prepare("UPDATE transaksi SET status = ? WHERE order_id = ?");
    $updateTrans->bind_param("ss", $status_transaksi, $order_id);
    $updateTrans->execute();

    // B. Update Status di Tabel JANJI (Hanya jika status berubah jadi 'dibayar' atau 'batal')
    // Jika pending, tabel janji biarkan tetap pending (default)
    if ($status_transaksi == 'dibayar') {
        $conn->query("UPDATE janji SET status = 'dibayar' WHERE id = '$id_janji'");
    } elseif ($status_transaksi == 'batal') {
        $conn->query("UPDATE janji SET status = 'batal' WHERE id = '$id_janji'");
    }
    
    // Kirim respon OK ke Midtrans agar tidak dikirim ulang
    echo "OK, Status Updated to $status_transaksi";

} else {
    // Jika Order ID tidak ditemukan di database kita
    echo "Order ID Not Found";
}
?>
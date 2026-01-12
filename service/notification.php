<?php
require_once dirname(__FILE__) . '/../config/midtrans_config.php';
include '../service/koneksi.php';

try {
    $notif = new \Midtrans\Notification();
} catch (\Exception $e) {
    exit($e->getMessage());
}

$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

$log_msg = date("Y-m-d H:i:s") . " | Order: $order_id | Status: $transaction \n";
file_put_contents('midtrans.log', $log_msg, FILE_APPEND);
// -----------------------------------------------------

$status_transaksi = 'pending';

if ($transaction == 'capture') {
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            $status_transaksi = 'pending';
        } else {
            $status_transaksi = 'dibayar';
        }
    }
} else if ($transaction == 'settlement') {
    $status_transaksi = 'dibayar';
} else if ($transaction == 'pending') {
    $status_transaksi = 'pending';
} else if ($transaction == 'deny') {
    $status_transaksi = 'batal';
} else if ($transaction == 'expire') {
    $status_transaksi = 'batal';
} else if ($transaction == 'cancel') {
    $status_transaksi = 'batal';
}

$cek_data = $conn->query("SELECT janji_id FROM transaksi WHERE order_id = '$order_id'");

if ($cek_data->num_rows > 0) {
    $data = $cek_data->fetch_assoc();
    $id_janji = $data['janji_id'];

    $updateTrans = $conn->prepare("UPDATE transaksi SET status = ? WHERE order_id = ?");
    $updateTrans->bind_param("ss", $status_transaksi, $order_id);
    $updateTrans->execute();

    if ($status_transaksi == 'dibayar') {
        $conn->query("UPDATE janji SET status = 'dibayar' WHERE id = '$id_janji'");
    } elseif ($status_transaksi == 'batal') {
        $conn->query("UPDATE janji SET status = 'batal' WHERE id = '$id_janji'");
    }

    echo "OK, Status Updated to $status_transaksi";
} else {
    echo "Order ID Not Found";
}

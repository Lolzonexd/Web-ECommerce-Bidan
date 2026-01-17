<?php
session_start();
include 'koneksi.php';

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

$mailconfig = require '../config/mail.php';

$email = $_POST['email'] ?? '';

$sql = "SELECT id, username FROM user WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Email tidak ditemukan";
    header("Location: ../page/lupaPassword.php");
    exit;
}

$user = $result->fetch_assoc();

// Generate token
$token = bin2hex(random_bytes(32));
$expired = date("Y-m-d H:i:s", strtotime("+1 hour"));

// Simpan token
$update = $conn->prepare(
    "UPDATE user SET reset_token=?, reset_expired=? WHERE id=?"
);
$update->bind_param("ssi", $token, $expired, $user['id']);
$update->execute();

// Kirim email
$link = "https://pmbnurhasanahpga.site/page/resetPassword.php?token=$token";
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $mailconfig['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $mailconfig['username'];
    $mail->Password   = $mailconfig['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = $mailconfig['port'];

    $mail->setFrom($mailconfig['username'], 'PMB Nurhasanah');
    $mail->addAddress($email, $user['usename']);

    $mail->isHTML(true);
    $mail->Subject = 'Konfirmasi Reset Password';
    $mail->Body    = "
       <div style='font-family: Arial, sans-serif; color: #333;'>
            <h2 style='color: #6a9c89;'>Halo, {$user['username']}! 👋</h2>
            <p>Apakah kamu ingin melakukan reset password <b>PMB Nurhasanah</b>?.</p>
            <p>Abaikan saja jika kamu tidak ingin reset password</p>
            <br>
            <p>Silakan reset password melalui tautan berikut:</p>
            <a href='$link' style='background: #6a9c89; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Password</a>
            <br><br>
            <p>Salam Sehat,<br><b>Admin PMB Nurhasanah</b></p>
        </div>
    ";

    $mail->send();

    $_SESSION['reset_success'] = "Link reset password telah dikirim ke email.";
    header("Location: ../page/login.php");
    exit;
} catch (Exception $e) {
    $_SESSION['error'] = "Gagal mengirim email.";
    header("Location: ../page/lupaPassword.php");
    exit;
}

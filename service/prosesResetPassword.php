<?php
session_start();
require 'koneksi.php';

$token    = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($token) || empty($password)) {
    $_SESSION['reset_error'] = "Data tidak lengkap.";
    header("Location: ../page/resetPassword.php");
    exit();
}

if (strlen($password) < 8) {
    $_SESSION['reset_error'] = "Password minimal 8 karakter.";
    header("Location: ../page/resetPassword.php");
    exit();
}

$sql = "
    SELECT 
        u.id,
        u.email,
        u.username,
        b.nama_lengkap
    FROM user u
    LEFT JOIN biodata b ON b.user_id = u.id
    WHERE u.reset_token = ?
      AND u.reset_expired > NOW()
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['reset_error'] = "Token tidak valid atau sudah kadaluarsa.";
    header("Location: ../page/resetPassword.php");
    exit();
}

$userData = $result->fetch_assoc();

$userName = $userData['nama_lengkap']
    ?: $userData['username']
    ?: 'Pengguna';

$email = $userData['email'];
$userId = $userData['id'];

$hashed = password_hash($password, PASSWORD_BCRYPT);

$update = $conn->prepare("
    UPDATE user 
    SET password = ?, reset_token = NULL, reset_expired = NULL
    WHERE id = ?
");
$update->bind_param("si", $hashed, $userId);
$update->execute();

if ($update->affected_rows !== 1) {
    $_SESSION['reset_error'] = "Gagal memperbarui password.";
    header("Location: ../page/resetPassword.php");
    exit();
}

require_once __DIR__ . '/../vendor/autoload.php';
$mailconfig = require '../config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $mail->addAddress($email, $userName);

    $safeName = htmlspecialchars($userName, ENT_QUOTES, 'UTF-8');

    $mail->isHTML(true);
    $mail->Subject = 'Reset Password Berhasil';

    $mail->Body = "
        Halo <b>$safeName</b>,<br><br>

        Password akun Anda telah <b>berhasil diubah</b>.<br><br>

        Jika Anda merasa <b>tidak melakukan</b> perubahan ini,
        segera hubungi admin atau amankan akun Anda.<br><br>

        Salam,<br>
        <b>PMB Nurhasanah</b>
    ";

    $mail->send();
} catch (Exception $e) {
    // email gagal -> tidak perlu gagalkan reset
}

$_SESSION['reset_success'] = "Password berhasil direset. Silakan login.";
header("Location: ../page/login.php");
exit();

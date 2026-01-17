<?php
session_start();
include 'koneksi.php';

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Composer autoload (PAKAI INI SAJA)
require_once __DIR__ . '/../vendor/autoload.php';

$mailconfig = require '../config/mail.php';

// Cek Input dari page/register.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $level = 'user';
    $password = $_POST['password'];

    // Cek Panjang Password
    if (strlen($password) < 8) {
        $_SESSION['registration_error'] = "Password minimal 8 karakter.";
        header("Location: ../page/register.php");
        exit;
    }

    // Cek Email
    $sqlEmail = "SELECT email FROM user WHERE email = ?";
    $check = $conn->prepare($sqlEmail);
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['registration_error'] = "Email sudah terdaftar.";
        header("Location: ../page/register.php");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO user (username, email, password, level) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Database Error
    if (!$stmt) {
        $_SESSION['registration_error'] = "Terjadi Kesalahan pada server, coba beberapa saat lagi.";
        header("Location: ../page/register.php");
        exit;
    }

    $stmt->bind_param("ssss", $username, $email, $hashed_password, $level);

    // Pendaftaran Berhasil
    if ($stmt->execute()) {

        // Kirim Email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $mailconfig['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailconfig['username'];
            $mail->Password   = $mailconfig['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $mailconfig['port'];
        
            $mail->setFrom($mailconfig['username'], 'PMB Nurhasanah');
            $mail->addAddress($email, $usename);
        
            $mail->isHTML(true);
            $mail->Subject = 'Akun Berhasil Dibuat';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; color: #333;'>
                    <h2 style='color: #6a9c89;'>Halo, $username! 👋</h2>
                    <p>Terima kasih telah mendaftar di <b>PMB Nurhasanah</b>.</p>
                    <p>Akun Anda kini sudah aktif. Anda dapat login untuk menikmati layanan kami seperti:</p>
                    <ul>
                        <li>Booking Jadwal Periksa & Imunisasi</li>
                        <li>Konsultasi Online</li>
                        <li>Melihat Riwayat Kesehatan</li>
                    </ul>
                    <br>
                    <p>Silakan login melalui tautan berikut:</p>
                    <a href='https://pmbnurhasanahpga.site/page/login.php' style='background: #6a9c89; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login Sekarang</a>
                    <br><br>
                    <p>Salam Sehat,<br><b>Admin PMB Nurhasanah</b></p>
                </div>
            ";
        
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        $_SESSION['registration_success'] = "Pendaftaran berhasil! Silakan login.";
        header("Location: ../page/login.php");
        exit();

    } else {
        $_SESSION['registration_error'] = "Terjadi Kesalahan pada server, coba beberapa saat lagi.";
        header("Location: ../page/register.php");
        exit;
    }

} else {
    header("Location: ../page/register.php");
    exit();
}

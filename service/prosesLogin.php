<?php
session_start();
include 'koneksi.php';

// Cek Input dari page/login.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, email, password, level 
            FROM user 
            WHERE username = ? OR email = ?";

    $stmt = $conn->prepare($sql);

    // Database Error
    if (!$stmt) {
        $_SESSION['login_error'] = "Terjadi Kesalahan pada server, coba beberapa saat lagi: ";
        header("Location: ../page/login.php");
        exit;
    }

    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek User
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Cek Password
        if (password_verify($password, $user['password'])) {

            // Cek Tetap Login
            if (!empty($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                $hashedToken = hash('sha256', $token);

                // 30 hari
                $expire = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30);

                $sqlToken = "UPDATE user 
                            SET remember_token = ?, remember_expire = ? 
                            WHERE id = ?";

                $stmtToken = $conn->prepare($sqlToken);
                $stmtToken->bind_param("ssi", $hashedToken, $expire, $user['id']);
                $stmtToken->execute();

                setcookie(
                    "remember_me",
                    $token,
                    [
                        "expires" => time() + 60 * 60 * 24 * 30,
                        "path" => "/",
                        "secure" => true,        // HTTPS wajib
                        // "httponly" => true,   // cegah JS akses -> Nanti pas hosting
                        "samesite" => "Strict",  // cegah CSRF
                    ]
                );
            }

            session_regenerate_id(true);
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];

            switch ($user['level']) {
                case 'admin':
                    header("Location: ../admin/dashboardAdmin.php");
                    exit;

                case 'user':
                    header("Location: ../public/dashboardUser.php");
                    exit;

                default:
                    $_SESSION['login_error'] = "Terjadi Kesalahan pada server, coba beberapa saat lagi: ";
                    header("Location: ../page/login.php");
                    exit;
            }
        } else {
            $_SESSION['login_error'] = "Password salah.";
        }
    } else {
        $_SESSION['login_error'] = "Username/Email tidak ditemukan.";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../page/login.php");
    exit;
} else {
    header("Location: ../page/login.php");
    exit;
}

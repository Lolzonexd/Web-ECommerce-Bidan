<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../service/koneksi.php';

// Kalau belum ada session, cek cookie
if (!isset($_SESSION['loggedin']) && isset($_COOKIE['remember_me'])) {

    $hashedToken = hash('sha256', $_COOKIE['remember_me']);

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
        }
    }
}

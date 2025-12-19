<?php
// Pastikan session dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include koneksi database
require_once __DIR__ . '/../service/koneksi.php';

// --------------------------------------------
// 1. Jika SESSION SUDAH ADA → redirect sesuai level
// --------------------------------------------
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {

    // Kalau authCheck dipanggil di dashboard, jangan redirect ulang
    $current = basename($_SERVER['PHP_SELF']);

    if ($_SESSION['level'] === 'admin') {
        if ($current !== 'dashboardAdmin.php') {
            header("Location: ../admin/dashboardAdmin.php");
            exit;
        }
    } else {
        if ($current !== 'dashboardUser.php') {
            header("Location: ../public/dashboardUser.php");
            exit;
        }
    }
    return; // Hentikan agar HTML halaman tetap tampil
}

// --------------------------------------------
// 2. SESSION belum ada → cek COOKIE remember_me
// --------------------------------------------
// if (isset($_COOKIE['remember_me'])) {

//     $token = $_COOKIE['remember_me'];
//     $hashedToken = hash('sha256', $token);

//     $sql = "SELECT id, username, level, remember_expire 
//             FROM user 
//             WHERE remember_token = ?";

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $hashedToken);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     // Token ditemukan
//     if ($result->num_rows === 1) {
//         $user = $result->fetch_assoc();

//         // Token MASIH berlaku
//         if (strtotime($user['remember_expire']) > time()) {

//             // Regenerate session ID untuk keamanan
//             session_regenerate_id(true);

//             $_SESSION['loggedin'] = true;
//             $_SESSION['user_id']  = $user['id'];
//             $_SESSION['username'] = $user['username'];
//             $_SESSION['level']    = $user['level'];

//             // Redirect sesuai level
//             if ($user['level'] === 'admin') {
//                 header("Location: ../admin/dashboard.php");
//                 exit;
//             } else {
//                 header("Location: ../public/dashboard.php");
//                 exit;
//             }
//         } else {
//             // Kalau token expired → hapus cookie
//             setcookie("remember_me", "", time() - 3600, "/");
//         }
//     }
// }

// --------------------------------------------
// 3. Kalau tidak ada session & cookie → biarkan halaman tampil (guest mode)
// --------------------------------------------

return;

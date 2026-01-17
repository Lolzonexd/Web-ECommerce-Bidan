<?php
require '../service/koneksi.php';

$token = $_GET['token'] ?? '';

$sql = "SELECT id FROM user 
        WHERE reset_token=? AND reset_expired > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<h3 style='text-align:center; margin-top:50px;'>Error: Link kadaluarsa atau tidak valid.<br><a href='lupaPassword.php'>Request ulang di sini</a></h3>");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../asset/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-box">
            <div class="auth-header">
                <h2>Password Baru</h2>
                <p>Silakan buat password baru untuk akun Anda.</p>
            </div>

            <form action="../service/prosesResetPassword.php" method="POST">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required minlength="8">
                </div>
                
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" class="btn-submit">Ubah Password</button>
            </form>
        </div>
    </div>
</body>
</html>
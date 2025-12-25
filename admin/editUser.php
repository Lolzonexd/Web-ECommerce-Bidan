<?php
session_start();
include '../service/koneksi.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['level'] !== 'admin') {
    header("location: login.php");
    exit;
}

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM user WHERE id='$id'")->fetch_assoc();

if (isset($_POST['update_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $level    = $_POST['level'];
    
    // Logika Update Password (hanya jika diisi)
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE user SET username='$username', email='$email', level='$level', password='$password' WHERE id='$id'";
    } else {
        $sql = "UPDATE user SET username='$username', email='$email', level='$level' WHERE id='$id'";
    }

    if ($conn->query($sql)) {
        header("Location: manageUser.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User - Admin</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <nav class="navbar-dashboard">
        <a href="manageUser.php" class="brand"><i class="fas fa-arrow-left"></i> Batal</a>
        <div class="nav-right"><span class="user-greeting">Edit Data User</span></div>
    </nav>

    <div class="dashboard-container" style="max-width: 500px;">
        <div class="card-box">
            <h3 class="card-title"><i class="fas fa-user-edit"></i> Edit User</h3>
            
            <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Level</label>
                    <select name="level" class="form-control">
                        <option value="user" <?= $data['level'] == 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $data['level'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="bidan" <?= $data['level'] == 'bidan' ? 'selected' : '' ?>>Bidan</option>
                    </select>
                </div>

                <div class="form-group" style="background: #f9f9f9; padding: 10px; border-radius: 5px; border: 1px dashed #ccc;">
                    <label style="color: #e64848;">Reset Password (Opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Isi hanya jika ingin mengganti password">
                    <small style="color:#666;">Biarkan kosong jika tidak ingin mengubah password.</small>
                </div>

                <button type="submit" name="update_user" class="btn-submit">Simpan Perubahan</button>
            </form>
        </div>
    </div>

</body>
</html>
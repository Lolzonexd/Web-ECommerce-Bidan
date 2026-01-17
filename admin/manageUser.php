<?php
session_start();
include '../service/koneksi.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['level'] !== 'admin') {
    header("location: login.php");
    exit;
}

if (isset($_POST['tambah_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $level    = $_POST['level'];

    $cekEmail = $conn->query("SELECT id FROM user WHERE email = '$email'");
    if ($cekEmail->num_rows > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        $sql = "INSERT INTO user (username, email, password, level) VALUES ('$username', '$email', '$password', '$level')";
        if ($conn->query($sql)) {
            header("Location: manageUser.php?status=sukses");
            exit;
        } else {
            $error = "Gagal menambah user: " . $conn->error;
        }
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if ($id == $_SESSION['id']) {
        echo "<script>alert('Anda tidak bisa menghapus akun sendiri!'); window.location='manageUser.php';</script>";
        exit;
    }

    $conn->query("DELETE FROM user WHERE id='$id'");
    header("Location: manageUser.php?status=hapus");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola User - Admin</title>
    <link rel="stylesheet" href="../asset/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .admin-grid {
                grid-template-columns: 1fr;
            }
        }

        .btn-action {
            padding: 6px 10px;
            border-radius: 5px;
            color: white;
            margin-right: 5px;
            font-size: 0.8rem;
        }

        .btn-edit {
            background: #f39c12;
        }

        .btn-del {
            background: #e74c3c;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            color: white;
        }

        .badge-admin {
            background: #e74c3c;
        }

        .badge-user {
            background: #3498db;
        }

        .badge-bidan {
            background: #2ecc71;
        }
    </style>
</head>

<body>

    <nav class="navbar-dashboard">
        <a href="dashboardAdmin.php" class="brand"><i class="fas fa-arrow-left"></i> Kembali</a>
        <div class="nav-right"><span class="user-greeting">Manajemen User</span></div>
    </nav>

    <div class="dashboard-container">

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
            <div style="background:#d4edda; color:#155724; padding:15px; border-radius:10px; margin-bottom:20px;">
                <i class="fas fa-check"></i> Berhasil menambah user baru!
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div style="background:#f8d7da; color:#721c24; padding:15px; border-radius:10px; margin-bottom:20px;">
                <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="admin-grid">

            <div class="card-box">
                <h3 class="card-title"><i class="fas fa-user-plus"></i> Tambah User Baru</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Nama Pengguna">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="email@contoh.com">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="******">
                    </div>
                    <div class="form-group">
                        <label>Level Akses</label>
                        <select name="level" class="form-control">
                            <option value="user">User (Pasien)</option>
                            <option value="admin">Admin</option>
                            <option value="bidan">Bidan</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah_user" class="btn-submit">Simpan User</button>
                </form>
            </div>

            <div class="card-box">
                <h3 class="card-title"><i class="fas fa-users"></i> Daftar Pengguna Terdaftar</h3>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username / Email</th>
                                <th>Level</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $my_id = $_SESSION['id'] ?? $_SESSION['user_id'];

                            $result = $conn->query("SELECT * FROM user ORDER BY id DESC");
                            $no = 1;
                            while ($row = $result->fetch_assoc()):
                                $badgeClass = 'badge-user';
                                if ($row['level'] == 'admin') $badgeClass = 'badge-admin';
                                if ($row['level'] == 'bidan') $badgeClass = 'badge-bidan';
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['username']) ?></strong><br>
                                        <small style="color:#888;"><?= htmlspecialchars($row['email']) ?></small>
                                    </td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= ucfirst($row['level']) ?></span></td>
                                    <td>
                                        <a href="editUser.php?id=<?= $row['id'] ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>

                                        <?php if ($row['id'] != $my_id): ?>
                                            <a href="manageUser.php?hapus=<?= $row['id'] ?>" class="btn-action btn-del" onclick="return confirm('Yakin hapus user ini? Data biodata & janji terkait juga akan hilang!')"><i class="fas fa-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <?php include '../layout/footer.html'; ?>
</body>

</html>
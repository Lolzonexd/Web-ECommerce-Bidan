<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include '../layout/header.html'; ?>

    <h1>Dashboard User</h1>

    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        echo "<p>Anda masuk sebagai: <b>" . htmlspecialchars($_SESSION['username']) . "</b> (Level: " . htmlspecialchars($_SESSION['level']) . ")</p>";
        echo "<p><a href='../service/logout.php'>Logout</a></p>";
    } else {
        echo "<p>Silakan <a href='page/login.php'>Login</a> atau <a href='page/register.php'>Register</a>.</p>";
    }
    ?>

    <?php include '../layout/footer.html'; ?>
</body>

</html>
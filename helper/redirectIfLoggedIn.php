<?php
require_once __DIR__ . '/authChecker.php';

if (isset($_SESSION['loggedin'])) {
    if ($_SESSION['level'] === 'admin') {
        header("Location: ../admin/dashboardAdmin.php");
        exit;
    } else {
        header("Location: ../public/dashboardUser.php");
        exit;
    }
}

<?php
require_once __DIR__ . '/authChecker.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../page/login.php");
    exit;
}

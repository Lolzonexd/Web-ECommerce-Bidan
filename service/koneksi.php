<?php
$db = require __DIR__ . '/../config/database.php';

$conn = new mysqli(
    $db['host'],
    $db['user'],
    $db['pass'],
    $db['name']
);

// Database Error
if ($conn->connect_error) {
    header("Location: ../page/databaseError.html");
    exit();
}

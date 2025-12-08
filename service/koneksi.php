<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "dbbidan";

$conn = new mysqli($host, $user, $pass, $db);

// Database Error
if ($conn->connect_error) {
    header("Location: databaseError.php");
    exit();
}

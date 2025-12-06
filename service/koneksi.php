<?php


$host     = "localhost";
$user     = "root";
$pass     = ""; 
$db       = "dbbidan"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}
?>
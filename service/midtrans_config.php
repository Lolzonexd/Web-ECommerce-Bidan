<?php
require_once dirname(__FILE__) . '/../vendor/autoload.php'; 

// Set konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'Mid-server-R-EagaH4ndx7Q4pPZQnLpDlK'; //Server key midtrans
\Midtrans\Config::$isProduction = false; // False karena mode Sandbox
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;
?>
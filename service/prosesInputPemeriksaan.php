<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $janji_id = $_POST['janji_id'];
    $keluhan  = $_POST['keluhan'];
    $tensi    = $_POST['tensi'];
    $berat    = $_POST['berat'];
    $diagnosa = $_POST['diagnosa'];
    $tindakan = $_POST['tindakan'];
    $resep    = $_POST['resep'];

    $sql = "INSERT INTO rekam_medis (janji_id, keluhan, tensi_darah, berat_badan, diagnosa, tindakan, resep_obat) 
            VALUES ('$janji_id', '$keluhan', '$tensi', '$berat', '$diagnosa', '$tindakan', '$resep')";

    if ($conn->query($sql) === TRUE) {
        $conn->query("UPDATE janji SET status = 'selesai' WHERE id = '$janji_id'");

        echo "<script>
                alert('Pemeriksaan Selesai! Data berhasil disimpan.');
                window.location.href = '../bidan/dashboardBidan.php';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

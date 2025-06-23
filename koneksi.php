<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "kelompok5";

// Koneksi ke database
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

<?php
include '../koneksi.php';
include 'auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pesanan = intval($_POST['id_pesanan']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "UPDATE pesanan SET status = '$status' WHERE id_pesanan = $id_pesanan";
    if (mysqli_query($conn, $query)) {
        header("Location: kelola_pesanan.php");
        exit;
    } else {
        echo "Gagal memperbarui status: " . mysqli_error($conn);
    }
} else {
    echo "Metode tidak diizinkan.";
}

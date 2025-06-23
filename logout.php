<?php
session_start();

// Hapus semua data login tapi simpan keranjang
if (isset($_SESSION['keranjang'])) {
    $keranjang_backup = $_SESSION['keranjang'];
}

// Hapus semua session
session_unset();
session_destroy();

// Mulai ulang sesi dan restore keranjang
session_start();
if (isset($keranjang_backup)) {
    $_SESSION['keranjang'] = $keranjang_backup;
}

// Redirect ke halaman login atau beranda
header("Location: index.php");
exit;

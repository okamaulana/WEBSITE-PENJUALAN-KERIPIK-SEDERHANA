<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login dulu.'); location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];
$produk_checkout = [];

// ✅ Jika checkout langsung dari produk (URL ada ?id_produk)
if (isset($_GET['id_produk'])) {
    $id_produk = intval($_GET['id_produk']);
    $jumlah = isset($_GET['jumlah']) ? intval($_GET['jumlah']) : 1;

    $produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id_produk"));

    if ($produk) {
        $produk_checkout[$id_produk] = [
            'data' => $produk,
            'jumlah' => $jumlah
        ];
    } else {
        echo "<script>alert('Produk tidak ditemukan.'); location.href='index.php';</script>";
        exit;
    }
}
// ✅ Jika dari keranjang (ambil dari tabel keranjang)
else {
    $keranjang = mysqli_query($conn, "
        SELECT k.jumlah, p.* 
        FROM keranjang k 
        JOIN produk p ON k.id_produk = p.id_produk 
        WHERE k.id_user = $id_user
    ");

    if (mysqli_num_rows($keranjang) === 0) {
        echo "<script>alert('Keranjang kamu kosong.'); location.href='index.php';</script>";
        exit;
    }

    while ($row = mysqli_fetch_assoc($keranjang)) {
        $produk_checkout[$row['id_produk']] = [
            'data' => $row,
            'jumlah' => $row['jumlah']
        ];
    }
}

// ✅ Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'bukti/';
    $bukti = $_FILES['bukti_bayar'];

    if ($bukti['error'] === 0) {
        $filename = uniqid() . '_' . basename($bukti['name']);
        $uploadPath = $uploadDir . $filename;
        move_uploaded_file($bukti['tmp_name'], $uploadPath);
    } else {
        echo "<script>alert('Upload bukti gagal!'); history.back();</script>";
        exit;
    }

    foreach ($produk_checkout as $id_produk => $item) {
        $jumlah = $item['jumlah'];
        $query = "INSERT INTO pesanan (id_user, id_produk, jumlah, bukti_bayar, status)
                  VALUES ('$id_user', '$id_produk', '$jumlah', '$filename', 'menunggu')";
        mysqli_query($conn, $query);
    }

    // ✅ Hapus isi keranjang jika checkout dari keranjang
    if (!isset($_GET['id_produk'])) {
        mysqli_query($conn, "DELETE FROM keranjang WHERE id_user = $id_user");
    }

    echo "<script>alert('Pesanan berhasil dibuat!'); location.href='status_pesanan.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout | SNACKVERSE</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #eef1f8;
            padding: 40px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 20px;
            border-left: 5px solid #4e54c8;
            padding-left: 15px;
        }
        table {
            width: 100%;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 20px;
        }
        input[type="file"] {
            margin-top: 10px;
        }
        button {
            margin-top: 30px;
            padding: 10px 20px;
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Checkout & Upload Bukti Pembayaran</h2>
        <form method="POST" enctype="multipart/form-data">
            <table>
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($produk_checkout as $item):
                        $produk = $item['data'];
                        $jumlah = $item['jumlah'];
                        $subtotal = $produk['harga'] * $jumlah;
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
                        <td><?= $jumlah ?></td>
                        <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td><strong>Rp<?= number_format($total, 0, ',', '.') ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <label for="bukti_bayar">Upload Bukti Pembayaran:</label>
            <input type="file" name="bukti_bayar" id="bukti_bayar" accept="image/*" required>

            <button type="submit">Konfirmasi Pesanan</button>
        </form>
    </div>
</body>
</html>

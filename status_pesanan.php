<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login dulu!'); location.href='login.php';</script>";
    exit;
}

$id_user = intval($_SESSION['id_user']);

$query = "SELECT 
            p.id_pesanan,
            pr.nama_produk,
            pr.harga,
            p.jumlah,
            p.bukti_bayar,
            p.status
          FROM pesanan p
          JOIN produk pr ON p.id_produk = pr.id_produk
          WHERE p.id_user = $id_user
          ORDER BY p.id_pesanan DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Pesanan Saya | GigitTerus.id</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #b2ebf2, #ffffff);
            margin: 0;
            padding: 0;
        }

        header {
            padding: 20px;
            text-align: center;
            font-size: 2.5em;
            font-weight: bold;
            letter-spacing: 2px;
            color: #ffffff;
            background: linear-gradient(to right, #00cfff, #0077ff);
            text-shadow: 0 0 10px #00e0ff, 0 0 20px #00aaff;
            box-shadow: 0 0 20px rgba(0, 200, 255, 0.6);
        }

        nav {
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            padding: 15px 0;
            gap: 30px;
            box-shadow: 0 0 10px rgba(0, 100, 255, 0.2);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(8px);
        }

        nav a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 1px solid #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
            background-color: white;
            margin-left: 15px;
        }

        nav a:hover {
            background-color: #007bff;
            color: white;
            box-shadow: 0 0 12px #00cfff;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        h2 {
            color: #009688;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.8em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        thead th {
            background: linear-gradient(to right, #00bcd4, #009688);
            color: white;
            padding: 14px;
            text-align: center;
            font-weight: 600;
        }

        tbody tr {
            background-color: #fefefe;
            transition: background-color 0.2s ease-in-out;
        }

        tbody tr:nth-child(even) {
            background-color: #f0faff;
        }

        tbody tr:hover {
            background-color: #e0f7ff;
        }

        tbody td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }

        img {
            max-width: 80px;
            border-radius: 6px;
            border: 2px solid #00cfff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .status {
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.diproses {
            color: #0277bd;
        }

        .status.dikonfirmasi {
            color: #fb8c00;
        }

        .status.dikirim {
            color: #6a1b9a;
        }

        .status.selesai {
            color: #2e7d32;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tbody td {
                padding: 10px;
                border: none;
                position: relative;
                text-align: left;
            }

            tbody td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                margin-bottom: 6px;
                color: #009688;
            }
        }
    </style>
</head>
<body>

<header>GigitTerus.id</header>

<nav>
    <a href="index.php">Beranda</a>
    <a href="tentang.php">Tentang</a>
    <a href="keranjang.php">Keranjang</a>
    <a href="status_pesanan.php" style="background:#00bcd4; color:white;">Status Pesanan</a>
    <a href="logout.php">Logout</a>
    <?php if (isset($_SESSION['username'])): ?>
    <div style="margin-left: auto; padding-right: 20px; font-weight: bold; color: #007bff;">
        Hai, <?= htmlspecialchars($_SESSION['username']) ?> 👋
    </div>
    <?php endif; ?>
</nav>

<div class="container">
    <h2>Status Pesanan Saya</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Bukti Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $statusClass = strtolower(str_replace(' ', '', $row['status']));
                ?>
                    <tr>
                        <td data-label="Nama Produk"><?= htmlspecialchars($row['nama_produk']) ?></td>
                        <td data-label="Jumlah"><?= intval($row['jumlah']) ?></td>
                        <td data-label="Harga">Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td data-label="Status" class="status <?= $statusClass ?>">
                            <?= !empty($row['status']) ? htmlspecialchars($row['status']) : '<span style="color:gray;">Belum diproses</span>' ?>
                        </td>
                        <td data-label="Bukti Pembayaran">
                            <?php if (!empty($row['bukti_bayar'])): ?>
                                <img src="bukti/<?= htmlspecialchars($row['bukti_bayar']) ?>" alt="Bukti">
                            <?php else: ?>
                                <span style="color: gray;">Belum Upload</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">Belum ada pesanan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

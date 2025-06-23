<?php
include '../koneksi.php';
include 'auth.php';

$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];

    $tgl_awal_sql = mysqli_real_escape_string($conn, $tgl_awal);
    $tgl_akhir_sql = mysqli_real_escape_string($conn, $tgl_akhir);

    $query = "SELECT 
                p.id_pesanan,
                u.nama AS nama_user,
                pr.nama_produk,
                pr.harga,
                p.jumlah,
                p.bukti_bayar,
                p.status,
                p.tanggal
              FROM pesanan p
              JOIN user u ON p.id_user = u.id_user
              JOIN produk pr ON p.id_produk = pr.id_produk
              WHERE DATE(p.tanggal) BETWEEN '$tgl_awal_sql' AND '$tgl_akhir_sql'
              ORDER BY p.id_pesanan DESC";

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
            padding: 40px;
            color: #000;
        }

        h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #333;
            border-left: 6px solid #4e54c8;
            padding-left: 15px;
        }

        form {
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        input[type="date"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background: #fff;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: #fff;
            padding: 12px;
            text-align: center;
        }

        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        img {
            max-height: 80px;
            border-radius: 5px;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9em;
            text-align: right;
            color: #444;
        }

        @media print {
            form, .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <h2><i class="fas fa-file-alt"></i> Laporan Pesanan</h2>

    <form method="POST">
        <label for="tgl_awal">Dari:</label>
        <input type="date" name="tgl_awal" id="tgl_awal" required>
        <label for="tgl_akhir">Sampai:</label>
        <input type="date" name="tgl_akhir" id="tgl_akhir" required>
        <button type="submit"><i class="fas fa-search"></i> Tampilkan</button>
        <?php if (!empty($data)): ?>
            <button type="button" class="no-print" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak
            </button>
        <?php endif; ?>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p class="info">Menampilkan data dari tanggal <b><?= htmlspecialchars($tgl_awal) ?></b> sampai <b><?= htmlspecialchars($tgl_akhir) ?></b></p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama User</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Bukti</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($data) > 0): ?>
                    <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= htmlspecialchars($row['nama_user']) ?></td>
                            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                            <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td><?= ucfirst($row['status']) ?></td>
                            <td>
                                <?php if (!empty($row['bukti_bayar'])): ?>
                                    <img src="../bukti/<?= htmlspecialchars($row['bukti_bayar']) ?>" alt="Bukti">
                                <?php else: ?>
                                    <span style="color: gray;">Belum Ada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8">Tidak ada data ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="footer">
            Dicetak pada: <?= date('d-m-Y H:i') ?>
        </div>
    <?php endif; ?>

</body>
</html>

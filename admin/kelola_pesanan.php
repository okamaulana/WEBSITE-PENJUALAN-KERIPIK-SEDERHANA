<?php
include '../koneksi.php';
include 'auth.php';

// Proses update status pesanan + pengurangan stok
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pesanan'], $_POST['status'])) {
    $id_pesanan = intval($_POST['id_pesanan']);
    $status_baru = mysqli_real_escape_string($conn, $_POST['status']);

    $cek = mysqli_query($conn, "SELECT status, id_produk, jumlah FROM pesanan WHERE id_pesanan = $id_pesanan");
    $data_lama = mysqli_fetch_assoc($cek);

    $status_lama = $data_lama['status'];
    $id_produk = $data_lama['id_produk'];
    $jumlah = $data_lama['jumlah'];

    // Update stok hanya jika status baru dikonfirmasi/selesai dan sebelumnya belum
    if (
        in_array($status_baru, ['dikonfirmasi', 'selesai']) &&
        !in_array($status_lama, ['dikonfirmasi', 'selesai'])
    ) {
        mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah WHERE id_produk = $id_produk");
    }

    // Update status pesanan
    $update = mysqli_query($conn, "UPDATE pesanan SET status = '$status_baru' WHERE id_pesanan = $id_pesanan");

    if (!$update) {
        echo "<script>alert('Gagal update status: " . mysqli_error($conn) . "');</script>";
    } else {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Ambil semua data pesanan
$query = "SELECT 
            p.id_pesanan,
            u.nama AS nama_user,
            u.alamat,
            pr.nama_produk,
            pr.harga,
            p.jumlah,
            p.bukti_bayar,
            p.status
          FROM pesanan p
          JOIN user u ON p.id_user = u.id_user
          JOIN produk pr ON p.id_produk = pr.id_produk
          ORDER BY p.id_pesanan DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            min-height: 100vh;
            background: #eef1f8;
        }
        .content {
            flex: 1;
            padding: 40px;
            background: #f9faff;
        }
        .content h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #333;
            border-left: 6px solid #4e54c8;
            padding-left: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }
        th {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: #fff;
            padding: 15px;
            text-align: left;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #444;
            vertical-align: top;
        }
        tr:hover {
            background-color: #f0f4ff;
        }
        img {
            max-width: 100px;
            border-radius: 5px;
        }
        select {
            padding: 6px;
            border-radius: 4px;
            font-weight: bold;
            border: 1px solid #ccc;
        }
        td:nth-child(2), th:nth-child(2) {
            max-width: 250px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h2><i class="fas fa-box"></i> Kelola Pesanan</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama User</th>
                    <th>Alamat</th>
                    <th>Nama Produk</th>
                    <th>Harga Produk</th>
                    <th>Jumlah</th>
                    <th>Bukti Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_user']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['alamat'])) ?></td>
                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td>
                        <?php if (!empty($row['bukti_bayar'])): ?>
                            <img src="../bukti/<?= htmlspecialchars($row['bukti_bayar']) ?>" alt="Bukti">
                        <?php else: ?>
                            <span style="color: gray;">Belum Ada</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="diproses" <?= $row['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                <option value="dikonfirmasi" <?= $row['status'] == 'dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                                <option value="dikirim" <?= $row['status'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                <option value="selesai" <?= $row['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

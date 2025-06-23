<?php
session_start();
$conn = new mysqli("localhost", "root", "", "kelompok5");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $id_produk = intval($_POST['id_produk']);
    $cek = $conn->prepare("SELECT * FROM keranjang WHERE id_user = ? AND id_produk = ?");
    $cek->bind_param("ii", $id_user, $id_produk);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        $conn->query("UPDATE keranjang SET jumlah = jumlah + 1 WHERE id_user = $id_user AND id_produk = $id_produk");
    } else {
        $conn->query("INSERT INTO keranjang (id_user, id_produk, jumlah) VALUES ($id_user, $id_produk, 1)");
    }

    header("Location: keranjang.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id_produk = intval($_GET['hapus']);
    $conn->query("DELETE FROM keranjang WHERE id_user = $id_user AND id_produk = $id_produk");
    header("Location: keranjang.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang | GigitTerus.id</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffffff, #e0f7ff, #cceeff, #b3e0ff);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            color: #333;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
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
            margin: auto;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 200, 255, 0.1);
        }

        h1 {
            text-align: center;
            color: #0077cc;
            margin-bottom: 30px;
            text-shadow: 0 0 5px #cceeff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: linear-gradient(to right, #00bfff, #0099ff);
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            font-size: 0.9em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-checkout {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: linear-gradient(to right, #00cfff, #0099ff);
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 0 15px #00e0ff;
        }

        .btn-checkout:hover {
            background: #007bff;
            box-shadow: 0 0 20px #00e0ff;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
            font-size: 1.2em;
        }

        .center {
            text-align: center;
            margin-top: 40px;
        }

        @media (max-width: 600px) {
            header {
                font-size: 1.8em;
            }

            nav {
                flex-wrap: wrap;
                gap: 10px;
            }

            .container {
                padding: 20px 10px;
            }

            table th, table td {
                font-size: 0.9em;
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
    <a href="status_pesanan.php">Status Pesanan</a>
    <a href="logout.php">Logout</a>
    <?php if (isset($_SESSION['username'])): ?>
    <div style="margin-left: auto; padding-right: 20px; font-weight: bold; color: #007bff;">
        Hai, <?= htmlspecialchars($_SESSION['username']) ?> 👋
    </div>
    <?php endif; ?>
</nav>

<div class="container">
    <h1>Keranjang Belanja</h1>

    <?php
    $total = 0;
    $items = $conn->query("
        SELECT k.jumlah, p.*
        FROM keranjang k
        JOIN produk p ON k.id_produk = p.id_produk
        WHERE k.id_user = $id_user
    ");

    if ($items->num_rows === 0): ?>
        <div class="center">
            <p>Keranjangmu kosong. <a href="index.php">Belanja sekarang</a>.</p>
        </div>
    <?php else: ?>
        <table>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
            <?php $no = 1; while ($produk = $items->fetch_assoc()):
                $subtotal = $produk['harga'] * $produk['jumlah'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
                <td>Rp<?= number_format($produk['harga'], 0, ',', '.') ?></td>
                <td><?= $produk['jumlah'] ?></td>
                <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                <td><a class="btn-danger" href="?hapus=<?= $produk['id_produk'] ?>" onclick="return confirm('Yakin hapus produk ini?')">Hapus</a></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <p class="total">Total: Rp<?= number_format($total, 0, ',', '.') ?></p>
        <div class="center">
            <a class="btn-checkout" href="checkout.php">Checkout</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

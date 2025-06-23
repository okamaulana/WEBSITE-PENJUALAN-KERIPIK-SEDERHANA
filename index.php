<?php
session_start();
$conn = new mysqli("localhost", "root", "", "kelompok5");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$produk = $conn->query("SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda | GigitTerus.id</title>
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
    padding: 40px 20px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: auto;
}

.card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 0 20px rgba(0, 200, 255, 0.1);
    transition: all 0.3s ease;
    border: 2px solid #d0f0ff;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 30px rgba(0, 200, 255, 0.3);
}

.card img {
    width: 70%;
    height: 180px;
    object-fit: cover;
    border-bottom: 1px solid #cceeff;
}

.card h3 {
    font-size: 1.3em;
    margin: 15px;
    color: #0077cc;
    text-shadow: 0 0 5px #cceeff;
}

.card p {
    font-size: 1em;
    color: #555;
    margin: 0 15px 10px;
}

.price {
    font-weight: bold;
    font-size: 1.1em;
    margin: 0 15px 15px;
    color: #009900;
}

button {
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s ease;
}

form button:first-of-type {
    background: linear-gradient(to right, #00e0ff, #00bbff);
    color: white;
    box-shadow: 0 0 10px #00ccff;
}

form button:first-of-type:hover {
    background: #00bfff;
    box-shadow: 0 0 15px #00e0ff;
}

form button:last-of-type {
    background: linear-gradient(to right, #00ffbb, #00ff88);
    color: black;
    box-shadow: 0 0 10px #00ffcc;
}

form button:last-of-type:hover {
    background: #00e69e;
    box-shadow: 0 0 15px #00ffaa;
}

@media (max-width: 600px) {
    header {
        font-size: 1.8em;
    }

    nav {
        flex-wrap: wrap;
        gap: 10px;
    }
}


    </style>
</head>
<body>

<header>GigitTerus.id</header>

<nav>
    <a href="index.php">Beranda</a>
    <a href="tentang.php">Tentang</a>
    <?php if (isset($_SESSION['username'])): ?>
        <a href="keranjang.php">Keranjang</a>
        <a href="status_pesanan.php">Status Pesanan</a>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php?page=login">Login</a>
    <?php endif; ?>
    <?php if (isset($_SESSION['username'])): ?>
    <div style="margin-left: auto; padding-right: 20px; font-weight: bold; color: #007bff;">
        Hai, <?= htmlspecialchars($_SESSION['username']) ?> 👋
    </div>
    <?php endif; ?>
</nav>

<div class="container">
    <?php while ($row = $produk->fetch_assoc()): ?>
        <div class="card">
            <img src="gambar/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
            <h3><?= htmlspecialchars($row['nama_produk']) ?></h3>
            <p><?= htmlspecialchars($row['deskripsi']) ?></p>
            <div class="price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></div>

            <?php if (isset($_SESSION['username'])): ?>
                <div style="display: flex; gap: 10px; margin: 0 15px 15px;">
                    <form method="POST" action="keranjang.php">
                        <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
                        <input type="hidden" name="aksi" value="tambah">
                        <button type="submit" style="
                            background-color: #00bfff;
                            border: none;
                            color: white;
                            padding: 8px 12px;
                            border-radius: 5px;
                            cursor: pointer;
                            font-weight: bold;
                        ">+ Keranjang</button>
                    </form>
                    <form method="GET" action="checkout.php">
    <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
    <input type="hidden" name="jumlah" value="1">
    <button type="submit" style="
        background-color: #28a745;
        border: none;
        color: white;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    ">Checkout</button>
</form>

                </div>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>


</body>
</html>

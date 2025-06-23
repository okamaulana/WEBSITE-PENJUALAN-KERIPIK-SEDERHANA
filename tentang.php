<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tentang Kami | GigitTerus.id</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
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
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .intro {
            text-align: center;
            margin-bottom: 40px;
        }

        .intro h2 {
            color: #0077cc;
            font-size: 2em;
            margin-bottom: 10px;
            text-shadow: 0 0 5px #cceeff;
        }

        .intro p {
            color: #555;
            font-size: 1.1em;
        }

        .anggota {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .card {
            background: #f1fefc;
            border: 2px solid #b2ebf2;
            border-radius: 12px;
            text-align: center;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 200, 255, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 200, 255, 0.3);
        }

        .card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid #00cfff;
            box-shadow: 0 0 8px #00e0ff;
        }

        .card h4 {
            margin: 0;
            color: #00796b;
        }

        .card p {
            margin: 5px 0 0;
            color: #555;
        }

        @media (max-width: 600px) {
            nav {
                flex-direction: column;
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
    <div class="intro">
        <h2>ANGGOTA KELOMPOK</h2>

    </div>

    <div class="anggota">
        <div class="card">
            <img src="asset/oka.jpg" alt="Oka M">
            <h4>OKA MAULANA</h4>
            <p>NIM: 2205903040045</p>
        </div>
        <div class="card">
            <img src="asset/widya.jpg" alt="ilva">
            <h4>ILVA MUSRI FADILAH LUBIS</h4>
            <p>NIM: 2205903040002</p>
        </div>
        <div class="card">
            <img src="asset/ilva.jpg" alt="widya">
            <h4>WIDYANTI SALHA</h4>
            <p>NIM: 2205903040012</p>
        </div>
        <div class="card">
            <img src="asset/jery.jpg" alt="jery">
            <h4>JERRY NURRIYANSYAH</h4>
            <p>NIM: 2205903040057</p>
        </div>
    </div>
</div>

</body>
</html>

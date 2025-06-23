<!-- Tambahkan link font & icon jika belum ada -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    .sidebar {
        width: 260px;
        background: linear-gradient(to bottom, #4e54c8, #8f94fb); /* mewah: ungu ke biru muda */
        color: #fff;
        padding: 30px 20px;
        min-height: 100vh;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 40px;
        font-size: 24px;
        letter-spacing: 1px;
        font-weight: bold;
        position: relative;
    }

    .sidebar h2::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background-color: rgba(255, 255, 255, 0.4);
        margin: 10px auto 0;
        border-radius: 10px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        margin-bottom: 18px;
    }

    .sidebar ul li a {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #fff;
        text-decoration: none;
        padding: 12px 16px;
        border-radius: 10px;
        transition: all 0.3s ease;
        background-color: rgba(255, 255, 255, 0.05);
        font-weight: 500;
    }

    .sidebar ul li a:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(5px);
        box-shadow: 0 0 10px rgba(255,255,255,0.3);
    }

    .sidebar ul li a i {
        width: 20px;
        text-align: center;
    }
</style>

<div class="sidebar">
    <h2>ADMIN PANEL</h2>
    <ul>
        <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="kelola_produk.php"><i class="fas fa-box-open"></i> Kelola Produk</a></li>
        <li><a href="kelola_user.php"><i class="fas fa-users-cog"></i> Kelola User</a></li>
        <li><a href="kelola_laporan.php"><i class="fas fa-chart-line"></i> Kelola Laporan</a></li>
        <li><a href="kelola_pesanan.php"><i class="fas fa-shopping-cart"></i> Kelola Pesanan</a></li>
        <li><a href="kelola_kategori.php"><i class="fas fa-tags"></i> Kelola Kategori</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

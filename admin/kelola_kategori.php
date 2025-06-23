<?php 
include '../koneksi.php'; // koneksi ke DB
include 'auth.php';
// Proses Simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);

    if (!empty($nama_kategori)) {
        $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script>alert('Kategori berhasil disimpan!'); window.location='kelola_kategori.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan kategori.');</script>";
        }
    } else {
        echo "<script>alert('Nama kategori tidak boleh kosong.');</script>";
    }
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori = $id");
    echo "<script>window.location='kelola_kategori.php';</script>";
}

// Ambil data kategori
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori</title>
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

        form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            max-width: 500px;
            margin-bottom: 40px;
        }

        label {
            font-weight: bold;
            color: #555;
            margin-top: 15px;
            display: block;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 8px;
            transition: 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #4e54c8;
            box-shadow: 0 0 5px rgba(78, 84, 200, 0.5);
        }

        button {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: white;
            font-weight: bold;
            padding: 12px;
            border: none;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background: linear-gradient(to right, #8f94fb, #4e54c8);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        table th {
            background: #4e54c8;
            color: #fff;
        }

        table td a {
            color: #d33;
            font-weight: bold;
            text-decoration: none;
        }

        table td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h2>Kelola Kategori</h2>

        <form method="POST">
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" placeholder="Masukkan nama kategori...">
            <button type="submit">Simpan</button>
        </form>

        <?php if (mysqli_num_rows($kategori) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($kategori)): ?>
            <tr>
                <td><?= $row['id_kategori'] ?></td>
                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td><a href="?hapus=<?= $row['id_kategori'] ?>" onclick="return confirm('Yakin mau hapus kategori ini?')">Hapus</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>Belum ada kategori ditambahkan.</p>
        <?php endif; ?>
    </div>
</body>
</html>

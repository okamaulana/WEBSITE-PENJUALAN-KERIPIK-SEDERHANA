<?php
include '../koneksi.php';
include 'auth.php';

// Ambil semua kategori
$kategori_query = mysqli_query($conn, "SELECT * FROM kategori");

// Hapus produk
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk = $id"));
    if ($data && file_exists("../gambar/" . $data['gambar'])) {
        unlink("../gambar/" . $data['gambar']);
    }
    mysqli_query($conn, "DELETE FROM produk WHERE id_produk = $id");
    echo "<script>alert('Produk dihapus!'); window.location='kelola_produk.php';</script>";
    exit;
}

// Ambil data produk jika sedang edit
$edit = false;
$produk_edit = [];
if (isset($_GET['edit'])) {
    $edit = true;
    $id_edit = intval($_GET['edit']);
    $produk_edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id_edit"));
}

// Simpan (tambah atau edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = isset($_POST['id_produk']) ? intval($_POST['id_produk']) : null;
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $stok = intval($_POST['stok']);
    $harga = intval($_POST['harga']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $kategori = intval($_POST['kategori']);
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = "../gambar/";
    if (!is_dir($folder)) mkdir($folder);

    if ($id_produk) {
        // Edit
        if ($gambar) {
            $lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk = $id_produk"));
            if ($lama && file_exists($folder . $lama['gambar'])) {
                unlink($folder . $lama['gambar']);
            }
            move_uploaded_file($tmp, $folder . $gambar);
            $gambar_query = ", gambar = '$gambar'";
        } else {
            $gambar_query = "";
        }

        mysqli_query($conn, "UPDATE produk SET 
            nama_produk = '$nama',
            stok = $stok,
            harga = $harga,
            deskripsi = '$deskripsi',
            id_kategori = $kategori
            $gambar_query
            WHERE id_produk = $id_produk");

        echo "<script>alert('Produk berhasil diperbarui!'); window.location='kelola_produk.php';</script>";
        exit;

    } else {
        // Tambah
        move_uploaded_file($tmp, $folder . $gambar);
        mysqli_query($conn, "INSERT INTO produk (nama_produk, stok, harga, deskripsi, id_kategori, gambar)
                             VALUES ('$nama', $stok, $harga, '$deskripsi', $kategori, '$gambar')");
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='kelola_produk.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk</title>
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
            margin-bottom: 25px;
            color: #333;
            border-left: 6px solid #4e54c8;
            padding-left: 15px;
        }

        form {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            max-width: 600px;
        }

        form label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
            color: #444;
        }

        form input, form select, form textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: 0.3s;
            font-size: 1em;
        }

        form textarea {
            resize: vertical;
        }

        form input:focus, form select:focus, form textarea:focus {
            border-color: #4e54c8;
            outline: none;
            box-shadow: 0 0 5px rgba(78, 84, 200, 0.4);
        }

        button {
            margin-top: 25px;
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: white;
            border: none;
            padding: 12px 20px;
            font-weight: bold;
            font-size: 1em;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        table {
            margin-top: 50px;
            width: 100%;
            background: #fff;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background: #4e54c8;
            color: #fff;
        }

        img {
            max-width: 60px;
            border-radius: 6px;
        }

        .aksi a {
            margin-right: 10px;
            color: #4e54c8;
            text-decoration: none;
        }

        .aksi a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h2><i class="fas fa-box-open"></i> <?= $edit ? 'Edit Produk' : 'Kelola Produk' ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <?php if ($edit): ?>
                <input type="hidden" name="id_produk" value="<?= $produk_edit['id_produk'] ?>">
            <?php endif; ?>

            <label>Nama Produk</label>
            <input type="text" name="nama_produk" required value="<?= $edit ? htmlspecialchars($produk_edit['nama_produk']) : '' ?>">

            <label>Stok</label>
            <input type="number" name="stok" required value="<?= $edit ? $produk_edit['stok'] : '' ?>">

            <label>Harga</label>
            <input type="number" name="harga" required value="<?= $edit ? $produk_edit['harga'] : '' ?>">

            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="4" required><?= $edit ? htmlspecialchars($produk_edit['deskripsi']) : '' ?></textarea>

            <label>Kategori</label>
            <select name="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php
                mysqli_data_seek($kategori_query, 0);
                while ($kat = mysqli_fetch_assoc($kategori_query)) :
                    $selected = $edit && $kat['id_kategori'] == $produk_edit['id_kategori'] ? 'selected' : '';
                ?>
                    <option value="<?= $kat['id_kategori'] ?>" <?= $selected ?>>
                        <?= htmlspecialchars($kat['nama_kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Gambar Produk <?= $edit ? '(kosongkan jika tidak diganti)' : '' ?></label>
            <input type="file" name="gambar" <?= $edit ? '' : 'required' ?>>

            <button type="submit"><i class="fas fa-save"></i> <?= $edit ? 'Update' : 'Simpan' ?></button>
        </form>

        <h2 style="margin-top:60px;"><i class="fas fa-list"></i> Daftar Produk</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $produk = mysqli_query($conn, "SELECT produk.*, kategori.nama_kategori 
                                               FROM produk 
                                               JOIN kategori ON produk.id_kategori = kategori.id_kategori 
                                               ORDER BY id_produk DESC");
                $no = 1;
                while ($row = mysqli_fetch_assoc($produk)) :
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td>Rp<?= number_format($row['harga']) ?></td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td><img src="../gambar/<?= $row['gambar'] ?>" alt=""></td>
                        <td class="aksi">
                            <a href="?edit=<?= $row['id_produk'] ?>"><i class="fas fa-edit"></i> Edit</a>
                            <a href="?hapus=<?= $row['id_produk'] ?>" onclick="return confirm('Yakin hapus produk ini?')"><i class="fas fa-trash"></i> Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

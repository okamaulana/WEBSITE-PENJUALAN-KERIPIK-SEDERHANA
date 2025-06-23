<?php
include '../koneksi.php';
include 'auth.php';

// Tambah user baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['id_user'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
    $nohp     = mysqli_real_escape_string($conn, $_POST['nohp']);

    $query = "INSERT INTO user (nama, username, password, alamat, nohp)
              VALUES ('$nama', '$username', '$password', '$alamat', '$nohp')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('User berhasil ditambahkan!'); window.location='kelola_user.php';</script>";
    } else {
        echo "Gagal menyimpan: " . mysqli_error($conn);
    }
}

// Update user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_user'])) {
    $id_user = $_POST['id_user'];
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
    $nohp     = mysqli_real_escape_string($conn, $_POST['nohp']);

    $update = "UPDATE user SET nama='$nama', username='$username', alamat='$alamat', nohp='$nohp' WHERE id_user='$id_user'";

    if (mysqli_query($conn, $update)) {
        echo "<script>alert('User berhasil diupdate!'); window.location='kelola_user.php';</script>";
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}

// Hapus user
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM user WHERE id_user='$id'");
    echo "<script>alert('User berhasil dihapus!'); window.location='kelola_user.php';</script>";
}

// Ambil data untuk edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE id_user='$id'");
    $edit = mysqli_fetch_assoc($result);
}

// Ambil semua user
$data_user = mysqli_query($conn, "SELECT * FROM user ORDER BY id_user DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User</title>
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
            margin-bottom: 40px;
        }

        form label {
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
            color: #444;
            display: block;
        }

        form input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: 0.3s;
        }

        form input:focus {
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
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }

        table th, table td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #4e54c8;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-title {
            font-size: 1.3em;
            margin-bottom: 15px;
            color: #444;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="content">
    <h2><i class="fas fa-users-cog"></i> <?= $edit ? "Edit User" : "Tambah User" ?></h2>
    <form method="POST">
        <?php if ($edit): ?>
            <input type="hidden" name="id_user" value="<?= $edit['id_user'] ?>">
        <?php endif; ?>
        <label>Nama</label>
        <input type="text" name="nama" value="<?= $edit['nama'] ?? '' ?>" required>

        <label>Username</label>
        <input type="text" name="username" value="<?= $edit['username'] ?? '' ?>" required>

        <?php if (!$edit): ?>
            <label>Password</label>
            <input type="password" name="password" required>
        <?php endif; ?>

        <label>Alamat</label>
        <input type="text" name="alamat" value="<?= $edit['alamat'] ?? '' ?>" required>

        <label>No HP</label>
        <input type="text" name="nohp" value="<?= $edit['nohp'] ?? '' ?>" required>

        <button type="submit"><i class="fas fa-save"></i> <?= $edit ? "Update" : "Simpan" ?></button>
    </form>

    <div class="table-title"><i class="fas fa-table"></i> Daftar User</div>
    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($data_user)) {
            echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama']}</td>
                <td>{$row['username']}</td>
                <td>{$row['alamat']}</td>
                <td>{$row['nohp']}</td>
                <td>
                    <a href='kelola_user.php?edit={$row['id_user']}' style='color:blue;'>Edit</a> |
                    <a href='kelola_user.php?hapus={$row['id_user']}' onclick=\"return confirm('Yakin ingin hapus user ini?')\" style='color:red;'>Hapus</a>
                </td>
            </tr>";
            $no++;
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php 
session_start();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "kelompok5");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $nama     = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $alamat   = trim($_POST['alamat']);
    $nohp     = trim($_POST['nohp']);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cek username
    $stmt = $conn->prepare("SELECT * FROM user WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username sudah digunakan. Silakan pilih yang lain.";
    } else {
        $stmt = $conn->prepare("INSERT INTO user (nama, username, password, alamat, nohp, role) VALUES (?, ?, ?, ?, ?, 'user')");
        $stmt->bind_param("sssss", $nama, $username, $hashed_password, $alamat, $nohp);
        if ($stmt->execute()) {
            $success = "Registrasi berhasil. Silakan <a href='login.php'>login di sini</a>.";
        } else {
            $error = "Gagal menyimpan data: " . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Registrasi User</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: url('https://raw.githubusercontent.com/eliotnet999/gambartugas/main/wall.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
    }

    .register-box {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 10px;
      padding: 40px;
      width: 350px;
      color: #fff;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }

    .register-box h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: none;
      border-radius: 5px;
      background-color: rgba(255, 255, 255, 0.2);
      color: white;
      font-size: 14px;
    }

    ::placeholder {
      color: #ddd;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #7f00ff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background-color: #5a00cc;
    }

    .message {
      text-align: center;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .error {
      color: #ff6b6b;
    }

    .success {
      color: #00e676;
    }

    .login-link {
      text-align: center;
      margin-top: 10px;
      font-size: 13px;
    }

    .login-link a {
      color: #fff;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="register-box">
    <h2>Registrasi</h2>

    <?php if (!empty($error)) echo "<div class='message error'>$error</div>"; ?>
    <?php if (!empty($success)) echo "<div class='message success'>$success</div>"; ?>

    <form method="POST">
      <input type="text" name="nama" placeholder="Nama Lengkap" required />
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <input type="text" name="alamat" placeholder="Alamat" required />
      <input type="text" name="nohp" placeholder="Nomor HP" required />
      <button type="submit">Daftar</button>
    </form>

    <div class="login-link">
      Sudah punya akun? <a href="login.php">Login di sini</a>
    </div>
  </div>
</body>
</html>

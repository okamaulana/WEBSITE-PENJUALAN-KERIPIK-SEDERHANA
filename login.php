<?php
session_start();
$conn = new mysqli("localhost", "root", "", "kelompok5");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$page = isset($_GET['page']) ? $_GET['page'] : 'login';
$error = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($page == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role     = $_POST['role'];

        $stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND role=?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['id_user'] = $user['id_user']; // ✅ penting

                header("Location: " . ($user['role'] === "admin" ? "admin/index.php" : "index.php"));
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username atau role tidak cocok!";
        }
    }

    if ($page == 'forgot') {
        $username = trim($_POST['username']);
        $new_password = trim($_POST['new_password']);
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("SELECT * FROM user WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $update = $conn->prepare("UPDATE user SET password=? WHERE username=?");
            $update->bind_param("ss", $hashed, $username);
            if ($update->execute()) {
                $message = "Password berhasil diperbarui. <a href='login.php?page=login' style='color:lightgreen;'>Login di sini</a>";
            } else {
                $error = "Gagal memperbarui password.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $page == 'forgot' ? 'Reset Password' : 'Login' ?></title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: url('asset/wall.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
    }

    .box {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      padding: 40px;
      width: 320px;
      color: #fff;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      font-weight: 600;
      letter-spacing: 1px;
    }

    select {
      width: 320px;
      padding: 12px;
      margin-bottom: 15px;
      border: none;
      border-radius: 8px;
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
      font-size: 14px;
    }

    input {
      width: 297px;
      padding: 12px;
      margin-bottom: 15px;
      border: none;
      border-radius: 8px;
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
      font-size: 14px;
    }

    select {
      background-color: rgba(255, 255, 255, 0.9);
      color: #000;
    }

    ::placeholder {
      color: #eee;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: rgba(255, 255, 255, 0.3);
      color: #fff;
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 8px;
      font-size: 15px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
    }

    button:hover {
      background-color: rgba(255, 255, 255, 0.5);
      color: #000;
    }

    .link {
      text-align: center;
      margin-top: 15px;
      font-size: 13px;
    }

    .link a {
      color: #fff;
      text-decoration: underline;
    }

    .error {
      color: #ff6b6b;
      text-align: center;
      margin-bottom: 15px;
      font-weight: bold;
    }

    .success {
      color: #00e676;
      text-align: center;
      margin-bottom: 15px;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="box">
    <h2><?= $page == 'forgot' ? 'Reset Password' : 'Login' ?></h2>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <?php if (!empty($message)) echo "<div class='success'>$message</div>"; ?>

    <form method="POST">
      <?php if ($page == 'login'): ?>
        <select name="role" required>
          <option value="">-- Pilih Peran --</option>
          <option value="admin">Admin</option>
          <option value="user">User</option>
        </select>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Login</button>
      <?php else: ?>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="new_password" placeholder="Password Baru" required />
        <button type="submit">Reset Password</button>
      <?php endif; ?>
    </form>

    <div class="link">
      <?php if ($page == 'login'): ?>
        Belum punya akun? <a href="register.php">Daftar di sini</a><br>
        <a href="?page=forgot">Lupa Password?</a>
      <?php else: ?>
        <a href="?page=login">Kembali ke Login</a>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>

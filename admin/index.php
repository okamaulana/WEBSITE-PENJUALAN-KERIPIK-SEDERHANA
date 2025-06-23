<?php session_start(); 
include 'auth.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .content h1 {
            font-size: 2.2em;
            margin-bottom: 20px;
            color: #333;
            border-left: 6px solid #4e54c8;
            padding-left: 15px;
        }

        .content p {
            font-size: 1.1em;
            color: #555;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1><i class="fas fa-home"></i> Dashboard Admin</h1>
        <p>Selamat datang di panel admin. Gunakan menu di samping untuk mengelola sistem dengan mudah dan cepat.</p>
    </div>
</body>
</html>

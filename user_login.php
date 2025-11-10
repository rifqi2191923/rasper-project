<?php
session_start();
include 'db_connect.php';

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Gunakan prepared statement agar aman dari SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username; // ✅ ubah ini sesuai index.php
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Pengguna - Rasper Fashion Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ Tambahkan favicon -->
  <link rel="icon" type="image/png" href="img/logo.png">

  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      width: 400px;
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      text-align: center;
    }
    .login-container img {
      width: 80px;
      margin-bottom: 15px;
    }
    .login-container h3 {
      font-weight: 600;
      margin-bottom: 25px;
      color: #333;
    }
    .btn-login {
      background-color: #000;
      color: #fff;
      font-weight: 500;
      border-radius: 10px;
      width: 100%;
      transition: 0.3s;
    }
    .btn-login:hover {
      background-color: #fff;
      color: #000;
      border: 1px solid #000;
    }
    .error {
      color: red;
      text-align: center;
      font-size: 0.9em;
      margin-bottom: 10px;
    }
    a {
      text-decoration: none;
      color: #000;
      font-weight: 500;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <!-- Logo Rasper -->
    <img src="img/logo.png" alt="Logo Rasper">
    <h3>Login</h3>
    
    <?php if ($error != ''): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3 text-start">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Masukkan username..." required>
      </div>
      <div class="mb-3 text-start">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Masukkan password..." required>
      </div>
      <button type="submit" name="login" class="btn btn-login">Login</button>
    </form>
    <p class="mt-3">Belum punya akun? <a href="user_register.php">Daftar di sini</a></p>
  </div>
</body>
</html>

<?php
include 'db_connect.php';
$message = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    // Cek apakah username sudah terdaftar
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $message = "Username sudah digunakan!";
    } elseif ($password !== $confirm) {
        $message = "Konfirmasi password tidak sama!";
    } else {
        // Simpan data ke database
        $query = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
        if ($query) {
            $message = "Registrasi berhasil! Silakan login.";
        } else {
            $message = "Terjadi kesalahan, coba lagi!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun - Rasper Fashion Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .register-container {
      width: 400px;
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      text-align: center;
    }
    .register-container img {
      width: 80px;
      margin-bottom: 15px;
    }
    .register-container h3 {
      font-weight: 600;
      margin-bottom: 25px;
      color: #333;
    }
    .btn-register {
      background-color: #000;
      color: #fff;
      font-weight: 500;
      border-radius: 10px;
      width: 100%;
      transition: 0.3s;
    }
    .btn-register:hover {
      background-color: #fff;
      color: #000;
      border: 1px solid #000;
    }
    .message {
      color: red;
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
  <div class="register-container">
    <img src="img/logo.png" alt="Logo Rasper">
    <h3>Daftar Akun</h3>

    <?php if ($message != ''): ?>
      <p class="message"><?= $message ?></p>
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
      <div class="mb-3 text-start">
        <label for="confirm" class="form-label">Konfirmasi Password</label>
        <input type="password" name="confirm" class="form-control" placeholder="Ulangi password..." required>
      </div>
      <button type="submit" name="register" class="btn btn-register">Daftar</button>
    </form>
    <p class="mt-3">Sudah punya akun? <a href="user_login.php">Login di sini</a></p>
  </div>
</body>
</html>

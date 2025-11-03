<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = "uploads/" . $gambar;

    if (move_uploaded_file($tmp, $folder)) {
        $sql = "INSERT INTO produk (nama_produk, harga, stok, gambar) 
                VALUES ('$nama', '$harga', '$stok', '$gambar')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Produk berhasil ditambahkan!');window.location='admin_dashboard.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Gagal upload gambar!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Produk</title>
<style>
body {
  font-family: Arial, sans-serif;
  margin: 40px;
  background: #f3f4f6;
}
form {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 350px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
input {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
}
button {
  background: #0077cc;
  color: white;
  border: none;
  padding: 10px;
  width: 100%;
  border-radius: 5px;
  cursor: pointer;
}
button:hover { background: #005fa3; }
</style>
</head>
<body>
  <h2>Tambah Produk Baru</h2>
  <form method="post" enctype="multipart/form-data">
    <label>Nama Produk:</label>
    <input type="text" name="nama_produk" required>
    <label>Harga:</label>
    <input type="number" name="harga" required>
    <label>Stok:</label>
    <input type="number" name="stok" required>
    <label>Upload Gambar:</label>
    <input type="file" name="gambar" accept="image/*" required>
    <button type="submit" name="simpan">Simpan Produk</button>
  </form>
</body>
</html>

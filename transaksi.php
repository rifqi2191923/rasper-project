<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id=$id"));
}

if (isset($_POST['submit'])) {
  $nama = $_POST['nama'];
  $jumlah = $_POST['jumlah'];
  $id_produk = $_POST['id_produk'];
  mysqli_query($conn, "INSERT INTO transaksi (nama_pembeli, id_produk, jumlah) VALUES ('$nama', $id_produk, $jumlah)");
  mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah WHERE id=$id_produk");
  echo "<p>Transaksi berhasil! <a href='index.php'>Kembali</a></p>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Transaksi - Rasper</title></head>
<body>
  <h2>Form Pembelian Produk</h2>
  <form method="post">
    Nama Pembeli: <input type="text" name="nama" required><br><br>
    Produk: <b><?= $produk['nama_produk']; ?></b><br>
    Jumlah: <input type="number" name="jumlah" min="1" max="<?= $produk['stok']; ?>" required><br><br>
    <input type="hidden" name="id_produk" value="<?= $produk['id']; ?>">
    <button type="submit" name="submit">Beli</button>
  </form>
</body>
</html>

<?php
session_start();
include 'db_connect.php';

$cart = $_SESSION['cart'] ?? [];

echo "<h2>🛒 Keranjang Belanja</h2>";

if (count($cart) === 0) {
  echo "<p>Keranjang kosong.</p>";
  exit;
}

$ids = implode(',', $cart);
$result = mysqli_query($conn, "SELECT * FROM produk WHERE id IN ($ids)");

echo "<table border='1' cellpadding='10'>
<tr><th>Nama Produk</th><th>Harga</th><th>Stok</th></tr>";

$total = 0;
while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>
          <td>{$row['nama_produk']}</td>
          <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
          <td>{$row['stok']}</td>
        </tr>";
  $total += $row['harga'];
}
echo "</table>";
echo "<p><b>Total: Rp " . number_format($total, 0, ',', '.') . "</b></p>";
echo "<a href='index.php'>← Lanjut Belanja</a>";
?>

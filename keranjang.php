<?php
session_start();
include 'db_connect.php';

$cart = $_SESSION['cart'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja - Rasper Fashion</title>

  <!-- ✅ Tambah favicon -->
  <link rel="icon" type="image/png" href="img/logo.png">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7fa;
      color: #333;
      padding: 40px;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #222;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
      background: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    th {
      background: #1a1a1a;
      color: #fff;
      padding: 12px;
      font-weight: 600;
    }

    td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    tr:hover td {
      background-color: #eef5ff;
    }

    .total {
      text-align: right;
      font-size: 18px;
      font-weight: 600;
      margin-top: 15px;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      background: #0077cc;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      transition: 0.3s;
    }

    a:hover {
      background: #005fa3;
    }

    .empty {
      text-align: center;
      margin-top: 100px;
      font-size: 18px;
    }
  </style>
</head>
<body>

<h2>🛒 Keranjang Belanja</h2>

<?php
if (count($cart) === 0) {
  echo "<p class='empty'>Keranjang kosong.</p>";
  echo "<div style='text-align:center;'><a href='index.php'>← Kembali ke Toko</a></div>";
  exit;
}

$ids = implode(',', $cart);
$result = mysqli_query($conn, "SELECT * FROM produk WHERE id IN ($ids)");

echo "<table>
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

echo "<p class='total'>Total: Rp " . number_format($total, 0, ',', '.') . "</p>";
echo "<div style='text-align:center;'><a href='index.php'>← Lanjut Belanja</a></div>";
?>

</body>
</html>

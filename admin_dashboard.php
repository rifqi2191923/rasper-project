<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

include 'db_connect.php';


// Hitung ringkasan untuk dashboard
$prod_count = 0;
$trans_count = 0;
$total_sales = 0;

$r = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM produk");
if ($r) {
  $row = mysqli_fetch_assoc($r);
  $prod_count = (int) ($row['cnt'] ?? 0);
}

$r = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM transaksi");
if ($r) {
  $row = mysqli_fetch_assoc($r);
  $trans_count = (int) ($row['cnt'] ?? 0);
}

// Total penjualan dengan join produk (jumlah * harga)
$r = mysqli_query($conn, "SELECT SUM(t.jumlah * p.harga) AS total FROM transaksi t JOIN produk p ON t.id_produk = p.id");
if ($r) {
  $row = mysqli_fetch_assoc($r);
  $total_sales = (int) ($row['total'] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - Rasper Fashion</title>

  <!-- ✅ Tambah favicon -->
  <link rel="icon" type="image/png" href="img/logo.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Poppins', sans-serif;
    }

    /* ===== NAVBAR ===== */
    .navbar {
      background: linear-gradient(90deg, #1a1a1a, #2b2b2b);
      padding: 15px 50px;
      color: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .navbar-brand {
      font-weight: 600;
      font-size: 22px;
      letter-spacing: 0.5px;
    }
    .navbar img {
      height: 38px;
      margin-right: 10px;
      border-radius: 8px;
      background: white;
      padding: 3px;
    }

    /* ===== MAIN CONTENT ===== */
    .container {
      margin-top: 50px;
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    h2 {
      font-weight: 600;
      color: #222;
      margin-bottom: 30px;
    }

    .btn-tambah {
      background: #0077cc;
      color: white;
      font-weight: 600;
      border-radius: 8px;
      padding: 10px 18px;
      text-decoration: none;
      transition: 0.3s;
    }
    .btn-tambah:hover {
      background: #005fa3;
      text-decoration: none;
      color: white;
    }

    .logout {
      color: #ff6666;
      font-weight: 600;
      margin-left: 15px;
      text-decoration: none;
    }
    .logout:hover {
      color: #ff4444;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 25px;
      text-align: center;
    }

    th {
      background: #1a1a1a;
      color: white;
      padding: 12px;
      font-weight: 600;
    }

    td {
      background: #f9f9f9;
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }

    tr:hover td {
      background: #eef5ff;
    }

    footer {
      text-align: center;
      margin-top: 60px;
      padding: 20px;
      color: #777;
      font-size: 14px;
    }

    .navbar-brand {
      color: #fff !important;
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <img src="img/logo.png" alt="Logo Rasper">
      <span class="navbar-brand">Rasper Fashion Admin</span>
    </div>
    <div>
      <span>👤 <?= htmlspecialchars($_SESSION['admin']); ?></span>
      <a href="logout.php" class="logout">Logout</a>
    </div>
  </nav>

  <!-- MAIN CONTENT -->
  <div class="container">
    <!-- Ringkasan dashboard -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="p-3 bg-white rounded" style="border:1px solid #eee;">
          <h6 class="text-muted">Total Produk</h6>
          <h3 style="margin-top:8px;"><?= number_format($prod_count); ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 bg-white rounded" style="border:1px solid #eee;">
          <h6 class="text-muted">Total Transaksi</h6>
          <h3 style="margin-top:8px;"><?= number_format($trans_count); ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 bg-white rounded" style="border:1px solid #eee;">
          <h6 class="text-muted">Total Penjualan</h6>
          <h3 style="margin-top:8px;">Rp <?= number_format($total_sales, 0, ',', '.'); ?></h3>
        </div>
      </div>
    </div>

    <h2>📦 Data Produk</h2>
    <div class="mb-3">
      <a href="tambah_produk.php" class="btn-tambah">+ Tambah Produk</a>
      <a href="transaksi.php" class="btn btn-outline-secondary" style="margin-left:10px;">Lihat Transaksi</a>
    </div>

    <table>
      <tr>
        <th>ID</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Stok</th>
      </tr>

      <?php
      // Ambil produk dari database
      $result = mysqli_query($conn, "SELECT * FROM produk");
      $produk_list = [];

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $produk_list[] = $row;
        }
      }

      // Jika database kosong, tampilkan produk dummy (dari index.php)
      if (empty($produk_list)) {
        $produk_list = [
          ["id" => "-", "nama_produk" => "Kaos Polos Premium", "harga" => 95000, "stok" => 25],
          ["id" => "-", "nama_produk" => "Hoodie Oversize", "harga" => 185000, "stok" => 15],
          ["id" => "-", "nama_produk" => "Celana Jeans Slim Fit", "harga" => 210000, "stok" => 10],
          ["id" => "-", "nama_produk" => "Kemeja Flanel", "harga" => 165000, "stok" => 12]
        ];
      }

      // Tampilkan semua produk
      foreach ($produk_list as $p) {
        echo "<tr>
                <td>{$p['id']}</td>
                <td>{$p['nama_produk']}</td>
                <td>Rp " . number_format($p['harga'], 0, ',', '.') . "</td>
                <td>{$p['stok']}</td>
              </tr>";
      }
      ?>
    </table>
  </div>

  <footer>
    &copy; <?= date('Y'); ?> Rasper Project — Admin Dashboard
  </footer>

</body>
</html>

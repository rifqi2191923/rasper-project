<?php
include 'db_connect.php';
session_start();

// Inisialisasi keranjang
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Tambah ke keranjang
if (isset($_GET['add'])) {
  $id = $_GET['add'];
  if (!in_array($id, $_SESSION['cart'])) {
    $_SESSION['cart'][] = $id;
  }
  header("Location: index.php");
  exit;
}

// Pencarian produk
$search = "";
if (isset($_GET['cari'])) {
  $search = $_GET['cari'];
}
$query = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rasper Fashion Store</title>
  <style>
    body { background-color: #f3f4f6; color: #333; font-family: 'Poppins', sans-serif; margin: 0; }
    header {
      display: flex; justify-content: space-between; align-items: center;
      padding: 20px 40px; background: #222; color: white;
    }
    header h1 { font-size: 24px; }
    header a { color: #fff; text-decoration: none; font-weight: bold; }
    header a:hover { text-decoration: underline; }

    .search-bar { text-align: center; margin: 20px; }
    input[type='text'] {
      padding: 8px 12px; width: 300px; border-radius: 5px; border: 1px solid #ccc;
    }
    button {
      padding: 8px 15px; border: none; background: #0077cc; color: white;
      border-radius: 5px; cursor: pointer;
    }
    button:hover { background: #005fa3; }

    .produk-container {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 25px; padding: 20px 40px;
    }
    .produk-card {
      background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden; transition: all 0.3s ease; text-align: center;
    }
    .produk-card:hover { transform: translateY(-5px); }
    .produk-card img { width: 100%; height: 180px; object-fit: cover; }
    .produk-info { padding: 15px; }
    .produk-info h3 { font-size: 18px; color: #111; margin-bottom: 8px; }
    .harga { color: #0077cc; font-weight: bold; }
    footer { text-align: center; padding: 15px; color: #777; font-size: 13px; border-top: 1px solid #ddd; }
  </style>
</head>
<body>

<header>
  <h1>🛍️ Rasper Fashion Store</h1>
  <div>
    <a href="admin_login.php">Admin</a> | 
    <a href="keranjang.php">🛒 Keranjang (<?= count($_SESSION['cart']); ?>)</a>
  </div>
</header>

<div class="search-bar">
  <form method="get">
    <input type="text" name="cari" placeholder="Cari produk..." value="<?= htmlspecialchars($search); ?>">
    <button type="submit">Cari</button>
  </form>
</div>

<div class="produk-container">
<?php
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $img = $row['gambar'] ? "uploads/".$row['gambar'] : "uploads/default.jpg";
    echo "
    <div class='produk-card'>
      <img src='$img' alt='Produk'>
      <div class='produk-info'>
        <h3>{$row['nama_produk']}</h3>
        <p class='harga'>Rp " . number_format($row['harga'], 0, ',', '.') . "</p>
        <p>Stok: {$row['stok']}</p>
        <a href='?add={$row['id']}'><button>Tambah ke Keranjang</button></a>
      </div>
    </div>";
  }
} else {
  echo "<p style='grid-column:1 / -1; text-align:center;'>Tidak ada produk ditemukan.</p>";
}
?>
</div>

<footer>
  &copy; <?= date('Y'); ?> Rasper Project — All Rights Reserved.
</footer>

</body>
</html>

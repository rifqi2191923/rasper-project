<?php
include 'db_connect.php';
session_start();

// Cek koneksi database
if (!$conn) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}

// Jika user belum login → arahkan ke halaman login
if (!isset($_SESSION['username'])) {
  header("Location: user_login.php");
  exit;
}

// Tombol Logout ditekan
if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header("Location: user_login.php");
  exit;
}

// Inisialisasi keranjang
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Tambah ke keranjang
if (isset($_GET['add'])) {
  $id = (int) $_GET['add']; // pastikan integer
  if ($id > 0 && !in_array($id, $_SESSION['cart'])) {
    $_SESSION['cart'][] = $id;
  }
  header("Location: index.php");
  exit;
}

// Pencarian produk
$search = trim($_GET['cari'] ?? '');
$searchTerm = "%{$search}%";

// Query aman pakai prepared statement
$stmt = $conn->prepare("SELECT * FROM produk WHERE nama_produk LIKE ? LIMIT 20");
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rasper Fashion Store</title>
  <link rel="icon" type="image/png" href="img/logo.png">

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background-color: #f5f7fa;
      color: #333;
      font-family: 'Poppins', sans-serif;
      line-height: 1.6;
    }

    /* ===== HEADER ===== */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 18px 50px;
      background: linear-gradient(90deg, #1a1a1a, #2b2b2b);
      color: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    }
    .logo-area {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .logo-area img {
      height: 40px;
      width: auto;
      border-radius: 8px;
      background: white;
      padding: 4px;
      box-shadow: 0 0 6px rgba(255, 255, 255, 0.3);
    }
    header h1 {
      font-size: 22px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    header a {
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      margin-left: 15px;
      transition: color 0.3s ease;
    }
    header a:hover { color: #00bfff; }

    .user-nav {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    /* ===== SEARCH ===== */
    .search-bar {
      text-align: center;
      margin: 40px 0 25px;
    }
    .search-bar form {
      display: inline-flex;
      background: #fff;
      padding: 8px 15px;
      border-radius: 50px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    .search-bar input[type='text'] {
      padding: 10px 15px;
      width: 320px;
      border: none;
      outline: none;
      font-size: 15px;
      border-radius: 30px;
      color: #333;
    }
    .search-bar button {
      background: #0077cc;
      border: none;
      color: white;
      padding: 10px 25px;
      border-radius: 30px;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.3s ease, transform 0.2s ease;
    }
    .search-bar button:hover {
      background: #005fa3;
      transform: scale(1.05);
    }

    /* ===== PRODUK GRID ===== */
    .produk-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
      gap: 25px;
      padding: 20px 60px 60px;
    }

    /* ===== PRODUK CARD ===== */
    .produk-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .produk-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }
    .produk-card img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-bottom: 1px solid #eee;
    }
    .produk-info {
      padding: 18px 10px 25px;
    }
    .produk-info h3 {
      font-size: 17px;
      margin: 10px 0 5px;
      color: #111;
      font-weight: 600;
    }
    .harga {
      color: #0077cc;
      font-weight: 700;
      margin: 6px 0;
    }
    .stok {
      color: #666;
      font-size: 13px;
      margin-bottom: 12px;
    }
    .btn-keranjang {
      display: inline-block;
      background: #0077cc;
      color: #fff;
      text-decoration: none;
      padding: 9px 16px;
      border-radius: 6px;
      font-weight: 600;
      transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn-keranjang:hover {
      background: #005fa3;
      transform: scale(1.05);
    }

    /* ===== FOOTER ===== */
    footer {
      text-align: center;
      padding: 18px;
      font-size: 13px;
      color: #777;
      background: #fff;
      border-top: 1px solid #ddd;
      letter-spacing: 0.3px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
      header { flex-direction: column; gap: 10px; text-align: center; padding: 20px; }
      .search-bar input[type='text'] { width: 220px; }
      .produk-container { padding: 20px; }
    }
  </style>
</head>

<body>

<header>
  <div class="logo-area">
    <img src="img/logo.png" alt="Logo Rasper">
    <h1>Rasper Fashion Store</h1>
  </div>

  <div class="user-nav">
    <span>👤 <?= htmlspecialchars($_SESSION['username']); ?></span>
    <a href="keranjang.php">🛒 Keranjang (<?= count($_SESSION['cart']); ?>)</a>
    <a href="?logout=true" style="color: #ff6666;">Logout</a>
  </div>
</header>

<div class="search-bar">
  <form method="get">
    <input type="text" name="cari" placeholder="Cari produk fashion..." 
           value="<?= htmlspecialchars($search); ?>">
    <button type="submit">Cari</button>
  </form>
</div>

<div class="produk-container">
<?php
// Ambil produk dari database
$db_produk = [];
if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $db_produk[] = [
      "id" => $row['id'],
      "nama" => $row['nama_produk'],
      "harga" => $row['harga'],
      "stok" => $row['stok'],
      "gambar" => $row['gambar'] ? "uploads/".$row['gambar'] : "uploads/default.jpg"
    ];
  }
}

// Produk dummy tambahan (offline)
$dummy_produk = [
  ["nama" => "Kaos Polos Premium", "harga" => 95000, "stok" => 25, "gambar" => "img/kaos.jpg"],
  ["nama" => "Hoodie Oversize", "harga" => 185000, "stok" => 15, "gambar" => "img/hoodie.jpg"],
  ["nama" => "Celana Jeans Slim Fit", "harga" => 210000, "stok" => 10, "gambar" => "img/jeans.jpg"],
  ["nama" => "Kemeja Flanel", "harga" => 165000, "stok" => 12, "gambar" => "img/flanel.jpg"]
];

// Filter dummy sesuai pencarian
$filtered_dummy = array_filter($dummy_produk, function($p) use ($search) {
  return stripos($p['nama'], $search) !== false;
});

// Gabungkan semua produk
$all_produk = array_merge($db_produk, $filtered_dummy);

// Tampilkan produk
if (count($all_produk) > 0) {
  foreach ($all_produk as $p) {
    $img = $p['gambar'];
    $harga = number_format($p['harga'], 0, ',', '.');
    $stok = $p['stok'];
    $nama = htmlspecialchars($p['nama']);

    echo "
    <div class='produk-card'>
      <img src='$img' alt='$nama' onerror=\"this.src='img/default.jpg'\">
      <div class='produk-info'>
        <h3>$nama</h3>
        <p class='harga'>Rp $harga</p>
        <p class='stok'>Stok: $stok</p>";
    
    if (isset($p['id']) && $p['id'] > 0) {
      echo "<a href='?add=" . $p['id'] . "' class='btn-keranjang'>Tambah ke Keranjang</a>";
    }

    echo "</div></div>";
  }
} else {
  echo "<p style='grid-column:1 / -1; text-align:center;'>Produk tidak ditemukan.</p>";
}
?>
</div>

<div style="text-align:center; margin:40px;">
  <a href="keranjang.php" class="btn-keranjang" style="padding:12px 24px; font-size:16px;">
    Lihat Keranjang 🛒
  </a>
</div>

<footer>
  &copy; <?= date('Y'); ?> Rasper Project — All Rights Reserved.
</footer>

</body>
</html>

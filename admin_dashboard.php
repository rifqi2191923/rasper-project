<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>
    <h2>Selamat Datang, Admin!</h2>
    <a href="tambah_produk.php">+ Tambah Produk</a> | 
    <a href="logout.php">Logout</a>

    <h3>Data Produk</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM produk");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nama_produk']}</td>
                    <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                    <td>{$row['stok']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>

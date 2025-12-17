<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}
require_once ROOT_PATH . '/config/database.php';

$q = mysqli_query($conn, "SELECT * FROM data_barang ORDER BY id_barang DESC");
?>

<div class="page-container">
  <div class="card">

    <div class="header-row">
      <h2>📦 Data Barang</h2>
      <a href="index.php?page=tambah" class="btn">➕ Tambah Barang</a>
    </div>

    <table class="table">
      <tr>
        <th>ID</th>
        <th>Gambar</th>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
      </tr>

      <?php while ($row = mysqli_fetch_assoc($q)): ?>
      <tr>
        <td><?= $row['id_barang'] ?></td>

        <td>
          <?php
          $img = $row['gambar'] ?: 'no-image.png';
          ?>
          <img src="assets/img/<?= htmlspecialchars($img) ?>">
        </td>

        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['kategori']) ?></td>
        <td>Rp <?= number_format($row['harga_jual']) ?></td>
        <td><?= $row['stok'] ?></td>

        <td class="action">
          <a class="edit" href="index.php?page=ubah&id=<?= $row['id_barang'] ?>">✏️</a>
          <a class="delete"
             href="index.php?page=hapus&id=<?= $row['id_barang'] ?>"
             onclick="return confirm('Yakin hapus data?')">🗑️</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>

  </div>
</div>

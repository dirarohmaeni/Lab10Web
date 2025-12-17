<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}
require_once ROOT_PATH . '/config/database.php';

// CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf_token'];

$errors = [];

// ambil ID
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php?page=list');
    exit;
}

// ambil data lama
$stmt = mysqli_prepare($conn, "SELECT * FROM data_barang WHERE id_barang=?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: index.php?page=list');
    exit;
}

$old = $data;

// submit update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    }

    $old['nama'] = trim($_POST['nama'] ?? '');
    $old['kategori'] = trim($_POST['kategori'] ?? '');
    $old['harga_beli'] = (int)($_POST['harga_beli'] ?? 0);
    $old['harga_jual'] = (int)($_POST['harga_jual'] ?? 0);
    $old['stok'] = (int)($_POST['stok'] ?? 0);

    if ($old['nama'] === '') {
        $errors[] = 'Nama barang wajib diisi.';
    }

    // upload gambar baru (optional)
    $gambarName = $data['gambar'];
    if (!empty($_FILES['file_gambar']['name'])) {
        $f = $_FILES['file_gambar'];
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        $gambarName = uniqid('img_', true) . '.' . $ext;
        move_uploaded_file($f['tmp_name'], ROOT_PATH . '/assets/img/' . $gambarName);
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE data_barang SET
             nama=?, kategori=?, gambar=?, harga_beli=?, harga_jual=?, stok=?
             WHERE id_barang=?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            'sssiiii',
            $old['nama'],
            $old['kategori'],
            $gambarName,
            $old['harga_beli'],
            $old['harga_jual'],
            $old['stok'],
            $id
        );

        mysqli_stmt_execute($stmt);
        header('Location: index.php?page=list&msg=updated');
        exit;
    }
}
?>

<h2>Ubah Barang</h2>

<?php foreach ($errors as $e): ?>
  <p style="color:red"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">

  <label>Nama</label><br>
  <input name="nama" value="<?= htmlspecialchars($old['nama']) ?>"><br><br>

  <label>Kategori</label><br>
  <input name="kategori" value="<?= htmlspecialchars($old['kategori']) ?>"><br><br>

  <label>Harga Beli</label><br>
  <input type="number" name="harga_beli" value="<?= $old['harga_beli'] ?>"><br><br>

  <label>Harga Jual</label><br>
  <input type="number" name="harga_jual" value="<?= $old['harga_jual'] ?>"><br><br>

  <label>Stok</label><br>
  <input type="number" name="stok" value="<?= $old['stok'] ?>"><br><br>

  <label>Gambar</label><br>
  <?php if (!empty($old['gambar'])): ?>
    <img src="assets/img/<?= htmlspecialchars($old['gambar']) ?>" width="80"><br>
  <?php endif; ?>
  <input type="file" name="file_gambar"><br><br>

  <button type="submit">Update</button>
</form>

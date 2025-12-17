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
$old = [
    'nama' => '',
    'kategori' => '',
    'harga_beli' => '',
    'harga_jual' => '',
    'stok' => ''
];

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

    // upload gambar
    $gambarName = null;
    if (!empty($_FILES['file_gambar']['name'])) {
        $f = $_FILES['file_gambar'];
        $allowed = ['jpg','jpeg','png','gif'];

        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Gagal upload gambar.';
        } elseif ($f['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Ukuran gambar maksimal 2MB.';
        } else {
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $errors[] = 'Tipe gambar tidak valid.';
            } else {
                $gambarName = uniqid('img_', true) . '.' . $ext;
                $dest = ROOT_PATH . '/assets/img/' . $gambarName;
                if (!move_uploaded_file($f['tmp_name'], $dest)) {
                    $errors[] = 'Gagal menyimpan gambar.';
                }
            }
        }
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO data_barang 
            (nama, kategori, gambar, harga_beli, harga_jual, stok)
            VALUES (?,?,?,?,?,?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            'sssiii',
            $old['nama'],
            $old['kategori'],
            $gambarName,
            $old['harga_beli'],
            $old['harga_jual'],
            $old['stok']
        );

        if (mysqli_stmt_execute($stmt)) {
            header('Location: index.php?page=list&msg=added');
            exit;
        } else {
            $errors[] = 'Gagal menyimpan data.';
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<h2>Tambah Barang</h2>

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
  <input type="file" name="file_gambar"><br><br>

  <button type="submit">Simpan</button>
</form>

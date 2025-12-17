<?php
// views/modules/user/hapus.php (PDO)
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__, 3));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/DatabaseClass.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$cfg = require ROOT_PATH . '/config/config.php';
$db = new DatabaseClass($cfg);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=list&err=invalid_method'); exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) { header('Location: index.php?page=list&err=invalid_id'); exit; }

if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    header('Location: index.php?page=list&err=csrf'); exit;
}

// ambil nama gambar
$item = $db->fetch("SELECT gambar FROM data_barang WHERE id_barang = :id LIMIT 1", ['id' => $id]);
$gambar = $item['gambar'] ?? null;

// delete
try {
    $db->delete('data_barang', 'id_barang = :id', ['id' => $id]);
    if ($gambar) {
        $file = ROOT_PATH . '/assets/img/' . $gambar;
        if (file_exists($file)) @unlink($file);
    }
    header('Location: index.php?page=list&msg=deleted'); exit;
} catch (Exception $e) {
    header('Location: index.php?page=list&err=delete_failed'); exit;
}

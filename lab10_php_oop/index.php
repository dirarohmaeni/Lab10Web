<?php
// index.php – Router Final (modules first, views as fallback)
declare(strict_types=1);

// ---------------- Find ROOT ----------------
function find_root(): string {
    $p = __DIR__;
    for ($i = 0; $i < 6; $i++) {
        if (is_dir($p . '/config')) return $p;
        $p = dirname($p);
    }
    return __DIR__;
}
if (!defined('ROOT_PATH')) define('ROOT_PATH', find_root());

// ---------------- Load DB config ----------------
$usePDO = false;
$cfg = null;

if (file_exists(ROOT_PATH . '/config/config.php') &&
    file_exists(ROOT_PATH . '/config/DatabaseClass.php')) {

    require_once ROOT_PATH . '/config/config.php';
    require_once ROOT_PATH . '/config/DatabaseClass.php';
    $cfg = require ROOT_PATH . '/config/config.php';
    $usePDO = true;

} elseif (file_exists(ROOT_PATH . '/config/database.php')) {
    require_once ROOT_PATH . '/config/database.php';
} else {
    die("Config DB tidak ditemukan.");
}

// ---------------- Session / CSRF ----------------
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(16));

function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

// ---------------- Page Whitelist ----------------
$pages = [
    'list'   => 'modules/user/list.php',
    'tambah' => 'modules/user/tambah.php',
    'ubah'   => 'modules/user/ubah.php',
    'hapus'  => 'modules/user/hapus.php',

    'login'  => 'modules/auth/login.php',
    'logout' => 'modules/auth/logout.php',

    'dashboard' => 'views/dashboard.php',
];

$page = $_GET['page'] ?? 'list';

if (!array_key_exists($page, $pages)) {
    header('Location: index.php?page=list');
    exit;
}

if ($page === 'hapus' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=list&err=invalid_method');
    exit;
}

// ---------------- Resolve correct path ----------------
$modulePath = ROOT_PATH . '/' . $pages[$page];           
$viewsPath  = ROOT_PATH . '/views/' . $pages[$page];     

if (file_exists($modulePath)) {
    $target = $modulePath;
} elseif (file_exists($viewsPath)) {
    $target = $viewsPath;
} else {
    die("<h2>Halaman belum tersedia:</h2><pre>$modulePath</pre>");
}

// ---------------- Login Protection ----------------
$public = ['login'];
if (!is_logged_in() && !in_array($page, $public)) {
    header('Location: index.php?page=login');
    exit;
}

// ---------------- Init DB handler ----------------
if ($usePDO) {
    $db = new DatabaseClass($cfg);
} else {
    if (!isset($conn)) die("Error: mysqli conn tidak ditemukan.");
}

// ---------------- Include Layout (optional) ----------------
if (file_exists(ROOT_PATH . '/views/header.php')) include ROOT_PATH . '/views/header.php';

// ---------------- Load page ----------------
include $target;

// ---------------- Footer ----------------
if (file_exists(ROOT_PATH . '/views/footer.php')) include ROOT_PATH . '/views/footer.php';

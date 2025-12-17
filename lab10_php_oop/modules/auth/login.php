<?php
// modules/auth/login.php — Praktikum 10 (mysqli + password_hash)

if (session_status() === PHP_SESSION_NONE) session_start();

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

require_once ROOT_PATH . '/config/database.php';

$errors = [];
$username = '';

// jika sudah login
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php?page=list');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Isi username dan password.';
    } else {
        $sql = "SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            $errors[] = 'Query gagal.';
        } else {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: index.php?page=list');
                exit;
            } else {
                $errors[] = 'Username atau password salah.';
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!-- ================== TAMPILAN LOGIN ================== -->
<style>
.login-wrap { max-width:520px; margin:40px auto; }
.login-card {
  background:#fff; padding:26px; border-radius:12px;
  box-shadow:0 18px 40px rgba(0,0,0,.06);
}
.label { font-weight:700; color:#1f6d2f; margin-bottom:6px; display:block; }
.input-lg {
  width:100%; padding:12px; border-radius:10px;
  border:1px solid #e6f3e6;
}
.btn-save {
  margin-top:14px;
  background:#28a745; color:#fff;
  padding:10px 20px; border:none;
  border-radius:10px;
}
.error { color:red; margin-top:8px; }
</style>

<div class="login-wrap">
  <div class="login-card">
    <h3>Login</h3>

    <?php foreach ($errors as $e): ?>
      <div class="error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="post" action="index.php?page=login">
      <label class="label">Username</label>
      <input name="username" class="input-lg"
             value="<?= htmlspecialchars($username) ?>">

      <label class="label">Password</label>
      <input type="password" name="password" class="input-lg">

      <button class="btn-save">Login</button>
    </form>
  </div>
</div>

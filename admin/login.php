<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
if (!empty($_SESSION['admin_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) $error = 'Sesi habis. Muat ulang halaman.';
    else {
        $st = $pdo->prepare('SELECT id, username, password_hash FROM admin WHERE username = ?');
        $st->execute([trim($_POST['username'] ?? '')]);
        $a = $st->fetch();
        if ($a && password_verify($_POST['password'] ?? '', $a['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$a['id']; $_SESSION['admin_username'] = $a['username'];
            header('Location: dashboard.php'); exit;
        }
        sleep(1); $error = 'Username atau kata sandi salah.';
    }
}
$pageTitle = 'Panel Admin | Nia\'s Bucket'; $hideNav = true; $bodyClass = 'admin-login'; $assetBase = '../';
require __DIR__ . '/../includes/header.php';
?>
<main class="admin-box">
  <div class="admin-card">
    <p class="badge-admin">Khusus pengelola toko</p>
    <h1>Panel admin</h1>
    <p class="text-muted small">Pelanggan? Masuk lewat <a href="../masuk.php">halaman pelanggan</a>.</p>
    <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <label class="form-label" for="username">Username</label><input class="form-control mb-3" id="username" name="username" required autofocus>
      <label class="form-label" for="password">Kata sandi</label><input class="form-control mb-4" type="password" id="password" name="password" required>
      <button class="btn btn-dark btn-lg w-100">Masuk ke dashboard</button>
    </form>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

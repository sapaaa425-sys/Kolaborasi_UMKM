<?php
require_once __DIR__ . '/../includes/auth.php'; require_admin();
require_once __DIR__ . '/../config/database.php';
$msg = ''; $ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $st = $pdo->prepare('SELECT password_hash FROM admin WHERE id = ?'); $st->execute([$_SESSION['admin_id']]);
    $hash = $st->fetchColumn(); $new = $_POST['baru'] ?? '';
    if (!csrf_ok()) $msg = 'Sesi habis. Muat ulang halaman.';
    elseif (!$hash || !password_verify($_POST['lama'] ?? '', $hash)) $msg = 'Kata sandi lama salah.';
    elseif (strlen($new) < 8) $msg = 'Kata sandi baru minimal 8 karakter.';
    elseif ($new !== ($_POST['ulang'] ?? '')) $msg = 'Pengulangan kata sandi tidak sama.';
    else {
        $pdo->prepare('UPDATE admin SET password_hash = ? WHERE id = ?')->execute([password_hash($new, PASSWORD_DEFAULT), $_SESSION['admin_id']]);
        $ok = true; $msg = 'Kata sandi berhasil diganti.';
    }
}
$pageTitle = 'Ganti sandi | Admin Nia\'s Bucket'; $hideNav = true; $bodyClass = 'admin-area'; $assetBase = '../';
require __DIR__ . '/../includes/header.php'; require __DIR__ . '/../includes/admin_bar.php';
?>
<main class="admin-main" style="max-width:520px">
  <h1>Ganti sandi</h1>
  <?php if ($msg): ?><div class="alert alert-<?= $ok ? 'success' : 'danger' ?>"><?= e($msg) ?></div><?php endif; ?>
  <form method="post" class="panel p-4"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label class="form-label">Kata sandi lama</label><input class="form-control mb-3" type="password" name="lama" required>
    <label class="form-label">Kata sandi baru</label><input class="form-control mb-3" type="password" name="baru" minlength="8" required>
    <label class="form-label">Ulangi kata sandi baru</label><input class="form-control mb-4" type="password" name="ulang" minlength="8" required>
    <button class="btn btn-dark">Simpan</button>
  </form>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

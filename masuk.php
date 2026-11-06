<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
$mode = ($_GET['mode'] ?? '') === 'daftar' ? 'daftar' : 'masuk';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = ($_POST['aksi'] ?? '') === 'daftar' ? 'daftar' : 'masuk';
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pass = $_POST['password'] ?? '';
    if (!csrf_ok()) { $error = 'Sesi habis. Muat ulang halaman lalu coba lagi.'; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $error = 'Format email belum benar.'; }
    elseif ($mode === 'daftar') {
        $nama = trim($_POST['nama'] ?? ''); $tel = trim($_POST['telepon'] ?? '');
        if ($nama === '' || $tel === '') $error = 'Nama dan nomor WhatsApp wajib diisi.';
        elseif (strlen($pass) < 8) $error = 'Kata sandi minimal 8 karakter.';
        else {
            $cek = $pdo->prepare('SELECT id FROM customers WHERE email = ?'); $cek->execute([$email]);
            if ($cek->fetch()) $error = 'Email ini sudah terdaftar. Silakan masuk.';
            else {
                $pdo->prepare('INSERT INTO customers (nama,email,telepon,password_hash) VALUES (?,?,?,?)')
                    ->execute([$nama, $email, $tel, password_hash($pass, PASSWORD_DEFAULT)]);
                $id = (int)$pdo->lastInsertId();
            }
        }
    } else {
        $st = $pdo->prepare('SELECT id,nama,password_hash FROM customers WHERE email = ?'); $st->execute([$email]);
        $u = $st->fetch();
        if (!$u || !password_verify($pass, $u['password_hash'])) $error = 'Email atau kata sandi salah.';
        else { $id = (int)$u['id']; $nama = $u['nama']; }
    }
    if ($error === '') {
        session_regenerate_id(true);
        $_SESSION['pelanggan_id'] = $id; $_SESSION['pelanggan_nama'] = $nama;
        header('Location: pembelian.php'); exit;
    }
}
$pageTitle = 'Masuk | Nia\'s Bucket'; $hideNav = true; $bodyClass = 'auth-page';
require __DIR__ . '/includes/header.php';
?>
<main class="auth">
  <aside class="auth-art"><a class="brand" href="index.php">Nia's Bucket</a>
    <img src="assets/img/produk/buket.jpeg" alt="">
    <p>Simpan data pesananmu, pesan ulang buket favorit dalam sekali klik.</p></aside>
  <section class="auth-form">
    <div class="tabs-lite">
      <a href="masuk.php" class="<?= $mode === 'masuk' ? 'on' : '' ?>">Masuk</a>
      <a href="masuk.php?mode=daftar" class="<?= $mode === 'daftar' ? 'on' : '' ?>">Daftar</a>
    </div>
    <h1><?= $mode === 'daftar' ? 'Buat akun pelanggan' : 'Selamat datang kembali' ?></h1>
    <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
    <form method="post" autocomplete="on">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="aksi" value="<?= $mode ?>">
      <?php if ($mode === 'daftar'): ?>
        <label class="form-label" for="nama">Nama lengkap</label><input class="form-control mb-3" id="nama" name="nama" required maxlength="100" value="<?= e($_POST['nama'] ?? '') ?>">
        <label class="form-label" for="telepon">Nomor WhatsApp</label><input class="form-control mb-3" id="telepon" name="telepon" required maxlength="25" placeholder="08xxxxxxxxxx" value="<?= e($_POST['telepon'] ?? '') ?>">
      <?php endif; ?>
      <label class="form-label" for="email">Email</label><input class="form-control mb-3" type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
      <label class="form-label" for="password">Kata sandi</label><input class="form-control mb-4" type="password" id="password" name="password" required <?= $mode === 'daftar' ? 'minlength="8"' : '' ?>>
      <button class="btn btn-pink btn-lg w-100"><?= $mode === 'daftar' ? 'Buat akun' : 'Masuk' ?></button>
    </form>
    <p class="small mt-4 mb-0"><a href="index.php">Kembali ke toko</a></p>
  </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>

<?php require_once __DIR__ . '/auth.php'; require_once __DIR__ . '/bloom.php'; $hideNav = $hideNav ?? false; ?>
<!DOCTYPE html>
<html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle ?? 'Nia\'s Bucket') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Marmelad&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= isset($assetBase) ? $assetBase : '' ?>assets/css/style.css" rel="stylesheet">
</head><body class="<?= e($bodyClass ?? '') ?>">
<?php if (!$hideNav): ?>
<nav class="site-nav"><div class="container d-flex align-items-center justify-content-between">
  <a class="brand" href="index.php">Nia's Bucket</a>
  <div class="nav-links">
    <a href="index.php#beranda">Beranda</a><a href="index.php#produk">Koleksi</a><a href="index.php#tentang">Tentang</a><a href="index.php#kontak">Kontak</a>
    <?php if (!empty($_SESSION['pelanggan_id'])): ?>
      <span class="who">Hai, <?= e(explode(' ', $_SESSION['pelanggan_nama'])[0]) ?></span><a href="pesanan_saya.php">Pesanan saya</a><a href="keluar.php">Keluar</a>
    <?php else: ?><a href="masuk.php">Masuk</a><?php endif; ?>
    <a class="btn btn-pink btn-sm" href="pembelian.php">Pesan buket</a>
  </div>
</div></nav>
<?php endif; ?>

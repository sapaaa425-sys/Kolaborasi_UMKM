<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
if (empty($_SESSION['pelanggan_id'])) { header('Location: masuk.php'); exit; }
$st = $pdo->prepare('SELECT ps.*, pr.nama AS nama_produk FROM purchases ps JOIN bouquets pr ON pr.id=ps.produk_id WHERE ps.pelanggan_id=? ORDER BY ps.created_at DESC');
$st->execute([$_SESSION['pelanggan_id']]); $orders = $st->fetchAll();
$pageTitle = 'Pesanan saya | Nia\'s Bucket';
require __DIR__ . '/includes/header.php';
?>
<main class="form-page section-space"><div class="container" style="max-width:720px">
  <h1 class="mb-4">Pesanan saya</h1>
  <?php foreach ($orders as $o): ?>
  <div class="history-row">
    <div><strong><?= e($o['nama_produk']) ?> × <?= (int)$o['jumlah'] ?></strong>
      <div class="small text-muted"><?= e(date('d M Y', strtotime($o['created_at']))) ?> · Rp <?= number_format((float)$o['total_harga'], 0, ',', '.') ?></div></div>
    <span class="pill s-<?= strtolower($o['status']) ?>"><?= e($o['status']) ?></span>
  </div>
  <?php endforeach; ?>
  <?php if (!$orders): ?><p class="text-muted">Belum ada pesanan. <a href="index.php#produk">Pilih buket pertamamu</a>.</p><?php endif; ?>
</div></main>
<?php require __DIR__ . '/includes/footer.php'; ?>

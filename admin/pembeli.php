<?php
require_once __DIR__ . '/../includes/auth.php'; require_admin();
require_once __DIR__ . '/../config/database.php';
$q = trim($_GET['q'] ?? '');
$sql = "SELECT MAX(ps.nama_pelanggan) AS nama, ps.telepon, COUNT(*) AS jml, SUM(ps.total_harga) AS total, MAX(ps.created_at) AS terakhir,
        MAX(ps.pelanggan_id IS NOT NULL) AS punya_akun, GROUP_CONCAT(DISTINCT pr.nama ORDER BY pr.nama SEPARATOR ', ') AS produk
        FROM purchases ps JOIN bouquets pr ON pr.id = ps.produk_id WHERE ps.status <> 'Dibatalkan'";
$args = [];
if ($q !== '') { $sql .= ' AND (ps.nama_pelanggan LIKE ? OR ps.telepon LIKE ?)'; $args = ["%$q%", "%$q%"]; }
$st = $pdo->prepare($sql . ' GROUP BY ps.telepon ORDER BY terakhir DESC'); $st->execute($args);
$rows = $st->fetchAll();
$pageTitle = 'Pembeli | Admin Nia\'s Bucket'; $hideNav = true; $bodyClass = 'admin-area'; $assetBase = '../';
require __DIR__ . '/../includes/header.php'; require __DIR__ . '/../includes/admin_bar.php';
?>
<main class="admin-main">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <h1 class="mb-0">Pembeli <span class="count-chip"><?= count($rows) ?></span></h1>
    <form class="d-flex gap-2" method="get"><input class="form-control" name="q" placeholder="Cari nama atau nomor" value="<?= e($q) ?>"><button class="btn btn-dark">Cari</button></form>
  </div>
  <div class="panel table-responsive"><table class="table align-middle mb-0">
    <thead><tr><th>Pembeli</th><th>Membeli</th><th>Pesanan</th><th>Total belanja</th><th>Terakhir</th></tr></thead><tbody>
    <?php foreach ($rows as $r): ?><tr>
      <td><strong><?= e($r['nama']) ?></strong> <?php if ($r['punya_akun']): ?><span class="pill s-diproses">Akun</span><?php endif; ?>
        <div class="small"><a href="<?= e(wa_link($r['telepon'])) ?>" target="_blank" rel="noopener"><?= e($r['telepon']) ?></a></div></td>
      <td class="small"><?= e($r['produk']) ?></td>
      <td><?= (int)$r['jml'] ?>×</td>
      <td>Rp <?= number_format((float)$r['total'], 0, ',', '.') ?></td>
      <td class="small"><?= e(date('d M Y', strtotime($r['terakhir']))) ?></td>
    </tr><?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="5" class="text-center py-5 text-muted">Belum ada pembeli<?= $q !== '' ? ' yang cocok dengan pencarian' : '' ?>.</td></tr><?php endif; ?>
  </tbody></table></div>
  <p class="small text-muted mt-3">Pembeli dikelompokkan berdasarkan nomor WhatsApp. Pesanan yang dibatalkan tidak dihitung.</p>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

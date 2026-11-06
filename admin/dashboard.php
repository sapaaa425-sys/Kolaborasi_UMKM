<?php
require_once __DIR__ . '/../includes/auth.php'; require_admin();
require_once __DIR__ . '/../config/database.php';
$q = fn($sql) => $pdo->query($sql)->fetchColumn();
$baru = (int)$q("SELECT COUNT(*) FROM purchases WHERE status='Menunggu'");
$proses = (int)$q("SELECT COUNT(*) FROM purchases WHERE status='Diproses'");
$total = (int)$q("SELECT COUNT(*) FROM purchases");
$produkTotal = (int)$q("SELECT COUNT(*) FROM bouquets");
$produkAktif = (int)$q("SELECT COUNT(*) FROM bouquets WHERE aktif=1");
$pembeli = (int)$q("SELECT COUNT(DISTINCT telepon) FROM purchases WHERE status<>'Dibatalkan'");
$omzet = (float)$q("SELECT COALESCE(SUM(total_harga),0) FROM purchases WHERE status='Selesai'");
$orders = $pdo->query("SELECT ps.*, pr.nama AS nama_produk FROM purchases ps JOIN bouquets pr ON pr.id=ps.produk_id ORDER BY (ps.status='Menunggu') DESC, ps.created_at DESC LIMIT 50")->fetchAll();
$pageTitle = 'Pesanan | Admin Nia\'s Bucket'; $hideNav = true; $bodyClass = 'admin-area'; $assetBase = '../';
require __DIR__ . '/../includes/header.php'; require __DIR__ . '/../includes/admin_bar.php';
?>
<main class="admin-main">
  <h1>Pesanan</h1>
  <div class="stat-row">
    <div><strong><?= $baru ?></strong><span>perlu dikonfirmasi</span></div>
    <div><strong><?= $proses ?></strong><span>sedang dirangkai</span></div>
    <div><strong><?= $total ?></strong><span>total pesanan</span></div>
    <div><strong><a class="stat-link" href="produk.php"><?= $produkTotal ?></a></strong><span>produk (<?= $produkAktif ?> tampil di toko)</span></div>
    <div><strong><a class="stat-link" href="pembeli.php"><?= $pembeli ?></a></strong><span>pembeli</span></div>
    <div><strong>Rp <?= number_format($omzet, 0, ',', '.') ?></strong><span>pendapatan selesai</span></div>
  </div>
  <div class="panel table-responsive"><table class="table align-middle mb-0">
    <thead><tr><th>#</th><th>Pelanggan</th><th>Pesanan</th><th>Dibutuhkan</th><th>Status</th></tr></thead><tbody>
    <?php foreach ($orders as $o): ?><tr>
      <td>#<?= (int)$o['id'] ?></td>
      <td><strong><?= e($o['nama_pelanggan']) ?></strong><div class="small"><a href="<?= e(wa_link($o['telepon'])) ?>" target="_blank" rel="noopener"><?= e($o['telepon']) ?></a></div></td>
      <td><?= e($o['nama_produk']) ?> × <?= (int)$o['jumlah'] ?><div class="small text-muted">Rp <?= number_format((float)$o['total_harga'], 0, ',', '.') ?> · <?= e($o['alamat']) ?></div>
        <?php if ($o['catatan']): ?><div class="small fst-italic">“<?= e($o['catatan']) ?>”</div><?php endif; ?></td>
      <td><?= e($o['tanggal_pengambilan'] ?: 'Fleksibel') ?></td>
      <td><form method="post" action="ubah_status.php" class="d-flex gap-1">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
        <select name="status" class="form-select form-select-sm s-<?= strtolower($o['status']) ?>"><?php foreach (['Menunggu','Diproses','Selesai','Dibatalkan'] as $s): ?><option <?= $o['status'] === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select>
        <button class="btn btn-sm btn-dark">Simpan</button></form></td>
    </tr><?php endforeach; ?>
    <?php if (!$orders): ?><tr><td colspan="5" class="text-center py-5 text-muted">Belum ada pesanan. Bagikan link toko untuk mulai menerima pesanan.</td></tr><?php endif; ?>
  </tbody></table></div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Pesan buket | Nia\'s Bucket';
$products = $pdo->query("SELECT id, nama, harga FROM bouquets WHERE aktif = 1 ORDER BY nama")->fetchAll();
$selected = filter_input(INPUT_GET, 'produk', FILTER_VALIDATE_INT) ?: '';
$me = ['nama' => '', 'telepon' => ''];
if (!empty($_SESSION['pelanggan_id'])) {
    $st = $pdo->prepare('SELECT nama, telepon FROM customers WHERE id=?'); $st->execute([$_SESSION['pelanggan_id']]);
    $me = $st->fetch() ?: $me;
}
require __DIR__ . '/includes/header.php';
?>
<main class="form-page section-space"><div class="container"><div class="form-shell mx-auto">
  <a href="index.php#produk" class="small text-decoration-none">← Kembali ke koleksi</a>
  <h1 class="mt-3 mb-2">Pesan buketmu</h1>
  <?php if (empty($_SESSION['pelanggan_id'])): ?>
    <p class="text-muted mb-4">Punya akun? <a href="masuk.php">Masuk</a> agar data terisi otomatis dan statusnya bisa kamu pantau. Tanpa akun juga bisa.</p>
  <?php else: ?><p class="text-muted mb-4">Data dari akunmu sudah terisi. Tinggal pilih buketnya.</p><?php endif; ?>
  <?php if (($_GET['status'] ?? '') === 'ok'): ?><div class="alert alert-success">Pesanan terkirim. Kami akan menghubungi lewat WhatsApp untuk konfirmasi.<?php if (!empty($_SESSION['pelanggan_id'])): ?> <a href="pesanan_saya.php">Lihat pesanan saya</a><?php endif; ?></div><?php endif; ?>
  <form action="simpan_pembelian.php" method="post" id="orderForm">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div class="mb-3"><label class="form-label" for="nama_pelanggan">Nama lengkap</label><input class="form-control" id="nama_pelanggan" name="nama_pelanggan" required maxlength="100" value="<?= e($me['nama']) ?>"></div>
    <div class="mb-3"><label class="form-label" for="telepon">Nomor WhatsApp</label><input class="form-control" id="telepon" name="telepon" required maxlength="25" placeholder="08xxxxxxxxxx" value="<?= e($me['telepon']) ?>"></div>
    <div class="mb-3"><label class="form-label" for="produk_id">Pilih buket</label><select class="form-select" id="produk_id" name="produk_id" required><option value="">Pilih buket</option><?php foreach ($products as $p): ?><option value="<?= (int)$p['id'] ?>" <?= (string)$selected === (string)$p['id'] ? 'selected' : '' ?>><?= e($p['nama']) ?> — Rp <?= number_format((float)$p['harga'], 0, ',', '.') ?></option><?php endforeach; ?></select></div>
    <div class="row"><div class="col-sm-5 mb-3"><label class="form-label" for="jumlah">Jumlah</label><input class="form-control" type="number" min="1" max="50" value="1" id="jumlah" name="jumlah" required></div>
    <div class="col-sm-7 mb-3"><label class="form-label" for="tanggal_pengambilan">Tanggal dibutuhkan</label><input class="form-control" type="date" id="tanggal_pengambilan" name="tanggal_pengambilan" min="<?= date('Y-m-d') ?>"></div></div>
    <div class="mb-3"><label class="form-label" for="alamat">Alamat atau lokasi pengambilan</label><textarea class="form-control" id="alamat" name="alamat" rows="3" required maxlength="500"></textarea></div>
    <div class="mb-4"><label class="form-label" for="catatan">Catatan untuk kartu ucapan atau permintaan khusus</label><textarea class="form-control" id="catatan" name="catatan" rows="3" maxlength="1000" placeholder="Warna, isi ucapan, atau hal lain"></textarea></div>
    <button class="btn btn-pink btn-lg w-100" type="submit">Kirim pesanan</button>
  </form>
</div></div></main>
<?php require __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/../includes/auth.php'; require_admin();
require_once __DIR__ . '/../config/database.php';
$error = '';
$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT) ?: 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
    $editId = $id;
    $nama = trim($_POST['nama'] ?? ''); $desk = trim($_POST['deskripsi'] ?? '');
    $harga = filter_input(INPUT_POST, 'harga', FILTER_VALIDATE_FLOAT); $gambar = trim($_POST['gambar'] ?? '');
    if (!csrf_ok()) $error = 'Sesi habis. Muat ulang halaman.';
    elseif ($nama === '' || $harga === false || $harga === null || $harga < 0) $error = 'Nama wajib diisi dan harga harus angka 0 atau lebih.';
    else {
        $up = upload_gambar($_FILES['foto'] ?? null);
        if ($up === false) $error = 'Foto harus berformat JPG, PNG, atau WebP dengan ukuran maksimal 2 MB.';
        else {
            $old = null;
            if ($id) { $o = $pdo->prepare('SELECT gambar FROM bouquets WHERE id=?'); $o->execute([$id]); $old = $o->fetchColumn() ?: null; }
            $final = $up ?: (isset($_POST['hapus_gambar']) ? null : ($gambar ?: null));
            if ($id) {
                $pdo->prepare('UPDATE bouquets SET nama=?, deskripsi=?, harga=?, gambar=? WHERE id=?')->execute([$nama, $desk ?: null, $harga, $final, $id]);
                if ($old !== $final) hapus_gambar_lokal($old);
                header('Location: produk.php?updated=1'); exit;
            }
            $pdo->prepare('INSERT INTO bouquets (nama, deskripsi, harga, gambar, aktif) VALUES (?,?,?,?,1)')->execute([$nama, $desk ?: null, $harga, $final]);
            header('Location: produk.php?added=1'); exit;
        }
    }
}
$products = $pdo->query('SELECT * FROM bouquets ORDER BY id DESC')->fetchAll();
$f = ['id' => 0, 'nama' => '', 'deskripsi' => '', 'harga' => '', 'gambar' => ''];
if ($error) { $f = ['id' => $editId, 'nama' => $_POST['nama'] ?? '', 'deskripsi' => $_POST['deskripsi'] ?? '', 'harga' => $_POST['harga'] ?? '', 'gambar' => $_POST['gambar'] ?? '']; }
elseif ($editId) { foreach ($products as $p) if ((int)$p['id'] === $editId) $f = $p; }
$editing = (int)$f['id'] > 0;
$pageTitle = 'Produk | Admin Nia\'s Bucket'; $hideNav = true; $bodyClass = 'admin-area'; $assetBase = '../';
require __DIR__ . '/../includes/header.php'; require __DIR__ . '/../includes/admin_bar.php';
?>
<main class="admin-main">
  <h1>Produk <span class="count-chip"><?= count($products) ?></span></h1>
  <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
  <?php if (isset($_GET['added'])): ?><div class="alert alert-success">Produk ditambahkan dan langsung tampil di toko.</div><?php endif; ?>
  <?php if (isset($_GET['updated'])): ?><div class="alert alert-success">Perubahan produk disimpan.</div><?php endif; ?>
  <?php if (isset($_GET['deleted'])): ?><div class="alert alert-success">Produk dihapus.</div><?php endif; ?>
  <?php if (($_GET['err'] ?? '') === 'terpakai'): ?><div class="alert alert-warning">Produk ini sudah pernah dipesan, jadi tidak bisa dihapus agar riwayat pesanan tetap utuh. Gunakan tombol <strong>Sembunyikan</strong> supaya tidak tampil di toko.</div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="panel p-4 mb-4" id="formProduk"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
    <h2 class="h5 mb-3"><?= $editing ? 'Edit buket' : 'Tambah buket baru' ?></h2>
    <div class="row g-3">
      <div class="col-md-7"><label class="form-label">Nama buket</label><input class="form-control" name="nama" required maxlength="120" value="<?= e($f['nama']) ?>"></div>
      <div class="col-md-5"><label class="form-label">Harga (Rp)</label><input class="form-control" name="harga" type="number" min="0" step="1000" required value="<?= e($f['harga'] !== '' ? (int)$f['harga'] : '') ?>"></div>
      <div class="col-12"><label class="form-label">Deskripsi singkat</label><textarea class="form-control" name="deskripsi" rows="2"><?= e($f['deskripsi']) ?></textarea></div>
      <div class="col-12"><label class="form-label"><?= $editing ? 'Ganti foto produk' : 'Foto produk' ?> (JPG, PNG, WebP, maks. 2 MB)</label>
        <div class="d-flex gap-3 align-items-center"><?php if ($f['gambar']): ?><img class="thumb" src="<?= e(img_src($f['gambar'], '../')) ?>" alt=""><?php endif; ?>
        <div class="flex-grow-1"><input class="form-control" type="file" name="foto" accept="image/jpeg,image/png,image/webp">
        <?php if ($editing && $f['gambar']): ?><label class="small mt-2 d-block"><input type="checkbox" name="hapus_gambar"> Hapus foto (toko memakai ilustrasi)</label><?php endif; ?></div></div></div>
      <div class="col-12"><label class="form-label">Atau alamat gambar dari internet (opsional)</label><input class="form-control" name="gambar" placeholder="assets/img/buket-pastel.jpg" value="<?= e($f['gambar']) ?>"></div>
      <div class="col-12 d-flex gap-2"><button class="btn btn-dark"><?= $editing ? 'Simpan perubahan' : 'Simpan buket' ?></button><?php if ($editing): ?><a class="btn btn-outline-dark" href="produk.php">Batal</a><?php endif; ?></div>
    </div>
  </form>
  <div class="panel table-responsive"><table class="table align-middle mb-0">
    <thead><tr><th>Buket</th><th>Harga</th><th>Tampil di toko</th><th class="text-end">Aksi</th></tr></thead><tbody>
    <?php foreach ($products as $p): ?><tr<?= (int)$p['id'] === $editId ? ' class="row-edit"' : '' ?>>
      <td><div class="d-flex gap-3 align-items-center"><?php if ($p['gambar']): ?><img class="thumb" src="<?= e(img_src($p['gambar'], '../')) ?>" alt=""><?php else: ?><span class="thumb thumb-empty"></span><?php endif; ?><div><strong><?= e($p['nama']) ?></strong><div class="small text-muted"><?= e($p['deskripsi'] ?? '') ?></div></div></div></td>
      <td>Rp <?= number_format((float)$p['harga'], 0, ',', '.') ?></td>
      <td><span class="pill <?= $p['aktif'] ? 's-selesai' : 's-dibatalkan' ?>"><?= $p['aktif'] ? 'Tampil' : 'Disembunyikan' ?></span></td>
      <td class="text-end"><div class="d-inline-flex gap-1">
        <a class="btn btn-sm btn-dark" href="produk.php?edit=<?= (int)$p['id'] ?>#formProduk">Edit</a>
        <form method="post" action="toggle_produk.php"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="btn btn-sm btn-outline-dark"><?= $p['aktif'] ? 'Sembunyikan' : 'Tampilkan' ?></button></form>
        <form method="post" action="hapus_produk.php" onsubmit="return confirm('Hapus produk &quot;<?= e(addslashes($p['nama'])) ?>&quot;? Tindakan ini tidak bisa dibatalkan.')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="btn btn-sm btn-outline-danger">Hapus</button></form>
      </div></td>
    </tr><?php endforeach; ?>
    <?php if (!$products): ?><tr><td colspan="4" class="text-center py-5 text-muted">Belum ada produk. Tambahkan buket pertama lewat formulir di atas.</td></tr><?php endif; ?>
  </tbody></table></div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

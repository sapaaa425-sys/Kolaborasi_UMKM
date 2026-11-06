<?php $cur = basename($_SERVER['SCRIPT_NAME']); $nav = ['dashboard.php' => 'Ringkasan & pesanan', 'produk.php' => 'Produk', 'pembeli.php' => 'Pembeli', 'password.php' => 'Ganti sandi']; ?>
<aside class="admin-side">
  <div class="side-brand">Nia's<small>Panel admin</small></div>
  <nav><?php foreach ($nav as $href => $label): ?><a href="<?= $href ?>" class="<?= $cur === $href ? 'on' : '' ?>"><?= $label ?></a><?php endforeach; ?>
    <a href="../index.php" target="_blank" rel="noopener">Lihat toko</a></nav>
  <div class="side-foot"><?= e($_SESSION['admin_username'] ?? '') ?><a href="logout.php">Keluar</a></div>
</aside>

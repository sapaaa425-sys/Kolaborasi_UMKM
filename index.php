<?php
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Nia\'s Bucket | Buket Segar untuk Momen Spesial';
$products = $pdo->query("SELECT id, nama, deskripsi, harga, gambar FROM bouquets WHERE aktif = 1 ORDER BY id DESC")->fetchAll();
require __DIR__ . '/includes/header.php';
$pic = fn($p) => !empty($p['gambar']) ? '<img src="' . e($p['gambar']) . '" alt="' . e($p['nama']) . '" loading="lazy">' : bloom_svg((int)$p['id']);
?>
<main>
<section id="beranda" class="hero"><div class="container"><div class="row align-items-center g-5">
  <div class="col-lg-6">
    <span class="eyebrow">Nia's Bucket</span>
    <h1>Bouquet cantik untuk yang tersayang.</h1>
    <p class="lead mb-4">Dirangkai sesuai pesanan, lengkap dengan kartu ucapan tulisanmu sendiri. Untuk wisuda, ulang tahun, atau sekadar bilang terima kasih.</p>
    <a class="btn btn-pink me-2" href="#produk">Lihat koleksi</a>
    <a class="btn btn-outline-dark" href="pembelian.php">Pesan sekarang</a>

  </div>
  <div class="col-lg-6"><div class="mosaic">
    <?php foreach (array_slice($products, 0, 4) as $p): ?><figure><?= $pic($p) ?></figure><?php endforeach; ?>
  </div></div>
</div></div></section>

<div class="strip"><div class="container"><span>Bunga segar setiap hari</span><span>Dirangkai sesuai pesanan</span><span>Kartu ucapan gratis</span></div></div>

<section id="produk" class="section-space"><div class="container">
  <div class="section-heading"><h2>Koleksi buket</h2><p>Pilih yang paling cocok, lalu tekan Pesan. Pesan satu hari sebelumnya ya.</p></div>
  <div class="row g-4">
    <?php if (!$products): ?><div class="col-12"><p>Koleksi belum tersedia. Tambahkan buket lewat dashboard admin.</p></div><?php endif; ?>
    <?php foreach ($products as $p): ?>
    <div class="col-sm-6 col-lg-4"><article class="product-card">
      <div class="product-image"><?= $pic($p) ?></div>
      <div class="d-flex justify-content-between align-items-baseline gap-2"><h3><?= e($p['nama']) ?></h3><strong class="price">Rp <?= number_format((float)$p['harga'], 0, ',', '.') ?></strong></div>
      <p><?= e($p['deskripsi'] ?? '') ?></p>
      <a class="btn btn-pink w-100" href="pembelian.php?produk=<?= (int)$p['id'] ?>">Pesan buket ini</a>
    </article></div>
    <?php endforeach; ?>
  </div>
</div></section>

<section class="steps section-space"><div class="container">
  <h2>Cara memesan</h2>
  <div class="row g-4">
    <div class="col-md-4 step"><b>01</b><h3>Pilih buket</h3><p>Lihat koleksi dan tekan Pesan pada buket pilihanmu.</p></div>
    <div class="col-md-4 step"><b>02</b><h3>Isi formulir</h3><p>Tulis tanggal, alamat, dan ucapan untuk kartu.</p></div>
    <div class="col-md-4 step"><b>03</b><h3>Kami konfirmasi</h3><p>Admin menghubungi lewat WhatsApp, lalu buketmu dirangkai.</p></div>
  </div>
</div></section>

<section id="tentang" class="about section-space"><div class="container"><div class="row align-items-center g-5">
  <div class="col-lg-5"><img src="assets/img/produk/buket.jpeg" alt="Buket Nia's Bucket"></div>
  <div class="col-lg-7">
    <span class="eyebrow">Tentang kami</span>
    <h2>Hadiah yang terasa dipikirkan.</h2>
    
<p style="max-width:34rem">
     Ada rasa yang lebih mudah disampaikan lewat bunga. Rasa terima kasih, rindu, bangga, atau sekadar ingin bilang "aku mikirin kamu". Di Nia's Bucket, kami bantu rasa itu sampai ke tangan orang yang tepat, dalam rangkaian yang segar, rapi, dan dibuat dengan sepenuh hati. 
</p>
<span style="font-family: 'Marmelad', sans-serif; font-size: 35px; letter-spacing: 1.5px; color: #9f414f;">
    "Trust the process, trust the Lord"
</span>
  </div>
</div></div></section>

<section id="kontak" class="contact section-space"><div class="container">
  <span class="eyebrow" style="color:#f0a9bc">Kontak</span>
  <h2>Punya ide buket sendiri?</h2>
  <p class="mb-4">Ceritakan warna, ukuran, dan momennya. Kami bantu wujudkan.</p>
  <p><strong>WhatsApp</strong> +62 857-4068-2569 &nbsp;&nbsp; <strong>Instagram</strong> @nia.s_bucket <br><span><b>Lokasi  </b>Tingkir, Salatiga, Jawa Tengah</span></p>
  <a class="btn btn-butter mt-2" href="pembelian.php">Pesan buket</a>
</div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>

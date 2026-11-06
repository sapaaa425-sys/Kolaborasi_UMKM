<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pembelian.php'); exit;
}
if (!csrf_ok()) { http_response_code(403); exit('Sesi habis. Kembali ke form dan kirim ulang.'); }
$nama = trim($_POST['nama_pelanggan'] ?? '');
$telepon = trim($_POST['telepon'] ?? '');
$produkId = filter_input(INPUT_POST, 'produk_id', FILTER_VALIDATE_INT);
$jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT);
$tanggal = trim($_POST['tanggal_pengambilan'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$catatan = trim($_POST['catatan'] ?? '');
if ($nama === '' || $telepon === '' || !$produkId || !$jumlah || $jumlah < 1 || $jumlah > 50 || $alamat === '') {
    http_response_code(422); exit('Data belum lengkap atau tidak valid. Silakan kembali dan periksa form.');
}
$stmt = $pdo->prepare('SELECT id, harga FROM bouquets WHERE id = ? AND aktif = 1');
$stmt->execute([$produkId]);
$product = $stmt->fetch();
if (!$product) { http_response_code(422); exit('Produk tidak ditemukan atau sudah tidak aktif.'); }
$tanggal = $tanggal !== '' ? $tanggal : null;
if ($tanggal !== null) {
    $d = DateTime::createFromFormat('Y-m-d', $tanggal);
    if (!$d || $d->format('Y-m-d') !== $tanggal || $tanggal < date('Y-m-d')) {
        http_response_code(422); exit('Tanggal pengambilan tidak valid.');
    }
}
$total = (float)$product['harga'] * $jumlah;
$insert = $pdo->prepare('INSERT INTO purchases (pelanggan_id, nama_pelanggan, telepon, produk_id, jumlah, harga_satuan, total_harga, tanggal_pengambilan, alamat, catatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$insert->execute([$_SESSION['pelanggan_id'] ?? null, $nama, $telepon, $produkId, $jumlah, $product['harga'], $total, $tanggal, $alamat, $catatan ?: null]);
header('Location: pembelian.php?status=ok'); exit;

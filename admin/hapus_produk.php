<?php
require_once __DIR__ . '/../includes/auth.php'; require_admin();
require_once __DIR__ . '/../config/database.php';
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_ok() || !$id) { header('Location: produk.php'); exit; }
try {
    $g = $pdo->prepare('SELECT gambar FROM bouquets WHERE id = ?'); $g->execute([$id]); $old = $g->fetchColumn();
    $pdo->prepare('DELETE FROM bouquets WHERE id = ?')->execute([$id]);
    hapus_gambar_lokal($old ?: null);
    header('Location: produk.php?deleted=1');
} catch (PDOException $ex) {
    // Produk yang sudah pernah dipesan dilindungi foreign key.
    header('Location: produk.php?err=terpakai');
}
exit;

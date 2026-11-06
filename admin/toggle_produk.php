<?php
require_once __DIR__ . '/../includes/auth.php'; require_admin();
require_once __DIR__ . '/../config/database.php';
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_ok() && $id) {
    $pdo->prepare('UPDATE bouquets SET aktif = IF(aktif = 1, 0, 1) WHERE id = ?')->execute([$id]);
}
header('Location: produk.php'); exit;

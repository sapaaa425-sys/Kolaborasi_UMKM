<?php
require_once __DIR__ . '/../includes/auth.php'; require_admin();
require_once __DIR__ . '/../config/database.php';
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_ok() && $id && in_array($status, ['Menunggu','Diproses','Selesai','Dibatalkan'], true)) {
    $pdo->prepare('UPDATE purchases SET status = ? WHERE id = ?')->execute([$status, $id]);
}
header('Location: dashboard.php'); exit;

<?php
require_once __DIR__ . '/includes/auth.php';
unset($_SESSION['pelanggan_id'], $_SESSION['pelanggan_nama']);
session_regenerate_id(true);
header('Location: index.php'); exit;

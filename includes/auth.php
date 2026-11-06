<?php
// Sesi admin dan pelanggan dipisah: area /admin/ memakai cookie sesi sendiri.
if (session_status() === PHP_SESSION_NONE) {
    if (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/admin/') !== false) session_name('sekar_admin');
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}
function csrf_token(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(16)); }
function csrf_ok(): bool { return hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? ''); }
function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function require_admin(): void { if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; } }
function wa_link(string $tel): string { $n = preg_replace('/\D/', '', $tel); return 'https://wa.me/' . preg_replace('/^0/', '62', $n); }
// Unggah gambar produk: JPG/PNG/WebP maksimal 2 MB. null = tidak ada file, false = ditolak.
function upload_gambar(?array $f) {
    if (!$f || ($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if ($f['error'] !== UPLOAD_ERR_OK || $f['size'] > 2 * 1024 * 1024) return false;
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name'])] ?? null;
    if (!$ext) return false;
    $dir = __DIR__ . '/../assets/img/produk';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    return move_uploaded_file($f['tmp_name'], "$dir/$name") ? "assets/img/produk/$name" : false;
}
function hapus_gambar_lokal(?string $p): void {
    if ($p && preg_match('#^assets/img/produk/[a-f0-9]+\.(jpg|png|webp)$#', $p)) @unlink(__DIR__ . '/../' . $p);
}
function img_src(?string $g, string $base = ''): string { return preg_match('#^https?://#', (string)$g) ? $g : $base . $g; }

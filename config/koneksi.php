<?php
/**
 * config/koneksi.php
 * Leaf & Latte — Koneksi database + helper global
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Jakarta');

// === Base URL aplikasi ===
define('BASE_URL', 'http://localhost/taking_order_amjaem');

// === Konfigurasi Database ===
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_cafe');

// === Konfigurasi Upload ===
define('UPLOAD_DIR',      __DIR__ . '/../uploads/menu/');
define('UPLOAD_URL_PATH', BASE_URL . '/uploads/menu/');
define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024);
define('UPLOAD_ALLOWED',  ['image/jpeg', 'image/png', 'image/webp']);

// === Logo Brand ===
define('LOGO_PATH',  BASE_URL . '/assets/logo_brand.png');
define('FAVICON_PATH', BASE_URL . '/assets/logo_brand.png');

// === Koneksi ===
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $koneksi->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    die('Koneksi database gagal. Silakan hubungi administrator.');
}

// =====================================================
// Helper Functions
// =====================================================

function rupiah($angka): string
{
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

function generateKodeOrder(): string
{
    return 'ORD-' . date('Ymd-His') . '-' . strtoupper(bin2hex(random_bytes(2)));
}

/**
 * URL absolut ke file dalam proyek.
 * Contoh: url('admin/login.php') → http://localhost/taking_order_amjaem/admin/login.php
 */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * URL aset (gambar, css, js).
 */
function asset(string $path): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * URL gambar menu (atau null).
 */
function menuImageUrl(?string $filename): ?string
{
    if (!$filename) return null;
    if (!is_file(UPLOAD_DIR . $filename)) return null;
    return UPLOAD_URL_PATH . rawurlencode($filename);
}

/**
 * Cek apakah logo brand tersedia sebagai file.
 */
function logoExists(): bool
{
    return is_file(__DIR__ . '/../assets/logo_brand.png');
}

/**
 * Upload gambar menu.
 */
function uploadGambarMenu(array $file): ?string
{
    if (!isset($file['tmp_name']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload gagal (kode: ' . $file['error'] . ').');
    }
    if ($file['size'] > UPLOAD_MAX_SIZE) {
        throw new RuntimeException('Ukuran gambar maksimal 2 MB.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, UPLOAD_ALLOWED, true)) {
        throw new RuntimeException('Format gambar harus JPG, PNG, atau WEBP.');
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    $ext = match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        default      => 'jpg',
    };
    $filename = 'menu_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target   = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new RuntimeException('Gagal menyimpan file gambar.');
    }
    return $filename;
}

function hapusGambarMenu(?string $filename): void
{
    if ($filename && is_file(UPLOAD_DIR . $filename)) {
        @unlink(UPLOAD_DIR . $filename);
    }
}
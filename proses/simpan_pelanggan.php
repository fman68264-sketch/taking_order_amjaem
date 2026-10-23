<?php
require_once '../config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . url('index.php')); exit; }

$nama    = trim($_POST['nama'] ?? '');
$no_meja = trim($_POST['no_meja'] ?? '');
$no_hp   = trim($_POST['no_hp'] ?? '');

if ($nama === '' || $no_meja === '') {
    $_SESSION['error'] = 'Nama dan nomor meja wajib diisi.';
    header('Location: ' . url('index.php')); exit;
}

$stmt = $koneksi->prepare("INSERT INTO pelanggan (nama, no_meja, no_hp) VALUES (?,?,?)");
$stmt->bind_param('sss', $nama, $no_meja, $no_hp);
try {
    $stmt->execute();
    $_SESSION['pelanggan_id']   = $stmt->insert_id;
    $_SESSION['pelanggan_nama'] = $nama;
    $_SESSION['pelanggan_meja'] = $no_meja;
    $_SESSION['keranjang']      = [];
    header('Location: ' . url('pelanggan/menu.php'));
} catch (Throwable $e) {
    $_SESSION['error'] = 'Terjadi kesalahan saat menyimpan data.';
    header('Location: ' . url('index.php'));
}
$stmt->close();
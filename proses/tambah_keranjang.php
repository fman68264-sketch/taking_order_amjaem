<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header('Location: ' . url('index.php')); exit; }

$menu_id = (int)($_POST['menu_id'] ?? 0);
$jumlah  = max(1, (int)($_POST['jumlah'] ?? 1));

$stmt = $koneksi->prepare("SELECT * FROM menu WHERE id=? AND status='tersedia'");
$stmt->bind_param('i', $menu_id); $stmt->execute();
$menu = $stmt->get_result()->fetch_assoc(); $stmt->close();

if (!$menu) {
    $_SESSION['pesan'] = 'Menu tidak ditemukan atau habis.';
    header('Location: ' . url('pelanggan/menu.php')); exit;
}

if ($jumlah > $menu['stok']) $jumlah = (int)$menu['stok'];
if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];

$current = $_SESSION['keranjang'][$menu_id] ?? 0;
$_SESSION['keranjang'][$menu_id] = min($current + $jumlah, (int)$menu['stok']);

$_SESSION['pesan'] = $menu['nama_menu'] . ' ditambahkan ke keranjang.';
header('Location: ' . url('pelanggan/menu.php'));
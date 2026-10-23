<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/login.php')); exit; }

$id = (int)($_GET['id'] ?? 0);
$stmt = $koneksi->prepare("SELECT gambar FROM menu WHERE id=?");
$stmt->bind_param('i', $id); $stmt->execute();
$row = $stmt->get_result()->fetch_assoc(); $stmt->close();

if ($row) {
    hapusGambarMenu($row['gambar']);
    $d = $koneksi->prepare("DELETE FROM menu WHERE id=?");
    $d->bind_param('i', $id); $d->execute(); $d->close();
    $_SESSION['pesan'] = 'Menu berhasil dihapus.';
} else {
    $_SESSION['error'] = 'Menu tidak ditemukan.';
}
header('Location: ' . url('admin/menu.php'));
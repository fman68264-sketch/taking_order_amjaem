<?php
require_once '../config/koneksi.php';
$menu_id = (int)($_POST['menu_id'] ?? 0);
$jumlah  = max(1, (int)($_POST['jumlah'] ?? 1));
if (isset($_SESSION['keranjang'][$menu_id])) {
    $_SESSION['keranjang'][$menu_id] = $jumlah;
}
header('Location: ' . url('pelanggan/keranjang.php'));
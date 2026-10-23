<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header('Location: ' . url('index.php')); exit; }

$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) { header('Location: ' . url('pelanggan/keranjang.php')); exit; }

$pelanggan_id = (int)$_SESSION['pelanggan_id'];
$no_meja      = $_SESSION['pelanggan_meja'];
$catatan      = trim($_POST['catatan'] ?? '');
$kode_order   = generateKodeOrder();

$koneksi->begin_transaction();
try {
    $total = 0;
    $items = [];
    foreach ($keranjang as $mid => $jml) {
        $stmt = $koneksi->prepare("SELECT * FROM menu WHERE id=? FOR UPDATE");
        $stmt->bind_param('i', $mid); $stmt->execute();
        $m = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if (!$m || $m['stok'] < $jml) {
            throw new RuntimeException('Stok menu ' . ($m['nama_menu'] ?? '') . ' tidak cukup.');
        }
        $sub = $m['harga'] * $jml;
        $total += $sub;
        $items[] = [$m, $jml, $sub];
    }

    $ins = $koneksi->prepare("INSERT INTO orders (kode_order, pelanggan_id, no_meja, total_harga, catatan)
                              VALUES (?,?,?,?,?)");
    $ins->bind_param('sisds', $kode_order, $pelanggan_id, $no_meja, $total, $catatan);
    $ins->execute();
    $order_id = $ins->insert_id;
    $ins->close();

    $insD = $koneksi->prepare("INSERT INTO order_detail (order_id, menu_id, nama_menu, harga, jumlah, subtotal)
                               VALUES (?,?,?,?,?,?)");
    $updS = $koneksi->prepare("UPDATE menu SET stok = stok - ? WHERE id=?");
    foreach ($items as [$m, $jml, $sub]) {
        $insD->bind_param('iisdid', $order_id, $m['id'], $m['nama_menu'], $m['harga'], $jml, $sub);
        $insD->execute();
        $updS->bind_param('ii', $jml, $m['id']); $updS->execute();
    }
    $insD->close(); $updS->close();

    $koneksi->commit();
    $_SESSION['keranjang'] = [];
    $_SESSION['last_order_id'] = $order_id;
    header('Location: ' . url('pelanggan/sukses.php'));
} catch (Throwable $e) {
    $koneksi->rollback();
    $_SESSION['error'] = 'Gagal menyimpan order: ' . $e->getMessage();
    header('Location: ' . url('pelanggan/keranjang.php'));
}
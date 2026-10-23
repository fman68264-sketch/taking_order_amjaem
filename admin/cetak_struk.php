<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/login.php')); exit; }

$id = (int)($_GET['id'] ?? 0);
$stmt = $koneksi->prepare("SELECT o.*, p.nama, p.no_hp FROM orders o
                           JOIN pelanggan p ON o.pelanggan_id=p.id WHERE o.id=?");
$stmt->bind_param('i', $id); $stmt->execute();
$o = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$o) { die('Order tidak ditemukan.'); }

$detail = $koneksi->query("SELECT * FROM order_detail WHERE order_id=$id")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk <?= htmlspecialchars($o['kode_order']); ?></title>
    <style>
        @page { size: 80mm auto; margin: 4mm; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #3E2723;
            margin: 0;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #B99A94; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; }
        .total { font-size: 13px; font-weight: bold; color: #C24D68; }
        .header h2 { margin: 0; font-size: 14px; letter-spacing: 1px; color: #C24D68; }
        .header p { margin: 1px 0; font-size: 10px; color: #6B3A36; }
        .logo { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; margin: 0 auto 4px; display: block; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header center">
        <?php if (logoExists()): ?>
            <img src="<?= LOGO_PATH; ?>" alt="Leaf &amp; Latte" class="logo">
        <?php endif; ?>
        <h2>LEAF &amp; LATTE</h2>
        <p>Jl. Contoh Alamat No. 123</p>
        <p>Telp: 021-1234567</p>
    </div>
    <div class="divider"></div>

    <div class="row"><span>Kode</span><span><?= htmlspecialchars($o['kode_order']); ?></span></div>
    <div class="row"><span>Tanggal</span><span><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></span></div>
    <div class="row"><span>Nama</span><span><?= htmlspecialchars($o['nama']); ?></span></div>
    <div class="row"><span>Meja</span><span><?= htmlspecialchars($o['no_meja']); ?></span></div>
    <?php if (!empty($o['no_hp'])): ?>
    <div class="row"><span>No. HP</span><span><?= htmlspecialchars($o['no_hp']); ?></span></div>
    <?php endif; ?>

    <div class="divider"></div>

    <?php foreach ($detail as $d): ?>
        <div class="bold"><?= htmlspecialchars($d['nama_menu']); ?></div>
        <div class="row">
            <span><?= (int)$d['jumlah']; ?> x <?= number_format($d['harga'], 0, ',', '.'); ?></span>
            <span><?= number_format($d['subtotal'], 0, ',', '.'); ?></span>
        </div>
    <?php endforeach; ?>

    <div class="divider"></div>
    <div class="row total">
        <span>TOTAL</span>
        <span><?= rupiah($o['total_harga']); ?></span>
    </div>
    <div class="row"><span>Status</span><span><?= strtoupper($o['status']); ?></span></div>

    <?php if (!empty($o['catatan'])): ?>
    <div class="divider"></div>
    <div><span class="bold">Catatan:</span> <?= nl2br(htmlspecialchars($o['catatan'])); ?></div>
    <?php endif; ?>

    <div class="divider"></div>
    <div class="center">
        <p>Terima kasih atas kunjungan Anda</p>
        <p>Selamat menikmati</p>
    </div>

    <div class="no-print" style="margin-top:12px;text-align:center">
        <button onclick="window.print()"
                style="padding:6px 12px;border:none;background:#F192A9;color:#fff;border-radius:6px;cursor:pointer">
            Cetak
        </button>
    </div>
    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>
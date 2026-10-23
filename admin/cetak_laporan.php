<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/login.php')); exit; }

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

$stmt = $koneksi->prepare("SELECT o.*, p.nama FROM orders o
                           JOIN pelanggan p ON o.pelanggan_id=p.id
                           WHERE DATE(o.waktu_order) BETWEEN ? AND ?
                           ORDER BY o.waktu_order ASC");
$stmt->bind_param('ss', $tgl_awal, $tgl_akhir); $stmt->execute();
$q = $stmt->get_result();
$rows = []; $total = 0;
while ($r = $q->fetch_assoc()) {
    $rows[] = $r;
    if ($r['status'] === 'selesai') $total += $r['total_harga'];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - Leaf &amp; Latte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            @page { size: A4; margin: 15mm; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white p-8 text-sm text-chestnut">
    <div class="mb-6 border-b-4 border-double border-branddark pb-4 text-center">
        <?php if (logoExists()): ?>
            <img src="<?= LOGO_PATH; ?>" alt="Leaf &amp; Latte" class="mx-auto mb-2 h-16 w-16 rounded-xl object-cover">
        <?php endif; ?>
        <h1 class="text-2xl font-extrabold tracking-widest text-branddark">LEAF &amp; LATTE</h1>
        <p class="text-xs text-chestnut/70">Jl. Contoh Alamat No. 123, Kota Anda</p>
        <p class="text-xs text-chestnut/70">Telp: 021-1234567 &middot; info@leafandlatte.com</p>
    </div>

    <h2 class="mb-3 text-center text-lg font-bold text-chestnut">LAPORAN ORDER</h2>

    <div class="mb-3 text-xs">
        <p>Periode: <?= date('d/m/Y', strtotime($tgl_awal)); ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)); ?></p>
        <p>Tanggal Cetak: <?= date('d/m/Y H:i'); ?></p>
    </div>

    <table class="w-full border-collapse text-xs">
        <thead>
            <tr class="bg-blush">
                <th class="border border-chestnut/40 px-2 py-1.5 text-center text-branddark">No</th>
                <th class="border border-chestnut/40 px-2 py-1.5 text-left text-branddark">Kode Order</th>
                <th class="border border-chestnut/40 px-2 py-1.5 text-left text-branddark">Nama Pelanggan</th>
                <th class="border border-chestnut/40 px-2 py-1.5 text-center text-branddark">Meja</th>
                <th class="border border-chestnut/40 px-2 py-1.5 text-right text-branddark">Total</th>
                <th class="border border-chestnut/40 px-2 py-1.5 text-center text-branddark">Status</th>
                <th class="border border-chestnut/40 px-2 py-1.5 text-center text-branddark">Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="7" class="border border-chestnut/40 px-2 py-2 text-center">Tidak ada data.</td></tr>
            <?php endif; ?>
            <?php foreach ($rows as $i => $o): ?>
                <tr>
                    <td class="border border-chestnut/40 px-2 py-1.5 text-center"><?= $i + 1; ?></td>
                    <td class="border border-chestnut/40 px-2 py-1.5"><?= htmlspecialchars($o['kode_order']); ?></td>
                    <td class="border border-chestnut/40 px-2 py-1.5"><?= htmlspecialchars($o['nama']); ?></td>
                    <td class="border border-chestnut/40 px-2 py-1.5 text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
                    <td class="border border-chestnut/40 px-2 py-1.5 text-right"><?= rupiah($o['total_harga']); ?></td>
                    <td class="border border-chestnut/40 px-2 py-1.5 text-center"><?= ucfirst($o['status']); ?></td>
                    <td class="border border-chestnut/40 px-2 py-1.5 text-center"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-4 text-right text-sm font-bold text-branddark">
        Total Pendapatan (Order Selesai): <?= rupiah($total); ?>
    </div>

    <div class="mt-12 text-right text-xs">
        <p>Kota Anda, <?= date('d/m/Y'); ?></p>
        <p>Administrator,</p>
        <p class="mt-16 inline-block min-w-[180px] border-t border-chestnut pt-1 text-center">
            <?= htmlspecialchars($_SESSION['admin_nama']); ?>
        </p>
    </div>

    <div class="no-print mt-8 text-center">
        <button onclick="window.print()"
                class="rounded-xl bg-brand px-5 py-2 text-sm font-bold text-white">
            Cetak Sekarang
        </button>
    </div>

    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>
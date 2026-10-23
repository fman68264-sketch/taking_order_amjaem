<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header('Location: ' . url('index.php')); exit; }
$judul = 'Status Pesanan - Leaf & Latte';
include '../includes/header.php';
include '../includes/navbar.php';

$pelanggan_id = (int)$_SESSION['pelanggan_id'];
$stmt = $koneksi->prepare("SELECT * FROM orders WHERE pelanggan_id=? ORDER BY waktu_order DESC");
$stmt->bind_param('i', $pelanggan_id); $stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$statusMap = [
    'pending'    => ['Menunggu Konfirmasi', 'bg-warning-bg text-warning-fg', 'ph-hourglass'],
    'diproses'   => ['Sedang Diproses',     'bg-info-bg text-info-fg',       'ph-cooking-pot'],
    'selesai'    => ['Selesai',             'bg-success-bg text-success-fg', 'ph-check-circle'],
    'dibatalkan' => ['Dibatalkan',          'bg-danger-bg text-danger-fg',   'ph-x-circle'],
];
?>

<main class="mx-auto max-w-4xl px-4 py-8">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-branddark">Status Pesanan</h1>
            <p class="text-sm text-chestnut/60">Pantau progres pesanan Anda</p>
        </div>
        <a href="<?= url('pelanggan/menu.php'); ?>"
           class="flex items-center gap-2 rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-white shadow-soft transition hover:bg-branddark">
            <i class="ph ph-plus-circle"></i> Pesan Lagi
        </a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="rounded-3xl bg-white p-10 text-center shadow-soft ring-1 ring-chestnut/5">
            <i class="ph ph-receipt text-brand/40" style="font-size:3rem"></i>
            <p class="mt-2 text-chestnut/70">Belum ada pesanan pada sesi ini.</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($orders as $o):
                $s = $statusMap[$o['status']] ?? ['-', 'bg-chestnut/10 text-chestnut', 'ph-question'];
            ?>
            <div class="rounded-3xl bg-white p-5 shadow-soft ring-1 ring-chestnut/5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-mono text-xs text-chestnut/50"><?= htmlspecialchars($o['kode_order']); ?></p>
                        <p class="mt-1 text-sm text-chestnut/70">
                            <?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?>
                            &middot; Meja <?= htmlspecialchars($o['no_meja']); ?>
                        </p>
                    </div>
                    <span class="flex items-center gap-1.5 rounded-full <?= $s[1]; ?> px-3 py-1 text-xs font-semibold">
                        <i class="ph <?= $s[2]; ?>"></i> <?= $s[0]; ?>
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between border-t border-chestnut/10 pt-3">
                    <span class="text-sm text-chestnut/60">Total</span>
                    <span class="text-lg font-extrabold text-branddark"><?= rupiah($o['total_harga']); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
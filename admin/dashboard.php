<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/login.php')); exit; }
$judul = 'Dashboard Admin - Leaf & Latte';

$total_menu       = $koneksi->query("SELECT COUNT(*) j FROM menu")->fetch_assoc()['j'];
$total_order      = $koneksi->query("SELECT COUNT(*) j FROM orders")->fetch_assoc()['j'];
$total_pending    = $koneksi->query("SELECT COUNT(*) j FROM orders WHERE status='pending'")->fetch_assoc()['j'];
$total_pendapatan = $koneksi->query("SELECT COALESCE(SUM(total_harga),0) j FROM orders WHERE status='selesai'")->fetch_assoc()['j'];

$recent = $koneksi->query("SELECT o.*, p.nama FROM orders o
                           JOIN pelanggan p ON o.pelanggan_id = p.id
                           ORDER BY o.waktu_order DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

$topMenu = $koneksi->query("SELECT nama_menu, SUM(jumlah) total
                            FROM order_detail
                            GROUP BY menu_id, nama_menu
                            ORDER BY total DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/admin_navbar.php';
?>
<div class="flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-6">
        <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-branddark">Dashboard</h1>
                <p class="text-sm text-chestnut/60">Ringkasan aktivitas cafe Anda</p>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <?php
            $cards = [
                ['Total Menu',         $total_menu,               'ph-fork-knife'],
                ['Total Order',        $total_order,              'ph-receipt'],
                ['Order Pending',      $total_pending,            'ph-hourglass'],
                ['Pendapatan Selesai', rupiah($total_pendapatan), 'ph-wallet'],
            ];
            foreach ($cards as $c): ?>
                <div class="rounded-2xl bg-white p-5 shadow-soft ring-1 ring-chestnut/5">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-brand/15 text-branddark">
                        <i class="ph <?= $c[2]; ?>" style="font-size:1.25rem"></i>
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-chestnut/50"><?= $c[0]; ?></p>
                    <p class="mt-1 text-xl font-extrabold text-chestnut"><?= $c[1]; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-6 grid gap-5 lg:grid-cols-3">
            <div class="rounded-3xl bg-white p-5 shadow-soft ring-1 ring-chestnut/5 lg:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-chestnut/50">Order Terbaru</h2>
                    <a href="<?= url('admin/order.php'); ?>" class="text-xs font-semibold text-branddark hover:underline">Lihat semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-chestnut/10 text-left text-[10px] uppercase tracking-wider text-chestnut/40">
                                <th class="pb-2">Kode</th>
                                <th class="pb-2">Nama</th>
                                <th class="pb-2 text-right">Total</th>
                                <th class="pb-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-chestnut/5">
                            <?php if (empty($recent)): ?>
                                <tr><td colspan="4" class="py-6 text-center text-chestnut/50">Belum ada order.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($recent as $r):
                                $badge = match($r['status']) {
                                    'pending'    => 'bg-warning-bg text-warning-fg',
                                    'diproses'   => 'bg-info-bg text-info-fg',
                                    'selesai'    => 'bg-success-bg text-success-fg',
                                    'dibatalkan' => 'bg-danger-bg text-danger-fg',
                                    default      => 'bg-chestnut/10 text-chestnut',
                                };
                            ?>
                            <tr>
                                <td class="py-2 font-mono text-xs"><?= htmlspecialchars($r['kode_order']); ?></td>
                                <td class="py-2"><?= htmlspecialchars($r['nama']); ?></td>
                                <td class="py-2 text-right font-medium"><?= rupiah($r['total_harga']); ?></td>
                                <td class="py-2 text-center">
                                    <span class="rounded-full <?= $badge; ?> px-2.5 py-0.5 text-xs font-semibold">
                                        <?= ucfirst($r['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-5 shadow-soft ring-1 ring-chestnut/5">
                <h2 class="mb-4 text-xs font-bold uppercase tracking-widest text-chestnut/50">Menu Terlaris</h2>
                <?php if (empty($topMenu)): ?>
                    <p class="py-6 text-center text-sm text-chestnut/50">Belum ada data.</p>
                <?php else: ?>
                    <?php $max = max(array_column($topMenu, 'total') ?: [1]); ?>
                    <ul class="space-y-3">
                        <?php foreach ($topMenu as $t): ?>
                            <li>
                                <div class="mb-1 flex justify-between text-sm">
                                    <span class="font-medium"><?= htmlspecialchars($t['nama_menu']); ?></span>
                                    <span class="text-chestnut/60"><?= (int)$t['total']; ?>x</span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-blush">
                                    <div class="h-full rounded-full bg-brand"
                                         style="width: <?= max(5, ((int)$t['total'] / $max) * 100); ?>%"></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>
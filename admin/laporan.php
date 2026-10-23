<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/login.php')); exit; }
$judul = 'Laporan Order - Leaf & Latte';

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

$stmt = $koneksi->prepare("SELECT o.*, p.nama FROM orders o
                           JOIN pelanggan p ON o.pelanggan_id=p.id
                           WHERE DATE(o.waktu_order) BETWEEN ? AND ?
                           ORDER BY o.waktu_order DESC");
$stmt->bind_param('ss', $tgl_awal, $tgl_akhir); $stmt->execute();
$q = $stmt->get_result();

$total = 0;
$rows = [];
while ($r = $q->fetch_assoc()) {
    $rows[] = $r;
    if ($r['status'] === 'selesai') $total += $r['total_harga'];
}
$stmt->close();

include '../includes/header.php';
include '../includes/admin_navbar.php';
?>
<div class="flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-6">
        <h1 class="mb-5 text-2xl font-extrabold text-branddark">Laporan Order</h1>

        <form method="GET" class="mb-4 grid gap-3 rounded-3xl bg-white p-5 shadow-soft ring-1 ring-chestnut/5 sm:grid-cols-4">
            <div>
                <label class="mb-1 block text-xs font-semibold text-chestnut/60">Tanggal Awal</label>
                <input type="date" name="tgl_awal" value="<?= htmlspecialchars($tgl_awal); ?>"
                       class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-3 py-2 text-sm outline-none transition focus:border-brand focus:bg-white">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-chestnut/60">Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir); ?>"
                       class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-3 py-2 text-sm outline-none transition focus:border-brand focus:bg-white">
            </div>
            <div class="flex items-end">
                <button class="flex w-full items-center justify-center gap-1.5 rounded-xl bg-brand py-2 text-sm font-bold text-white transition hover:bg-branddark">
                    <i class="ph ph-magnifying-glass"></i> Filter
                </button>
            </div>
            <div class="flex items-end">
                <a href="<?= url('admin/cetak_laporan.php?tgl_awal=' . urlencode($tgl_awal) . '&tgl_akhir=' . urlencode($tgl_akhir)); ?>"
                   target="_blank"
                   class="flex w-full items-center justify-center gap-1.5 rounded-xl border border-chestnut/20 py-2 text-sm font-semibold text-chestnut transition hover:border-brand hover:text-branddark">
                    <i class="ph ph-printer"></i> Cetak
                </a>
            </div>
        </form>

        <div class="mb-4 rounded-2xl bg-brand/10 px-5 py-4 text-sm text-branddark ring-1 ring-brand/20">
            <strong>Total Pendapatan (order selesai):</strong> <?= rupiah($total); ?>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-soft ring-1 ring-chestnut/5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-branddark text-white">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">No</th>
                            <th class="px-4 py-3 text-left font-semibold">Kode</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold">Meja</th>
                            <th class="px-4 py-3 text-right font-semibold">Total</th>
                            <th class="px-4 py-3 text-center font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-chestnut/10">
                        <?php if (empty($rows)): ?>
                            <tr><td colspan="7" class="px-4 py-8 text-center text-chestnut/60">Tidak ada data.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($rows as $i => $o): ?>
                            <tr class="transition hover:bg-blush/60">
                                <td class="px-4 py-2.5"><?= $i + 1; ?></td>
                                <td class="px-4 py-2.5 font-mono text-xs"><?= htmlspecialchars($o['kode_order']); ?></td>
                                <td class="px-4 py-2.5"><?= htmlspecialchars($o['nama']); ?></td>
                                <td class="px-4 py-2.5"><?= htmlspecialchars($o['no_meja']); ?></td>
                                <td class="px-4 py-2.5 text-right font-medium"><?= rupiah($o['total_harga']); ?></td>
                                <td class="px-4 py-2.5 text-center"><?= ucfirst($o['status']); ?></td>
                                <td class="px-4 py-2.5 text-xs text-chestnut/70"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>
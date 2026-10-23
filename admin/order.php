<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/login.php')); exit; }
$judul = 'Data Order - Leaf & Latte';
include '../includes/header.php';
include '../includes/admin_navbar.php';

$filter = $_GET['status'] ?? '';
$where = '';
if ($filter !== '') {
    $f = $koneksi->real_escape_string($filter);
    $where = "WHERE o.status = '$f'";
}
$q = $koneksi->query("SELECT o.*, p.nama FROM orders o
                      JOIN pelanggan p ON o.pelanggan_id = p.id
                      $where ORDER BY o.waktu_order DESC");

$tabs = [
    ''           => ['Semua',      'bg-chestnut text-white'],
    'pending'    => ['Pending',    'bg-warning-fg text-white'],
    'diproses'   => ['Diproses',   'bg-info-fg text-white'],
    'selesai'    => ['Selesai',    'bg-success-fg text-white'],
    'dibatalkan' => ['Dibatalkan', 'bg-danger-fg text-white'],
];
?>
<div class="flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-6">
        <h1 class="mb-5 text-2xl font-extrabold text-branddark">Data Order</h1>

        <div class="mb-4 flex flex-wrap gap-2">
            <?php foreach ($tabs as $key => $t):
                $active = $filter === $key; ?>
                <a href="<?= url('admin/order.php' . ($key !== '' ? '?status=' . $key : '')); ?>"
                   class="rounded-full border px-4 py-1.5 text-xs font-semibold transition
                   <?= $active
                        ? $t[1] . ' border-transparent'
                        : 'border-chestnut/20 text-chestnut hover:border-brand hover:text-branddark'; ?>">
                    <?= $t[0]; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-soft ring-1 ring-chestnut/5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-branddark text-white">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Kode</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold">Meja</th>
                            <th class="px-4 py-3 text-right font-semibold">Total</th>
                            <th class="px-4 py-3 text-center font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold">Waktu</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-chestnut/10">
                        <?php if ($q->num_rows === 0): ?>
                            <tr><td colspan="7" class="px-4 py-8 text-center text-chestnut/60">Belum ada order.</td></tr>
                        <?php endif; ?>
                        <?php while ($o = $q->fetch_assoc()):
                            $b = match($o['status']) {
                                'pending'    => 'bg-warning-bg text-warning-fg',
                                'diproses'   => 'bg-info-bg text-info-fg',
                                'selesai'    => 'bg-success-bg text-success-fg',
                                'dibatalkan' => 'bg-danger-bg text-danger-fg',
                                default      => 'bg-chestnut/10 text-chestnut',
                            };
                        ?>
                        <tr class="transition hover:bg-blush/60">
                            <td class="px-4 py-2.5 font-mono text-xs"><?= htmlspecialchars($o['kode_order']); ?></td>
                            <td class="px-4 py-2.5"><?= htmlspecialchars($o['nama']); ?></td>
                            <td class="px-4 py-2.5"><?= htmlspecialchars($o['no_meja']); ?></td>
                            <td class="px-4 py-2.5 text-right font-medium"><?= rupiah($o['total_harga']); ?></td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="rounded-full <?= $b; ?> px-2.5 py-0.5 text-xs font-semibold"><?= ucfirst($o['status']); ?></span>
                            </td>
                            <td class="px-4 py-2.5 text-xs text-chestnut/70"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                            <td class="px-4 py-2.5 text-center">
                                <a href="<?= url('admin/order_detail.php?id=' . (int)$o['id']); ?>"
                                   class="inline-flex rounded-lg bg-brand/10 p-2 text-branddark transition hover:bg-brand/20">
                                    <i class="ph ph-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>
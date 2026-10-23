<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/login.php')); exit; }
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = in_array($_POST['status'] ?? '', ['pending','diproses','selesai','dibatalkan'], true)
              ? $_POST['status'] : 'pending';
    $u = $koneksi->prepare("UPDATE orders SET status=? WHERE id=?");
    $u->bind_param('si', $status, $id); $u->execute(); $u->close();
    $_SESSION['pesan'] = 'Status order berhasil diperbarui.';
    header('Location: ' . url('admin/order_detail.php?id=' . $id)); exit;
}

$stmt = $koneksi->prepare("SELECT o.*, p.nama, p.no_hp FROM orders o
                           JOIN pelanggan p ON o.pelanggan_id = p.id WHERE o.id=?");
$stmt->bind_param('i', $id); $stmt->execute();
$o = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$o) { header('Location: ' . url('admin/order.php')); exit; }

$detail = $koneksi->query("SELECT * FROM order_detail WHERE order_id=$id")->fetch_all(MYSQLI_ASSOC);

$judul = 'Detail Order - Leaf & Latte';
include '../includes/header.php';
include '../includes/admin_navbar.php';
?>
<div class="flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-6">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <a href="<?= url('admin/order.php'); ?>" class="inline-flex items-center gap-1.5 text-sm text-chestnut/60 hover:text-branddark">
                <i class="ph ph-arrow-left"></i> Kembali
            </a>
            <a href="<?= url('admin/cetak_struk.php?id=' . (int)$o['id']); ?>" target="_blank"
               class="flex items-center gap-1.5 rounded-xl border border-chestnut/20 px-3 py-2 text-sm font-semibold text-chestnut transition hover:border-brand hover:text-branddark">
                <i class="ph ph-printer"></i> Cetak Struk
            </a>
        </div>
        <h1 class="mb-5 text-2xl font-extrabold text-branddark">Detail Order <?= htmlspecialchars($o['kode_order']); ?></h1>

        <?php if (!empty($_SESSION['pesan'])): ?>
            <div class="mb-4 rounded-xl bg-success-bg px-4 py-3 text-sm text-success-fg">
                <?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?>
            </div>
        <?php endif; ?>

        <div class="grid gap-5 lg:grid-cols-5">
            <div class="space-y-4 lg:col-span-2">
                <div class="rounded-3xl bg-white p-6 shadow-soft ring-1 ring-chestnut/5">
                    <h2 class="mb-4 text-xs font-bold uppercase tracking-widest text-chestnut/50">Informasi Pelanggan</h2>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-chestnut/60">Nama</dt><dd class="font-medium"><?= htmlspecialchars($o['nama']); ?></dd></div>
                        <div class="flex justify-between"><dt class="text-chestnut/60">No. HP</dt><dd class="font-medium"><?= htmlspecialchars($o['no_hp'] ?: '-'); ?></dd></div>
                        <div class="flex justify-between"><dt class="text-chestnut/60">No. Meja</dt><dd class="font-medium"><?= htmlspecialchars($o['no_meja']); ?></dd></div>
                        <div class="flex justify-between"><dt class="text-chestnut/60">Waktu</dt><dd class="font-medium"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></dd></div>
                        <div class="flex justify-between"><dt class="text-chestnut/60">Catatan</dt><dd class="max-w-[60%] text-right font-medium"><?= htmlspecialchars($o['catatan'] ?: '-'); ?></dd></div>
                    </dl>
                </div>
                <div class="rounded-3xl bg-white p-6 shadow-soft ring-1 ring-chestnut/5">
                    <h2 class="mb-3 text-xs font-bold uppercase tracking-widest text-chestnut/50">Update Status</h2>
                    <form method="POST">
                        <select name="status"
                                class="mb-3 w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                            <?php foreach (['pending','diproses','selesai','dibatalkan'] as $s): ?>
                                <option value="<?= $s; ?>" <?= $o['status']===$s?'selected':''; ?>><?= ucfirst($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="w-full rounded-xl bg-brand py-2.5 text-sm font-bold text-white transition hover:bg-branddark">
                            Simpan Status
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="overflow-hidden rounded-3xl bg-white shadow-soft ring-1 ring-chestnut/5">
                    <table class="w-full text-sm">
                        <thead class="bg-branddark text-white">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Menu</th>
                                <th class="px-4 py-3 text-right font-semibold">Harga</th>
                                <th class="px-4 py-3 text-center font-semibold">Jumlah</th>
                                <th class="px-4 py-3 text-right font-semibold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-chestnut/10">
                            <?php foreach ($detail as $d): ?>
                                <tr>
                                    <td class="px-4 py-2.5"><?= htmlspecialchars($d['nama_menu']); ?></td>
                                    <td class="px-4 py-2.5 text-right"><?= rupiah($d['harga']); ?></td>
                                    <td class="px-4 py-2.5 text-center"><?= (int)$d['jumlah']; ?></td>
                                    <td class="px-4 py-2.5 text-right font-medium"><?= rupiah($d['subtotal']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-blush">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-semibold">Total</td>
                                <td class="px-4 py-3 text-right text-lg font-extrabold text-branddark"><?= rupiah($o['total_harga']); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include '../includes/footer.php'; ?>
<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header('Location: ' . url('index.php')); exit; }
$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) { header('Location: ' . url('pelanggan/keranjang.php')); exit; }

$total = 0;
foreach ($keranjang as $mid => $jml) {
    $m = $koneksi->query("SELECT harga FROM menu WHERE id = " . (int)$mid)->fetch_assoc();
    if ($m) $total += $m['harga'] * $jml;
}
$judul = 'Checkout - Leaf & Latte';
include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="mx-auto max-w-5xl px-4 py-8">
    <h1 class="mb-6 text-2xl font-extrabold text-branddark">Konfirmasi Pesanan</h1>
    <div class="grid gap-5 md:grid-cols-5">
        <div class="space-y-4 md:col-span-3">
            <div class="rounded-3xl bg-white p-6 shadow-soft ring-1 ring-chestnut/5">
                <h2 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-chestnut/50">
                    <i class="ph ph-user text-branddark"></i> Data Pelanggan
                </h2>
                <dl class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-chestnut/60">Nama</dt>
                        <dd class="font-semibold"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-chestnut/60">No. Meja</dt>
                        <dd class="font-semibold"><?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></dd>
                    </div>
                </dl>
            </div>

            <form action="<?= url('proses/simpan_order.php'); ?>" method="POST" class="space-y-4">
                <div class="rounded-3xl bg-white p-6 shadow-soft ring-1 ring-chestnut/5">
                    <h2 class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-chestnut/50">
                        <i class="ph ph-note-pencil text-branddark"></i> Catatan Tambahan
                    </h2>
                    <textarea name="catatan" rows="3" placeholder="Contoh: tanpa gula, extra shot (opsional)"
                              class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-3 text-sm outline-none transition placeholder:text-chestnut/40 focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30"></textarea>
                </div>
                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-brand py-3 text-sm font-bold text-white shadow-soft transition hover:bg-branddark active:scale-[0.99]">
                    <i class="ph ph-paper-plane-tilt"></i> Kirim Pesanan
                </button>
            </form>
        </div>

        <div class="md:col-span-2">
            <div class="rounded-3xl bg-white p-6 shadow-soft ring-1 ring-chestnut/5">
                <h2 class="mb-4 text-xs font-bold uppercase tracking-widest text-chestnut/50">Rincian Pesanan</h2>
                <?php foreach ($keranjang as $mid => $jml):
                    $m = $koneksi->query("SELECT * FROM menu WHERE id = " . (int)$mid)->fetch_assoc();
                    if (!$m) continue;
                ?>
                <div class="mb-2 flex justify-between text-sm">
                    <span class="text-chestnut">
                        <?= htmlspecialchars($m['nama_menu']); ?>
                        <span class="text-chestnut/50">&times; <?= (int)$jml; ?></span>
                    </span>
                    <span class="font-medium"><?= rupiah($m['harga'] * $jml); ?></span>
                </div>
                <?php endforeach; ?>
                <hr class="my-4 border-chestnut/10">
                <div class="flex items-center justify-between">
                    <span class="font-bold">Total</span>
                    <span class="text-xl font-extrabold text-branddark"><?= rupiah($total); ?></span>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
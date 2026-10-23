<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header('Location: ' . url('index.php')); exit; }
$judul = 'Keranjang - Leaf & Latte';
include '../includes/header.php';
include '../includes/navbar.php';

$keranjang = $_SESSION['keranjang'] ?? [];
$total = 0;
?>

<main class="mx-auto max-w-5xl px-4 py-8">
    <h1 class="mb-6 text-2xl font-extrabold text-branddark">Keranjang Pesanan</h1>

    <?php if (empty($keranjang)): ?>
        <div class="rounded-3xl bg-white p-10 text-center shadow-soft ring-1 ring-chestnut/5">
            <i class="ph ph-shopping-cart text-brand/40" style="font-size:3rem"></i>
            <p class="mt-2 text-chestnut/70">Keranjang masih kosong.</p>
            <a href="<?= url('pelanggan/menu.php'); ?>"
               class="mt-4 inline-flex items-center gap-2 rounded-xl bg-brand px-5 py-2 text-sm font-semibold text-white transition hover:bg-branddark">
                <i class="ph ph-coffee"></i> Lihat Menu
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-hidden rounded-3xl bg-white shadow-soft ring-1 ring-chestnut/5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-branddark text-white">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Menu</th>
                            <th class="px-4 py-3 text-right font-semibold">Harga</th>
                            <th class="px-4 py-3 text-center font-semibold">Jumlah</th>
                            <th class="px-4 py-3 text-right font-semibold">Subtotal</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-chestnut/10">
                        <?php foreach ($keranjang as $mid => $jml):
                            $m = $koneksi->query("SELECT * FROM menu WHERE id = " . (int)$mid)->fetch_assoc();
                            if (!$m) continue;
                            $sub = $m['harga'] * $jml;
                            $total += $sub;
                        ?>
                        <tr>
                            <td class="px-4 py-3 font-medium"><?= htmlspecialchars($m['nama_menu']); ?></td>
                            <td class="px-4 py-3 text-right"><?= rupiah($m['harga']); ?></td>
                            <td class="px-4 py-3">
                                <form action="<?= url('proses/update_keranjang.php'); ?>" method="POST"
                                      class="flex items-center justify-center gap-1">
                                    <input type="hidden" name="menu_id" value="<?= (int)$mid; ?>">
                                    <input type="number" name="jumlah" value="<?= (int)$jml; ?>" min="1" max="<?= (int)$m['stok']; ?>"
                                           class="w-16 rounded-lg border border-chestnut/15 bg-blush/60 px-2 py-1 text-center text-sm outline-none transition focus:border-brand focus:bg-white">
                                    <button type="submit"
                                            class="rounded-lg border border-brand/40 p-1.5 text-branddark transition hover:bg-brand/10"
                                            aria-label="Perbarui jumlah">
                                        <i class="ph ph-arrows-clockwise"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right font-bold"><?= rupiah($sub); ?></td>
                            <td class="px-4 py-3 text-center">
                                <a href="<?= url('proses/hapus_keranjang.php?id=' . (int)$mid); ?>"
                                   class="inline-flex rounded-lg bg-danger-bg p-2 text-danger-fg transition hover:brightness-95"
                                   aria-label="Hapus item">
                                    <i class="ph ph-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-blush">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right font-semibold">Total</td>
                            <td colspan="2" class="px-4 py-3 text-right text-lg font-extrabold text-branddark"><?= rupiah($total); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap justify-between gap-3">
            <a href="<?= url('pelanggan/menu.php'); ?>"
               class="flex items-center gap-2 rounded-xl border border-chestnut/20 px-5 py-2.5 text-sm font-semibold text-chestnut transition hover:border-brand hover:text-branddark">
                <i class="ph ph-arrow-left"></i> Lanjut Pesan
            </a>
            <a href="<?= url('pelanggan/checkout.php'); ?>"
               class="flex items-center gap-2 rounded-xl bg-brand px-5 py-2.5 text-sm font-bold text-white shadow-soft transition hover:bg-branddark">
                Checkout <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
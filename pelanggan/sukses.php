<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id']) || !isset($_SESSION['last_order_id'])) {
    header('Location: ' . url('index.php')); exit;
}

$oid = (int)$_SESSION['last_order_id'];
$stmt = $koneksi->prepare("SELECT o.*, p.nama, p.no_hp FROM orders o
                           JOIN pelanggan p ON o.pelanggan_id = p.id
                           WHERE o.id = ?");
$stmt->bind_param('i', $oid);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) { header('Location: ' . url('index.php')); exit; }

$detail = $koneksi->query("SELECT * FROM order_detail WHERE order_id = $oid")->fetch_all(MYSQLI_ASSOC);

$judul = 'Struk Pesanan - Leaf & Latte';
include '../includes/header.php';
?>

<main class="mx-auto max-w-md px-4 py-8">
    <div class="mb-5 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand/15 text-branddark">
            <i class="ph-fill ph-check-circle" style="font-size:2.25rem"></i>
        </div>
        <h1 class="mt-3 text-xl font-extrabold text-branddark">Pesanan Berhasil</h1>
        <p class="text-sm text-chestnut/60">Tunjukkan struk ini ke kasir atau pelayan</p>
    </div>

    <div class="rounded-3xl bg-white p-6 font-mono text-sm shadow-card ring-1 ring-chestnut/5">
        <div class="text-center">
            <?php if (logoExists()): ?>
                <img src="<?= LOGO_PATH; ?>" alt="Leaf &amp; Latte" class="mx-auto mb-2 h-12 w-12 rounded-xl object-cover">
            <?php endif; ?>
            <h2 class="font-bold tracking-wider text-branddark">LEAF &amp; LATTE</h2>
            <p class="text-xs text-chestnut/60">Jl. Contoh Alamat No. 123, Kota Anda</p>
            <p class="text-xs text-chestnut/60">Telp: 021-1234567</p>
        </div>
        <div class="my-3 border-t border-dashed border-chestnut/20"></div>

        <div class="space-y-1 text-xs">
            <div class="flex justify-between"><span class="text-chestnut/60">Kode Order</span><span><?= htmlspecialchars($order['kode_order']); ?></span></div>
            <div class="flex justify-between"><span class="text-chestnut/60">Tanggal</span><span><?= date('d/m/Y H:i', strtotime($order['waktu_order'])); ?></span></div>
            <div class="flex justify-between"><span class="text-chestnut/60">Nama</span><span><?= htmlspecialchars($order['nama']); ?></span></div>
            <div class="flex justify-between"><span class="text-chestnut/60">Meja</span><span><?= htmlspecialchars($order['no_meja']); ?></span></div>
            <?php if (!empty($order['no_hp'])): ?>
            <div class="flex justify-between"><span class="text-chestnut/60">No. HP</span><span><?= htmlspecialchars($order['no_hp']); ?></span></div>
            <?php endif; ?>
        </div>

        <div class="my-3 border-t border-dashed border-chestnut/20"></div>

        <?php foreach ($detail as $d): ?>
            <div class="mb-1 text-xs">
                <div class="font-semibold"><?= htmlspecialchars($d['nama_menu']); ?></div>
                <div class="flex justify-between text-chestnut/60">
                    <span><?= (int)$d['jumlah']; ?> x <?= number_format($d['harga'], 0, ',', '.'); ?></span>
                    <span><?= number_format($d['subtotal'], 0, ',', '.'); ?></span>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="my-3 border-t border-dashed border-chestnut/20"></div>

        <div class="flex justify-between text-sm font-extrabold text-branddark">
            <span>TOTAL</span><span><?= rupiah($order['total_harga']); ?></span>
        </div>
        <div class="mt-1 flex justify-between text-xs">
            <span class="text-chestnut/60">Status</span>
            <span class="uppercase"><?= htmlspecialchars($order['status']); ?></span>
        </div>

        <?php if (!empty($order['catatan'])): ?>
            <div class="my-3 border-t border-dashed border-chestnut/20"></div>
            <div class="text-xs">
                <div class="font-semibold">Catatan</div>
                <div class="text-chestnut/70"><?= nl2br(htmlspecialchars($order['catatan'])); ?></div>
            </div>
        <?php endif; ?>

        <div class="my-3 border-t border-dashed border-chestnut/20"></div>
        <p class="text-center text-xs text-chestnut/60">Terima kasih atas kunjungan Anda</p>
    </div>

    <div class="mt-6 flex flex-wrap justify-center gap-2">
        <a href="<?= url('pelanggan/status.php'); ?>"
           class="flex items-center gap-2 rounded-xl border border-chestnut/20 px-4 py-2 text-sm font-semibold text-chestnut transition hover:border-brand hover:text-branddark">
            <i class="ph ph-receipt"></i> Cek Status
        </a>
        <a href="<?= url('pelanggan/menu.php'); ?>"
           class="flex items-center gap-2 rounded-xl border border-chestnut/20 px-4 py-2 text-sm font-semibold text-chestnut transition hover:border-brand hover:text-branddark">
            <i class="ph ph-plus-circle"></i> Order Lagi
        </a>
        <a href="<?= url('pelanggan/logout_pelanggan.php'); ?>"
           class="flex items-center gap-2 rounded-xl bg-chestnut px-4 py-2 text-sm font-semibold text-white transition hover:bg-chestnut/90">
            <i class="ph ph-sign-out"></i> Selesai
        </a>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
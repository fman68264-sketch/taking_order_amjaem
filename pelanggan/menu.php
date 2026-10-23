<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header('Location: ' . url('index.php')); exit; }
$judul = 'Menu - Leaf & Latte';
include '../includes/header.php';
include '../includes/navbar.php';

$kategori_filter = $_GET['kategori'] ?? '';
$where = "WHERE status = 'tersedia'";
if ($kategori_filter !== '') {
    $kf = $koneksi->real_escape_string($kategori_filter);
    $where .= " AND kategori = '$kf'";
}
$query = $koneksi->query("SELECT * FROM menu $where ORDER BY kategori, nama_menu");
$kategori_list = $koneksi->query("SELECT DISTINCT kategori FROM menu ORDER BY kategori");
?>

<main class="mx-auto max-w-7xl px-4 py-8">
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-branddark">Menu Cafe</h1>
            <p class="text-sm text-chestnut/60">Pilih menu favorit Anda</p>
        </div>
        <a href="<?= url('pelanggan/keranjang.php'); ?>"
           class="flex items-center gap-2 rounded-xl border border-brand/30 bg-brand/10 px-4 py-2 text-sm font-semibold text-branddark transition hover:bg-brand/20">
            <i class="ph ph-shopping-cart"></i> Lihat Keranjang
        </a>
    </div>

    <?php if (!empty($_SESSION['pesan'])): ?>
        <div class="mb-4 rounded-xl bg-info-bg px-4 py-3 text-sm text-info-fg">
            <?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?>
        </div>
    <?php endif; ?>

    <div class="mb-6 flex flex-wrap gap-2">
        <a href="menu.php"
           class="rounded-full px-4 py-1.5 text-sm font-semibold transition
           <?= $kategori_filter === '' ? 'bg-branddark text-white' : 'border border-chestnut/20 text-chestnut hover:border-brand hover:text-branddark'; ?>">
            Semua
        </a>
        <?php while ($k = $kategori_list->fetch_assoc()): ?>
            <a href="menu.php?kategori=<?= urlencode($k['kategori']); ?>"
               class="rounded-full px-4 py-1.5 text-sm font-semibold transition
               <?= $kategori_filter === $k['kategori'] ? 'bg-branddark text-white' : 'border border-chestnut/20 text-chestnut hover:border-brand hover:text-branddark'; ?>">
                <?= htmlspecialchars($k['kategori']); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <?php if ($query->num_rows === 0): ?>
            <div class="col-span-full rounded-3xl border border-chestnut/10 bg-white p-10 text-center">
                <i class="ph ph-coffee text-brand/40" style="font-size:3rem"></i>
                <p class="mt-2 text-sm text-chestnut/60">Belum ada menu tersedia.</p>
            </div>
        <?php endif; ?>
        <?php while ($m = $query->fetch_assoc()):
            $stokKosong = (int)$m['stok'] <= 0;
            $imgUrl = menuImageUrl($m['gambar']);
        ?>
            <article class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-soft ring-1 ring-chestnut/5 transition hover:-translate-y-1 hover:shadow-card">
                <div class="relative aspect-[4/3] overflow-hidden bg-cream/40">
                    <?php if ($imgUrl): ?>
                        <img src="<?= $imgUrl; ?>" alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    <?php else: ?>
                        <div class="flex h-full w-full items-center justify-center text-brand/50">
                            <i class="ph ph-image" style="font-size:3rem"></i>
                        </div>
                    <?php endif; ?>
                    <span class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-branddark shadow-sm backdrop-blur">
                        <?= htmlspecialchars($m['kategori']); ?>
                    </span>
                    <?php if ($stokKosong): ?>
                        <span class="absolute right-3 top-3 rounded-full bg-danger-fg px-3 py-1 text-xs font-semibold text-white">
                            Habis
                        </span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <h3 class="text-lg font-bold text-chestnut"><?= htmlspecialchars($m['nama_menu']); ?></h3>
                    <p class="mt-1 line-clamp-2 flex-1 text-sm text-chestnut/60"><?= htmlspecialchars($m['deskripsi']); ?></p>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="text-lg font-extrabold text-branddark"><?= rupiah($m['harga']); ?></span>
                        <span class="text-xs text-chestnut/50">Stok: <?= (int)$m['stok']; ?></span>
                    </div>
                    <form action="<?= url('proses/tambah_keranjang.php'); ?>" method="POST" class="mt-4 flex gap-2">
                        <input type="hidden" name="menu_id" value="<?= (int)$m['id']; ?>">
                        <input type="number" name="jumlah" value="1" min="1" max="<?= (int)$m['stok']; ?>"
                               <?= $stokKosong ? 'disabled' : ''; ?>
                               class="w-16 rounded-xl border border-chestnut/15 bg-blush/60 px-2 py-2 text-center text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30 disabled:opacity-40">
                        <button type="submit" <?= $stokKosong ? 'disabled' : ''; ?>
                                class="flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-brand py-2 text-sm font-semibold text-white transition hover:bg-branddark active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-40">
                            <i class="ph ph-plus-circle"></i> Tambah
                        </button>
                    </form>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
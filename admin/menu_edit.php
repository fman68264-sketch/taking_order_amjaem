<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/login.php')); exit; }
$judul = 'Edit Menu - Leaf & Latte';

$id = (int)($_GET['id'] ?? 0);
$stmt = $koneksi->prepare("SELECT * FROM menu WHERE id=?");
$stmt->bind_param('i', $id); $stmt->execute();
$m = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$m) { header('Location: ' . url('admin/menu.php')); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['nama_menu'] ?? '');
    $kat    = trim($_POST['kategori'] ?? '');
    $harga  = (float)($_POST['harga'] ?? 0);
    $desc   = trim($_POST['deskripsi'] ?? '');
    $stok   = (int)($_POST['stok'] ?? 0);
    $status = ($_POST['status'] ?? 'tersedia') === 'habis' ? 'habis' : 'tersedia';

    try {
        if ($nama === '' || $kat === '' || $harga <= 0) {
            throw new RuntimeException('Nama, kategori, dan harga wajib diisi dengan benar.');
        }

        $newGambar = uploadGambarMenu($_FILES['gambar'] ?? []);
        $gambarFinal = $m['gambar'];
        if ($newGambar) {
            hapusGambarMenu($m['gambar']);
            $gambarFinal = $newGambar;
        } elseif (!empty($_POST['hapus_gambar']) && $_POST['hapus_gambar'] === '1') {
            hapusGambarMenu($m['gambar']);
            $gambarFinal = null;
        }

        $u = $koneksi->prepare("UPDATE menu SET nama_menu=?, kategori=?, harga=?, deskripsi=?, stok=?, gambar=?, status=? WHERE id=?");
        $u->bind_param('ssdsissi', $nama, $kat, $harga, $desc, $stok, $gambarFinal, $status, $id);
        $u->execute(); $u->close();

        $_SESSION['pesan'] = 'Menu berhasil diperbarui.';
        header('Location: ' . url('admin/menu.php')); exit;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
include '../includes/header.php';
include '../includes/admin_navbar.php';

$imgUrl = menuImageUrl($m['gambar']);
?>
<div class="flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-6">
        <div class="mb-5">
            <a href="<?= url('admin/menu.php'); ?>" class="mb-2 inline-flex items-center gap-1.5 text-sm text-chestnut/60 hover:text-branddark">
                <i class="ph ph-arrow-left"></i> Kembali
            </a>
            <h1 class="text-2xl font-extrabold text-branddark">Edit Menu</h1>
        </div>

        <?php if ($error): ?>
            <div class="mb-4 rounded-xl bg-danger-bg px-4 py-3 text-sm text-danger-fg">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data"
              class="max-w-3xl rounded-3xl bg-white p-6 shadow-soft ring-1 ring-chestnut/5">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Nama Menu</label>
                    <input type="text" name="nama_menu" required value="<?= htmlspecialchars($m['nama_menu']); ?>"
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Kategori</label>
                    <input type="text" name="kategori" required value="<?= htmlspecialchars($m['kategori']); ?>"
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Harga (Rp)</label>
                    <input type="number" name="harga" min="0" step="500" required value="<?= (int)$m['harga']; ?>"
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Stok</label>
                    <input type="number" name="stok" min="0" required value="<?= (int)$m['stok']; ?>"
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Status</label>
                    <select name="status"
                            class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                        <option value="tersedia" <?= $m['status']==='tersedia'?'selected':''; ?>>Tersedia</option>
                        <option value="habis"    <?= $m['status']==='habis'?'selected':''; ?>>Habis</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Gambar Saat Ini</label>
                    <div class="flex items-center gap-3">
                        <div class="h-16 w-16 overflow-hidden rounded-lg bg-cream/40 ring-1 ring-chestnut/10">
                            <?php if ($imgUrl): ?>
                                <img src="<?= $imgUrl; ?>" class="h-full w-full object-cover">
                            <?php else: ?>
                                <div class="flex h-full w-full items-center justify-center text-brand/40">
                                    <i class="ph ph-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($imgUrl): ?>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-danger-fg">
                                <input type="checkbox" name="hapus_gambar" value="1" class="accent-branddark">
                                Hapus gambar
                            </label>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">
                        Ganti Gambar <span class="font-normal text-chestnut/50">(opsional)</span>
                    </label>
                    <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp"
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-brand file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-white hover:file:bg-branddark">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-3 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30"><?= htmlspecialchars($m['deskripsi']); ?></textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit"
                        class="flex items-center gap-2 rounded-xl bg-brand px-5 py-2.5 text-sm font-bold text-white transition hover:bg-branddark">
                    <i class="ph ph-floppy-disk"></i> Update
                </button>
                <a href="<?= url('admin/menu.php'); ?>"
                   class="rounded-xl border border-chestnut/20 px-5 py-2.5 text-sm font-semibold text-chestnut transition hover:border-brand hover:text-branddark">
                    Batal
                </a>
            </div>
        </form>
    </main>
</div>
<?php include '../includes/footer.php'; ?>
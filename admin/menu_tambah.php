<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/login.php')); exit; }
$judul = 'Tambah Menu - Leaf & Latte';
include '../includes/header.php';
include '../includes/admin_navbar.php';

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
        $gambar = uploadGambarMenu($_FILES['gambar'] ?? []);

        $stmt = $koneksi->prepare("INSERT INTO menu (nama_menu, kategori, harga, deskripsi, stok, gambar, status)
                                   VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('ssdsiss', $nama, $kat, $harga, $desc, $stok, $gambar, $status);
        $stmt->execute();
        $stmt->close();

        $_SESSION['pesan'] = 'Menu berhasil ditambahkan.';
        header('Location: ' . url('admin/menu.php'));
        exit;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<div class="flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-6">
        <div class="mb-5">
            <a href="<?= url('admin/menu.php'); ?>" class="mb-2 inline-flex items-center gap-1.5 text-sm text-chestnut/60 hover:text-branddark">
                <i class="ph ph-arrow-left"></i> Kembali
            </a>
            <h1 class="text-2xl font-extrabold text-branddark">Tambah Menu</h1>
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
                    <input type="text" name="nama_menu" required value="<?= htmlspecialchars($_POST['nama_menu'] ?? ''); ?>"
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Kategori</label>
                    <input type="text" name="kategori" required placeholder="Kopi / Non-Kopi / Makanan / Minuman"
                           value="<?= htmlspecialchars($_POST['kategori'] ?? ''); ?>"
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Harga (Rp)</label>
                    <input type="number" name="harga" min="0" step="500" required value="<?= htmlspecialchars($_POST['harga'] ?? ''); ?>"
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Stok</label>
                    <input type="number" name="stok" min="0" required value="<?= htmlspecialchars($_POST['stok'] ?? '0'); ?>"
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Status</label>
                    <select name="status"
                            class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                        <option value="tersedia">Tersedia</option>
                        <option value="habis">Habis</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">
                        Gambar Produk
                        <span class="font-normal text-chestnut/50">(JPG/PNG/WEBP, maks 2MB)</span>
                    </label>
                    <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp"
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-brand file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-white hover:file:bg-branddark">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full rounded-xl border border-chestnut/15 bg-blush/60 px-4 py-3 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30"><?= htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit"
                        class="flex items-center gap-2 rounded-xl bg-brand px-5 py-2.5 text-sm font-bold text-white transition hover:bg-branddark">
                    <i class="ph ph-floppy-disk"></i> Simpan
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
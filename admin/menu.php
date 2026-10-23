<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/login.php')); exit; }
$judul = 'Kelola Menu - Leaf & Latte';
include '../includes/header.php';
include '../includes/admin_navbar.php';

$q = $koneksi->query("SELECT * FROM menu ORDER BY kategori, nama_menu");
?>
<div class="flex">
    <?php include '../includes/admin_sidebar.php'; ?>
    <main class="flex-1 p-6">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-branddark">Kelola Menu</h1>
                <p class="text-sm text-chestnut/60">Atur menu, harga, stok, dan gambar produk</p>
            </div>
            <a href="<?= url('admin/menu_tambah.php'); ?>"
               class="flex items-center gap-2 rounded-xl bg-brand px-4 py-2.5 text-sm font-semibold text-white shadow-soft transition hover:bg-branddark">
                <i class="ph ph-plus-circle"></i> Tambah Menu
            </a>
        </div>

        <?php if (!empty($_SESSION['pesan'])): ?>
            <div class="mb-4 rounded-xl bg-success-bg px-4 py-3 text-sm text-success-fg">
                <?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="mb-4 rounded-xl bg-danger-bg px-4 py-3 text-sm text-danger-fg">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="overflow-hidden rounded-3xl bg-white shadow-soft ring-1 ring-chestnut/5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-branddark text-white">
                        <tr>
                            <th class="px-3 py-3 text-left font-semibold">No</th>
                            <th class="px-3 py-3 text-left font-semibold">Gambar</th>
                            <th class="px-3 py-3 text-left font-semibold">Nama</th>
                            <th class="px-3 py-3 text-left font-semibold">Kategori</th>
                            <th class="px-3 py-3 text-right font-semibold">Harga</th>
                            <th class="px-3 py-3 text-center font-semibold">Stok</th>
                            <th class="px-3 py-3 text-center font-semibold">Status</th>
                            <th class="px-3 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-chestnut/10">
                        <?php $no = 1; while ($m = $q->fetch_assoc()):
                            $imgUrl = menuImageUrl($m['gambar']);
                        ?>
                        <tr class="transition hover:bg-blush/60">
                            <td class="px-3 py-3"><?= $no++; ?></td>
                            <td class="px-3 py-3">
                                <div class="h-12 w-12 overflow-hidden rounded-lg bg-cream/40 ring-1 ring-chestnut/10">
                                    <?php if ($imgUrl): ?>
                                        <img src="<?= $imgUrl; ?>" alt="" class="h-full w-full object-cover">
                                    <?php else: ?>
                                        <div class="flex h-full w-full items-center justify-center text-brand/40">
                                            <i class="ph ph-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-3 py-3 font-medium"><?= htmlspecialchars($m['nama_menu']); ?></td>
                            <td class="px-3 py-3">
                                <span class="rounded-full bg-chestnut/10 px-2.5 py-0.5 text-xs font-semibold text-chestnut">
                                    <?= htmlspecialchars($m['kategori']); ?>
                                </span>
                            </td>
                            <td class="px-3 py-3 text-right"><?= rupiah($m['harga']); ?></td>
                            <td class="px-3 py-3 text-center"><?= (int)$m['stok']; ?></td>
                            <td class="px-3 py-3 text-center">
                                <?php if ($m['status'] === 'tersedia'): ?>
                                    <span class="rounded-full bg-success-bg px-2.5 py-0.5 text-xs font-semibold text-success-fg">Tersedia</span>
                                <?php else: ?>
                                    <span class="rounded-full bg-danger-bg px-2.5 py-0.5 text-xs font-semibold text-danger-fg">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex justify-center gap-1.5">
                                    <a href="<?= url('admin/menu_edit.php?id=' . (int)$m['id']); ?>"
                                       class="rounded-lg bg-info-bg p-2 text-info-fg transition hover:brightness-95" title="Edit">
                                        <i class="ph ph-pencil-simple"></i>
                                    </a>
                                    <a href="<?= url('admin/menu_hapus.php?id=' . (int)$m['id']); ?>"
                                       onclick="return confirm('Hapus menu ini?')"
                                       class="rounded-lg bg-danger-bg p-2 text-danger-fg transition hover:brightness-95" title="Hapus">
                                        <i class="ph ph-trash"></i>
                                    </a>
                                </div>
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
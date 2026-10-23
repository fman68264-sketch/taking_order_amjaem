<?php
$current = basename($_SERVER['PHP_SELF']);
$menu = [
    ['dashboard.php', 'ph-house',      'Dashboard'],
    ['menu.php',      'ph-fork-knife', 'Kelola Menu'],
    ['order.php',     'ph-receipt',    'Data Order'],
    ['laporan.php',   'ph-file-text',  'Laporan'],
];
?>
<aside id="adminSidebar" class="hidden w-60 flex-shrink-0 border-r border-chestnut/10 bg-white md:block">
    <div class="sticky top-[68px] p-4">
        <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-widest text-chestnut/40">Menu Utama</p>
        <nav class="space-y-1">
            <?php foreach ($menu as $m):
                $active = $current === $m[0]; ?>
                <a href="<?= $m[0]; ?>"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition
                   <?= $active
                        ? 'bg-brand/15 text-branddark'
                        : 'text-chestnut/70 hover:bg-blush hover:text-chestnut'; ?>">
                    <i class="ph <?= $m[1]; ?> <?= $active ? 'ph-fill' : ''; ?>" style="font-size:1.1rem"></i>
                    <?= $m[2]; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="mt-6 rounded-2xl bg-blush p-4 ring-1 ring-brand/20">
            <div class="mb-2 flex items-center gap-2">
                <i class="ph ph-info text-branddark"></i>
                <p class="text-xs font-bold text-branddark">Tips</p>
            </div>
            <p class="text-xs leading-relaxed text-chestnut/70">
                Tambahkan gambar produk di setiap menu agar pelanggan lebih tertarik memesan.
            </p>
        </div>
    </div>
</aside>
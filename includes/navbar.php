<nav class="sticky top-0 z-40 border-b border-chestnut/10 bg-white/95 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3">
        <a href="<?= url('index.php'); ?>" class="flex items-center gap-2.5">
            <?php if (logoExists()): ?>
                <img src="<?= LOGO_PATH; ?>" alt="Leaf &amp; Latte" class="h-10 w-10 rounded-xl object-cover ring-1 ring-brand/30">
            <?php else: ?>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand text-white">
                    <i class="ph-fill ph-coffee"></i>
                </span>
            <?php endif; ?>
            <div class="leading-tight">
                <p class="text-sm font-extrabold text-branddark">Leaf &amp; Latte</p>
                <p class="text-[10px] font-medium uppercase tracking-wider text-chestnut/50">Taking Order Cafe</p>
            </div>
        </a>

        <?php if (!empty($_SESSION['pelanggan_id'])): ?>
            <div class="flex items-center gap-2 sm:gap-3">
                <span class="hidden items-center gap-1.5 text-sm text-chestnut/70 sm:flex">
                    <i class="ph ph-user-circle"></i>
                    <?= htmlspecialchars($_SESSION['pelanggan_nama']); ?>
                    <span class="text-chestnut/30">&middot;</span>
                    Meja <?= htmlspecialchars($_SESSION['pelanggan_meja']); ?>
                </span>
                <a href="<?= url('pelanggan/status.php'); ?>"
                   class="hidden items-center gap-1.5 rounded-xl border border-chestnut/20 px-3 py-2 text-sm font-semibold text-chestnut transition hover:border-brand hover:text-branddark sm:flex">
                    <i class="ph ph-receipt"></i> Status
                </a>
                <a href="<?= url('pelanggan/keranjang.php'); ?>"
                   class="relative flex items-center gap-2 rounded-xl bg-brand px-3 py-2 text-sm font-semibold text-white shadow-soft transition hover:bg-branddark sm:px-4">
                    <i class="ph ph-shopping-cart"></i>
                    <span class="hidden sm:inline">Keranjang</span>
                    <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-white px-1.5 text-xs font-bold text-branddark">
                        <?= isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0; ?>
                    </span>
                </a>
                <a href="<?= url('logout_pelanggan.php'); ?>"
                   class="rounded-xl border border-chestnut/20 px-3 py-2 text-sm font-semibold text-chestnut transition hover:border-brand hover:text-branddark">
                    Selesai
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>
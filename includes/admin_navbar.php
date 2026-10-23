<nav class="sticky top-0 z-40 border-b border-chestnut/10 bg-white/95 backdrop-blur">
    <div class="flex items-center justify-between gap-3 px-4 py-3">
        <div class="flex items-center gap-3">
            <button type="button"
                    onclick="document.getElementById('adminSidebar').classList.toggle('hidden')"
                    class="rounded-lg p-2 text-chestnut transition hover:bg-chestnut/5 md:hidden"
                    aria-label="Buka menu">
                <i class="ph ph-list" style="font-size:1.25rem"></i>
            </button>
            <a href="<?= url('admin/dashboard.php'); ?>" class="flex items-center gap-2.5">
                <?php if (logoExists()): ?>
                    <img src="<?= LOGO_PATH; ?>" alt="Leaf &amp; Latte" class="h-10 w-10 rounded-xl object-cover ring-1 ring-brand/30">
                <?php else: ?>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand text-white">
                        <i class="ph-fill ph-coffee"></i>
                    </span>
                <?php endif; ?>
                <div class="leading-tight">
                    <p class="text-sm font-extrabold text-branddark">Leaf &amp; Latte</p>
                    <p class="text-[10px] font-medium uppercase tracking-wider text-chestnut/50">Admin Panel</p>
                </div>
            </a>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden items-center gap-1.5 text-sm text-chestnut/70 sm:flex">
                <i class="ph ph-user-circle"></i>
                <?= htmlspecialchars($_SESSION['admin_nama']); ?>
            </span>
            <a href="<?= url('admin/logout.php'); ?>"
               class="flex items-center gap-1.5 rounded-xl border border-chestnut/20 px-3 py-1.5 text-xs font-semibold text-chestnut transition hover:border-brand hover:text-branddark">
                <i class="ph ph-sign-out"></i> Logout
            </a>
        </div>
    </div>
</nav>
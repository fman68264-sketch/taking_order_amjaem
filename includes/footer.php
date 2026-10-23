<footer class="mt-16 border-t border-chestnut/10 bg-white">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-6 sm:flex-row">
        <div class="flex items-center gap-2">
            <?php if (logoExists()): ?>
                <img src="<?= LOGO_PATH; ?>" alt="Leaf &amp; Latte" class="h-6 w-6 rounded-md object-cover">
            <?php endif; ?>
            <span class="text-sm font-semibold text-chestnut">Leaf &amp; Latte</span>
        </div>
        <p class="text-xs text-chestnut/60">
            &copy; <?= date('Y'); ?> Leaf &amp; Latte &middot; Sistem Pemesanan Berbasis Web
        </p>
    </div>
</footer>
</body>
</html>
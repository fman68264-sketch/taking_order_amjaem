<?php
require_once '../config/koneksi.php';
if (isset($_SESSION['admin_id'])) { header('Location: ' . url('admin/dashboard.php')); exit; }
$judul = 'Login Admin - Leaf & Latte';
include '../includes/header.php';
?>

<main class="flex min-h-screen items-center justify-center bg-gradient-to-br from-chestnut via-branddark to-brand px-4">
    <div class="w-full max-w-sm rounded-3xl bg-white p-8 shadow-card">
        <div class="mb-6 text-center">
            <?php if (logoExists()): ?>
                <img src="<?= LOGO_PATH; ?>" alt="Leaf &amp; Latte"
                     class="mx-auto mb-3 h-16 w-16 rounded-2xl object-cover ring-1 ring-brand/30">
            <?php else: ?>
                <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-brand text-white">
                    <i class="ph-fill ph-shield-check" style="font-size:2rem"></i>
                </div>
            <?php endif; ?>
            <h1 class="text-xl font-extrabold text-branddark">Login Admin</h1>
            <p class="text-xs text-chestnut/50">Leaf &amp; Latte Management</p>
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="mb-4 rounded-xl bg-danger-bg px-4 py-3 text-sm text-danger-fg">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= url('admin/proses_login.php'); ?>" method="POST" class="space-y-4">
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-chestnut">Username</label>
                <div class="relative">
                    <i class="ph ph-user absolute left-3 top-1/2 -translate-y-1/2 text-chestnut/40"></i>
                    <input type="text" name="username" required
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 py-3 pl-10 pr-4 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-chestnut">Password</label>
                <div class="relative">
                    <i class="ph ph-lock absolute left-3 top-1/2 -translate-y-1/2 text-chestnut/40"></i>
                    <input type="password" name="password" required
                           class="w-full rounded-xl border border-chestnut/15 bg-blush/60 py-3 pl-10 pr-4 text-sm outline-none transition focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                </div>
            </div>
            <button type="submit"
                    class="w-full rounded-xl bg-brand py-3 text-sm font-bold text-white shadow-soft transition hover:bg-branddark active:scale-[0.99]">
                Login
            </button>
        </form>

        <div class="mt-5 text-center">
            <a href="<?= url('index.php'); ?>" class="text-xs text-chestnut/50 transition hover:text-branddark">
                Kembali ke Halaman Pelanggan
            </a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
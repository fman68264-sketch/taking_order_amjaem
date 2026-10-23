<?php
require_once 'config/koneksi.php';
$judul = 'Selamat Datang - Leaf & Latte';
include 'includes/header.php';
?>

<main class="relative flex min-h-screen items-center justify-center px-4 py-16">
    <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-b from-blush via-white to-blush"></div>

    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <?php if (logoExists()): ?>
                <img src="<?= LOGO_PATH; ?>" alt="Leaf &amp; Latte"
                     class="mx-auto h-24 w-24 rounded-3xl object-cover shadow-card ring-1 ring-brand/20">
            <?php else: ?>
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-3xl bg-brand text-white shadow-card">
                    <i class="ph-fill ph-coffee" style="font-size:3rem"></i>
                </div>
            <?php endif; ?>
            <h1 class="mt-5 text-3xl font-extrabold text-branddark">Leaf &amp; Latte</h1>
            <p class="mt-1 text-sm text-chestnut/60">Silakan isi data diri Anda untuk mulai memesan</p>
        </div>

        <div class="rounded-3xl bg-white p-8 shadow-card ring-1 ring-chestnut/5">
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="mb-4 rounded-xl bg-danger-bg px-4 py-3 text-sm text-danger-fg">
                    <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="proses/simpan_pelanggan.php" method="POST" class="space-y-5">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Nama Lengkap</label>
                    <div class="relative">
                        <i class="ph ph-user absolute left-3 top-1/2 -translate-y-1/2 text-chestnut/40"></i>
                        <input type="text" name="nama" required placeholder="Masukkan nama Anda"
                               class="w-full rounded-xl border border-chestnut/15 bg-blush/60 py-3 pl-10 pr-4 text-sm text-chestnut outline-none transition placeholder:text-chestnut/40 focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">Nomor Meja</label>
                    <div class="relative">
                        <i class="ph ph-grid-four absolute left-3 top-1/2 -translate-y-1/2 text-chestnut/40"></i>
                        <input type="text" name="no_meja" required placeholder="Contoh: A01"
                               class="w-full rounded-xl border border-chestnut/15 bg-blush/60 py-3 pl-10 pr-4 text-sm text-chestnut outline-none transition placeholder:text-chestnut/40 focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-chestnut">
                        No. HP <span class="font-normal text-chestnut/50">(opsional)</span>
                    </label>
                    <div class="relative">
                        <i class="ph ph-phone absolute left-3 top-1/2 -translate-y-1/2 text-chestnut/40"></i>
                        <input type="text" name="no_hp" placeholder="08xxxxxxxxxx"
                               class="w-full rounded-xl border border-chestnut/15 bg-blush/60 py-3 pl-10 pr-4 text-sm text-chestnut outline-none transition placeholder:text-chestnut/40 focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/30">
                    </div>
                </div>
                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-brand py-3 text-sm font-bold text-white shadow-soft transition hover:bg-branddark active:scale-[0.99]">
                    <i class="ph ph-arrow-right"></i> Mulai Pesan
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="admin/login.php"
                   class="text-xs font-medium text-chestnut/50 transition hover:text-branddark">
                    Login Admin
                </a>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
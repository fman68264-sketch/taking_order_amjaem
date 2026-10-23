<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? htmlspecialchars($judul) : 'Leaf & Latte'; ?></title>

    <link rel="icon" type="image/png" href="<?= FAVICON_PATH; ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand:      '#F192A9',
                        branddark:  '#C24D68',
                        chestnut:   '#6B3A36',
                        blush:      '#FDF4F6',
                        cream:      '#EAD0B8',
                        // Turunan status — tetap dalam palet
                        'success-bg': '#F1E3E7',
                        'success-fg': '#6B3A36',
                        'info-bg':    '#F5E6EA',
                        'info-fg':    '#C24D68',
                        'warning-bg': '#F8ECE0',
                        'warning-fg': '#8A5A2B',
                        'danger-bg':  '#F8E3E6',
                        'danger-fg':  '#A83652',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        soft: '0 4px 24px -8px rgba(107, 58, 54, 0.12)',
                        card: '0 8px 32px -12px rgba(107, 58, 54, 0.18)',
                    },
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
</head>
<body class="bg-blush font-sans text-chestnut antialiased">
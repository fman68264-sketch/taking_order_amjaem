-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2026 at 01:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `created_at`) VALUES
(1, 'admin', '$2y$10$zMQm0aaqgFcKyoMSDwmrnuDbXBzgpPdC70neFm5zigzy3TAo2IhOu', 'Administrator', '2026-10-23 07:09:37');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL COMMENT 'nama file di folder uploads/menu/',
  `status` enum('tersedia','habis') DEFAULT 'tersedia',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `stok`, `gambar`, `status`, `created_at`, `updated_at`) VALUES
(6, 'americano', 'kopi', 14000.00, 'Espresso + Air Panas', 14, 'menu_20261023172224_041bd0c9.jpg', 'tersedia', '2026-10-23 17:22:24', '2026-10-23 17:22:24'),
(8, 'coffe latte', 'kopi', 14000.00, 'Gula + susu kental manis + atau sirup rasa tambahan (seperti vanila, karamel, atau hazelnut)', 11, 'menu_20261023172600_fd90f72d.jpg', 'tersedia', '2026-10-23 17:26:00', '2026-10-23 17:45:45'),
(9, 'chicken steak', 'makanan', 23000.00, 'Daging Ayam Fillet +  Dimasak dengan cara dipanggang + Bumbu Marinasi', 49, 'menu_20261023172753_ebed6d86.jpg', 'tersedia', '2026-10-23 17:27:53', '2026-10-23 17:45:45'),
(10, 'roti bakar', 'minuman', 9000.00, 'roti + keju + susu kental manis', 24, 'menu_20261023172924_60b99c27.jpg', 'tersedia', '2026-10-23 17:29:24', '2026-10-23 17:29:24'),
(11, 'jus nanas', 'minuman', 13000.00, 'air putih + buah nanas', 45, 'menu_20261023173005_4f9e720d.jpg', 'tersedia', '2026-10-23 17:30:05', '2026-10-23 17:30:05'),
(12, 'cheesecake', 'makanan', 20000.00, 'krim keju (cream cheese) + gula + telur + susu atau krim kental (whipping cream)', 51, 'menu_20261023173332_3c89ecdf.jpg', 'tersedia', '2026-10-23 17:33:32', '2026-10-23 17:33:32'),
(13, 'jus alpukat', 'minuman', 12000.00, 'air putih + buah alpukat', 15, 'menu_20261023173508_17b18a87.jpg', 'tersedia', '2026-10-23 17:35:08', '2026-10-23 17:35:08');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `kode_order` varchar(30) NOT NULL,
  `pelanggan_id` int(11) NOT NULL,
  `no_meja` varchar(10) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status` enum('pending','diproses','selesai','dibatalkan') DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `waktu_order` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `kode_order`, `pelanggan_id`, `no_meja`, `total_harga`, `status`, `catatan`, `waktu_order`) VALUES
(1, 'ORD-20261023-174545-7B78', 1, 'jm01', 37000.00, 'selesai', '', '2026-10-23 17:45:45');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `order_id`, `menu_id`, `nama_menu`, `harga`, `jumlah`, `subtotal`) VALUES
(1, 1, 8, 'coffe latte', 14000.00, 1, 14000.00),
(2, 1, 9, 'chicken steak', 23000.00, 1, 23000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_meja` varchar(10) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `waktu_datang` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `no_meja`, `no_hp`, `waktu_datang`) VALUES
(1, 'jaemin', 'jm01', '05452251', '2026-10-23 17:45:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_admin_username` (`username`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menu_kategori` (`kategori`),
  ADD KEY `idx_menu_status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_orders_kode` (`kode_order`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_waktu` (`waktu_order`),
  ADD KEY `fk_orders_pelanggan` (`pelanggan_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detail_order` (`order_id`),
  ADD KEY `fk_detail_menu` (`menu_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pelanggan_meja` (`no_meja`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `fk_detail_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

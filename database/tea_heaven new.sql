-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2026 at 09:03 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tea_heaven`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `image` varchar(400) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `is_active`) VALUES
(1, 'Black Tea', 'black-tea', 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', 1),
(2, 'Green Tea', 'green-tea', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', 1),
(3, 'Herbal Tea', 'herbal-tea', 'https://sl.bing.net/cRvnLTwGdRk', 1),
(4, 'Oolong Tea', 'oolong-tea', 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=400', 1),
(5, 'Masala Chai', 'masala-chai', 'https://images.unsplash.com/photo-1561336313-0bd5e0b27ec8?w=400', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `order_number` varchar(30) NOT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `razorpay_order_id` varchar(100) DEFAULT NULL,
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `razorpay_signature` varchar(256) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `email` varchar(160) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `pincode` varchar(12) NOT NULL,
  `country` varchar(80) NOT NULL DEFAULT 'India',
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `payment_status`, `payment_method`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `subtotal`, `tax`, `shipping`, `discount`, `total`, `first_name`, `last_name`, `email`, `phone`, `address`, `city`, `pincode`, `country`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'TH957567C1', 'pending', 'pending', 'razorpay', 'order_SeT6RtL2s09OMG', NULL, NULL, '1914.00', '95.70', '50.00', '0.00', '2059.70', 'biki', 'Kumar Singh', 'bikikumarsingh2001@gmail.com', '07439099428', 'Ramkrishna Pur Lane', 'Shibpur', '711102', 'India', '', '2026-04-17 06:55:19', '2026-04-17 06:55:19'),
(2, 1, 'TH95E40750', 'confirmed', 'pending', 'upi', NULL, NULL, NULL, '1914.00', '95.70', '50.00', '0.00', '2059.70', 'biki', 'Kumar Singh', 'bikikumarsingh2001@gmail.com', '07439099428', 'Ramkrishna Pur Lane', 'Shibpur', '711102', 'India', '', '2026-04-17 06:55:26', '2026-04-17 06:55:26'),
(3, 1, 'TH9B46127F', 'confirmed', 'pending', 'cod', NULL, NULL, NULL, '108.00', '5.40', '50.00', '0.00', '163.40', 'biki', 'Kumar Singh', 'bikikumarsingh2001@gmail.com', '07439099428', 'Ramkrishna Pur Lane', 'Shibpur', '711102', 'India', '', '2026-04-17 06:56:52', '2026-04-17 06:56:52');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_image` varchar(400) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 2, 'Chamomile Green Tea', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', '54.00', 1, '54.00'),
(2, 1, 10, 'Peppermint Bliss', 'https://images.unsplash.com/photo-1515694346937-94d85e41e93a?w=400', '180.00', 1, '180.00'),
(3, 1, 11, 'Matcha Ceremonial', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', '599.00', 1, '599.00'),
(4, 1, 12, 'Assam Strong CTC', 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', '85.00', 1, '85.00'),
(5, 1, 9, 'Organic Rooibos', 'https://images.unsplash.com/photo-1515694346937-94d85e41e93a?w=400', '380.00', 1, '380.00'),
(6, 1, 4, 'Masala Chai', 'https://images.unsplash.com/photo-1561336313-0bd5e0b27ec8?w=400', '38.00', 1, '38.00'),
(7, 1, 3, 'Moroccan Mint Tea', 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=400', '349.00', 1, '349.00'),
(8, 1, 6, 'Darjeeling First Flush', 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', '229.00', 1, '229.00'),
(9, 2, 2, 'Chamomile Green Tea', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', '54.00', 1, '54.00'),
(10, 2, 10, 'Peppermint Bliss', 'https://images.unsplash.com/photo-1515694346937-94d85e41e93a?w=400', '180.00', 1, '180.00'),
(11, 2, 11, 'Matcha Ceremonial', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', '599.00', 1, '599.00'),
(12, 2, 12, 'Assam Strong CTC', 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', '85.00', 1, '85.00'),
(13, 2, 9, 'Organic Rooibos', 'https://images.unsplash.com/photo-1515694346937-94d85e41e93a?w=400', '380.00', 1, '380.00'),
(14, 2, 4, 'Masala Chai', 'https://images.unsplash.com/photo-1561336313-0bd5e0b27ec8?w=400', '38.00', 1, '38.00'),
(15, 2, 3, 'Moroccan Mint Tea', 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=400', '349.00', 1, '349.00'),
(16, 2, 6, 'Darjeeling First Flush', 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', '229.00', 1, '229.00'),
(17, 3, 2, 'Chamomile Green Tea', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', '54.00', 2, '108.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 100,
  `image` varchar(400) DEFAULT NULL,
  `badge` varchar(40) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `sale_price`, `stock`, `image`, `badge`, `is_active`, `created_at`) VALUES
(1, 1, 'Aam Panna Iced Tea', 'aam-panna-iced-tea', 'Refreshing mango-infused black iced tea.', '120.00', '99.00', 50, 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', 'Sale', 1, '2026-04-17 10:12:55'),
(2, 2, 'Chamomile Green Tea', 'chamomile-green-tea', 'Soothing chamomile blended with green tea.', '54.00', NULL, 80, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', 'Popular', 1, '2026-04-17 10:12:55'),
(3, 4, 'Moroccan Mint Tea', 'moroccan-mint-tea', 'Bold mint aroma, Moroccan style.', '400.00', '349.00', 30, 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=400', 'Sale', 1, '2026-04-17 10:12:55'),
(4, 5, 'Masala Chai', 'masala-chai', 'Classic Indian spiced milk tea blend.', '38.00', NULL, 100, 'https://images.unsplash.com/photo-1561336313-0bd5e0b27ec8?w=400', NULL, 1, '2026-04-17 10:12:55'),
(5, 1, 'Earl Grey', 'earl-grey', 'Premium bergamot-infused black tea.', '450.00', NULL, 40, 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=400', NULL, 1, '2026-04-17 10:12:55'),
(6, 1, 'Darjeeling First Flush', 'darjeeling-first-flush', 'Muscatel notes, early spring harvest.', '270.00', '229.00', 60, 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', 'Sale', 1, '2026-04-17 10:12:55'),
(7, 2, 'Japanese Sencha', 'japanese-sencha', 'Umami-rich steamed Japanese green tea.', '200.00', NULL, 70, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', 'New', 1, '2026-04-17 10:12:55'),
(8, 4, 'Ti Guan Yin Oolong', 'ti-guan-yin-oolong', 'Chinese partially oxidised oolong classic.', '490.00', NULL, 25, 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=400', NULL, 1, '2026-04-17 10:12:55'),
(9, 3, 'Organic Rooibos', 'organic-rooibos', 'South African caffeine-free herbal tea.', '420.00', '380.00', 55, 'https://images.unsplash.com/photo-1515694346937-94d85e41e93a?w=400', 'Sale', 1, '2026-04-17 10:12:55'),
(10, 3, 'Peppermint Bliss', 'peppermint-bliss', 'Pure peppermint leaves, caffeine-free.', '180.00', NULL, 90, 'https://images.unsplash.com/photo-1515694346937-94d85e41e93a?w=400', NULL, 1, '2026-04-17 10:12:55'),
(11, 2, 'Matcha Ceremonial', 'matcha-ceremonial', 'Grade-A ceremonial matcha from Uji, Japan.', '650.00', '599.00', 20, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', 'Popular', 1, '2026-04-17 10:12:55'),
(12, 1, 'Assam Strong CTC', 'assam-strong-ctc', 'Bold, brisk Assam CTC best with milk.', '85.00', NULL, 120, 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', NULL, 1, '2026-04-17 10:12:55');

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(30) NOT NULL,
  `discount_type` enum('flat','percent') NOT NULL DEFAULT 'percent',
  `discount_value` decimal(8,2) NOT NULL,
  `min_order` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_uses` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `expires_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `promo_codes`
--

INSERT INTO `promo_codes` (`id`, `code`, `discount_type`, `discount_value`, `min_order`, `max_uses`, `used_count`, `expires_at`, `is_active`) VALUES
(1, 'WELCOME10', 'percent', '10.00', '200.00', 500, 0, NULL, 1),
(2, 'FLAT50', 'flat', '50.00', '300.00', 200, 0, NULL, 1),
(3, 'TEA20', 'percent', '20.00', '500.00', 100, 0, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `google_id` varchar(120) DEFAULT NULL,
  `facebook_id` varchar(120) DEFAULT NULL,
  `avatar` varchar(400) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pincode` varchar(12) DEFAULT NULL,
  `country` varchar(80) DEFAULT 'India',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `google_id`, `facebook_id`, `avatar`, `phone`, `address`, `city`, `pincode`, `country`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'biki ', 'bikikumarsingh2001@gmail.com', '$2y$10$GgIMk5Pho3OnMMjWgiJJ7ePQ4KOZBwKMjgKlp/PVVCnOMRY0ZlGUS', NULL, NULL, NULL, '07439099428', 'Ramkrishna Pur Lane', 'Shibpur', '711102', 'India', 1, '2026-04-17 04:54:21', '2026-04-17 04:54:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_session` (`session_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_google` (`google_id`),
  ADD KEY `idx_facebook` (`facebook_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

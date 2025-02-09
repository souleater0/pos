-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2025 at 01:10 AM
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
-- Database: `tako_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Main Dish'),
(2, 'Addons');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `discount_id` int(11) NOT NULL,
  `discount_name` varchar(255) DEFAULT NULL,
  `discount_percentage` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`discount_id`, `discount_name`, `discount_percentage`) VALUES
(1, '0%', 0),
(2, '5%', 5),
(3, '10%', 10),
(4, '15%', 15),
(6, '20%', 20);

-- --------------------------------------------------------

--
-- Table structure for table `ingredient`
--

CREATE TABLE `ingredient` (
  `ingredient_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) DEFAULT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `warn_qty` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredient`
--

INSERT INTO `ingredient` (`ingredient_id`, `ingredient_name`, `price_per_unit`, `warn_qty`, `unit_id`, `created_at`, `updated_at`) VALUES
(1, 'Batter Flour', 20.00, 20, 5, '2025-02-05 09:18:15', '2025-02-06 18:51:52'),
(2, 'Cheese Bits', 20.00, 10, 3, '2025-02-05 09:18:18', '2025-02-06 08:17:53'),
(3, 'Octopus Bits', 20.00, 10, 3, '2025-02-05 09:18:20', '2025-02-06 15:01:56'),
(4, 'Kewpie Mayo', 20.00, 10, 3, '2025-02-05 09:18:22', '2025-02-06 08:17:48'),
(5, 'Takoyaki Sauce', 20.00, 10, 3, '2025-02-05 09:18:24', '2025-02-06 08:17:48'),
(6, 'Seaweed Flakes', 20.00, 10, 3, '2025-02-05 09:18:26', '2025-02-06 08:17:48'),
(7, 'Cooking Oil', 20.00, 10, 3, '2025-02-05 09:21:21', '2025-02-06 08:17:48'),
(8, 'Styrofoam Box', 1.00, 10, 3, '2025-02-05 09:21:24', '2025-02-06 12:53:00'),
(9, 'Cheese Sauce', 20.00, 10, 3, '2025-02-05 09:21:25', '2025-02-06 08:17:48'),
(10, 'Grated Cheese', 20.00, 10, 3, '2025-02-05 09:21:27', '2025-02-06 08:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_batch`
--

CREATE TABLE `ingredient_batch` (
  `batch_id` int(11) NOT NULL,
  `ingredient_id` int(11) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredient_batch`
--

INSERT INTO `ingredient_batch` (`batch_id`, `ingredient_id`, `barcode`, `quantity`, `expiry_date`, `created_at`, `updated_at`) VALUES
(4, 1, 'AWT', 8, '0000-00-00', '2025-02-06 12:46:18', '2025-02-06 12:46:28'),
(5, 1, NULL, 3, '2025-02-07', '2025-02-06 12:47:59', '2025-02-06 12:47:59'),
(6, 1, NULL, 2, '2025-02-15', '2025-02-06 12:48:09', '2025-02-06 12:48:09');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_waste`
--

CREATE TABLE `ingredient_waste` (
  `waste_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) DEFAULT NULL,
  `ingredient_price` decimal(10,2) DEFAULT NULL,
  `quantity_wasted` decimal(10,2) DEFAULT NULL,
  `units` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `reported_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `module_name`, `description`) VALUES
(1, 'Dashboard', NULL),
(2, 'POS', 'done'),
(3, 'Receipt', NULL),
(4, 'Products', 'done'),
(5, 'Ingredients', NULL),
(6, 'Categories', 'done'),
(7, 'Units', 'done'),
(8, 'Discounts', 'done'),
(9, 'Waste', NULL),
(10, 'Report', NULL),
(11, 'User', NULL),
(12, 'Role', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_type`
--

CREATE TABLE `payment_type` (
  `payment_id` int(11) NOT NULL,
  `payment_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_type`
--

INSERT INTO `payment_type` (`payment_id`, `payment_name`) VALUES
(1, 'Cash'),
(2, 'Gcash');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission_name`, `description`, `module_id`) VALUES
(1, 'manage_dashboard', 'MANAGE', 1),
(2, 'manage_pos', 'MANAGE', 2),
(3, 'manage_receipt', 'MANAGE', 3),
(4, 'void_receipt', 'VOID', 3),
(5, 'manage_product', 'MANAGE', 4),
(6, 'create_product', 'CREATE', 4),
(7, 'update_product', 'UPDATE', 4),
(8, 'delete_product', 'DELETE', 4),
(9, 'manage_ingredient', 'MANAGE', 5),
(10, 'create_ingredient', 'CREATE', 5),
(11, 'update_ingredient', 'UPDATE', 5),
(12, 'delete_ingredient', 'DELETE', 5),
(13, 'manage_category', 'MANAGE', 6),
(14, 'create_category', 'CREATE', 6),
(15, 'update_category', 'UPDATE', 6),
(16, 'delete_category', 'DELETE', 6),
(17, 'manage_unit', 'MANAGE', 7),
(18, 'create_unit', 'CREATE', 7),
(19, 'update_unit', 'UPDATE', 7),
(20, 'delete_unit', 'DELETE', 7),
(21, 'manage_discount', 'MANAGE', 8),
(22, 'create_discount', 'CREATE', 8),
(23, 'update_discount', 'UPDATE', 8),
(24, 'delete_discount', 'DELETE', 8),
(25, 'manage_waste', 'MANAGE', 9),
(26, 'create_waste', 'CREATE', 9),
(27, 'update_waste', 'UPDATE', 9),
(28, 'delete_waste', 'DELETE', 9),
(29, 'manage_report', 'MANAGE', 10),
(30, 'manage_user', 'MANAGE', 11),
(31, 'create_user', 'CREATE', 11),
(32, 'update_user', 'UPDATE', 11),
(33, 'delete_user', 'DELETE', 11),
(34, 'manage_role', 'MANAGE', 12),
(35, 'create_role', 'CREATE', 12),
(36, 'update_role', 'UPDATE', 12),
(37, 'delete_role', 'DELETE', 12);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_discount` int(11) NOT NULL DEFAULT 0,
  `product_image` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_price`, `product_discount`, `product_image`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bacon Cheesy Takoyaki', 105.00, 0, 'assets/images/products/Bacon Cheesy Takoyaki.jpg', 1, 0, '2025-02-04 15:21:56', '2025-02-05 09:40:57'),
(2, 'Ultimate Cheesy Takoyaki', 85.00, 0, 'assets/images/products/Ultimate Cheesy.jpg', 1, 0, '2025-02-04 15:21:57', '2025-02-05 09:38:49'),
(3, 'Baby Octo Takoyaki', 120.00, 0, 'assets/images/products/Baby Octo Takoyaki.jpg', 1, 0, '2025-02-04 15:22:00', '2025-02-05 09:40:57'),
(4, 'Authentic Takoyaki', 65.00, 0, 'assets/images/products/Authentic Takoyaki.jpg', 1, 0, '2025-02-04 15:22:01', '2025-02-05 09:38:16'),
(5, 'Octobits', 15.00, 0, 'assets/images/products/Octo_Bits.png', 2, 0, '2025-02-04 15:22:06', '2025-02-06 17:36:16'),
(6, 'Cheese Sauce', 15.00, 0, 'assets/images/products/Cheese_Sauce.png', 2, 0, '2025-02-04 15:22:12', '2025-02-06 17:35:29'),
(7, 'Kewpie Sauce', 15.00, 0, 'assets/images/products/Kewpie_Sauce.png', 2, 0, '2025-02-04 15:22:46', '2025-02-06 17:35:37'),
(8, 'Takoyaki Sauce', 15.00, 0, 'assets/images/products/Takoyaki_Sauce.png', 2, 0, '2025-02-04 15:22:47', '2025-02-06 17:35:42'),
(9, 'Cheese Bits', 15.00, 0, 'assets/images/products/cheese_Bits.png', 2, 0, '2025-02-04 15:22:50', '2025-02-06 17:35:48');

-- --------------------------------------------------------

--
-- Table structure for table `product_waste`
--

CREATE TABLE `product_waste` (
  `waste_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `quantity_wasted` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Admin'),
(5, 'Cashier');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(5, 2),
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `system_option`
--

CREATE TABLE `system_option` (
  `id` int(11) NOT NULL,
  `option_name` varchar(255) DEFAULT NULL,
  `option_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_option`
--

INSERT INTO `system_option` (`id`, `option_name`, `option_value`, `created_at`, `updated_at`) VALUES
(1, 'Tax', '1.5', '2024-07-19 04:29:28', '2025-02-06 08:47:47'),
(2, 'store_address', 'Sto. Nino Plaridel, Bulacan', '2025-02-08 19:36:41', '2025-02-08 19:37:07'),
(3, 'store_contact', '09392887055', '2025-02-08 19:36:58', '2025-02-08 19:37:11');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `cashier_name` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `transaction_paid` decimal(10,2) NOT NULL,
  `transaction_subtotal` decimal(10,2) NOT NULL,
  `transaction_discount` decimal(10,2) NOT NULL,
  `transaction_change` decimal(10,2) NOT NULL,
  `transaction_grandtotal` decimal(10,2) NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `invoice_no`, `cashier_name`, `customer_name`, `payment_type`, `transaction_paid`, `transaction_subtotal`, `transaction_discount`, `transaction_change`, `transaction_grandtotal`, `transaction_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'TKY00001', 'admin', 'Joe Mama', 'Cash', 500.00, 502.00, 25.10, 23.10, 476.90, '2025-02-09 06:46:06', 0, '2025-02-08 22:46:06', '2025-02-09 00:07:52'),
(2, 'TKY00002', 'admin', 'Marites', 'Cash', 300.00, 225.00, 0.00, 75.00, 225.00, '2025-02-09 06:51:43', 0, '2025-02-08 22:51:43', '2025-02-08 22:51:43'),
(3, 'TKY00003', 'admin', 'N/A', 'Cash', 500.00, 502.00, 25.10, 23.10, 476.90, '2025-02-09 07:08:27', 0, '2025-02-08 23:08:27', '2025-02-08 23:08:27'),
(4, 'TKY00004', 'admin', 'Spider Man', 'Cash', 500.00, 502.00, 25.10, 23.10, 476.90, '2025-02-09 07:26:50', 0, '2025-02-08 23:26:50', '2025-02-08 23:26:50'),
(5, 'TKY00005', 'admin', 'Jake', 'Cash', 250.00, 225.00, 0.00, 25.00, 225.00, '2025-02-09 07:38:00', 0, '2025-02-08 23:38:00', '2025-02-08 23:38:00');

-- --------------------------------------------------------

--
-- Table structure for table `transactions_item`
--

CREATE TABLE `transactions_item` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_qty` int(11) DEFAULT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `item_discount_amt` decimal(10,2) NOT NULL,
  `item_discount_percentage` int(11) NOT NULL,
  `item_subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions_item`
--

INSERT INTO `transactions_item` (`id`, `transaction_id`, `item_name`, `item_qty`, `item_price`, `item_discount_amt`, `item_discount_percentage`, `item_subtotal`, `created_at`) VALUES
(1, 1, 'Baby Octo Takoyaki', 2, 120.00, 12.00, 5, 228.00, '2025-02-08 22:46:06'),
(2, 1, 'Bacon Cheesy Takoyaki', 2, 105.00, 21.00, 10, 189.00, '2025-02-08 22:46:06'),
(3, 1, 'Ultimate Cheesy Takoyaki', 1, 85.00, 0.00, 0, 85.00, '2025-02-08 22:46:06'),
(4, 2, 'Baby Octo Takoyaki', 1, 120.00, 0.00, 0, 120.00, '2025-02-08 22:51:43'),
(5, 2, 'Bacon Cheesy Takoyaki', 1, 105.00, 0.00, 0, 105.00, '2025-02-08 22:51:43'),
(6, 3, 'Baby Octo Takoyaki', 2, 120.00, 12.00, 5, 228.00, '2025-02-08 23:08:27'),
(7, 3, 'Bacon Cheesy Takoyaki', 2, 105.00, 21.00, 10, 189.00, '2025-02-08 23:08:27'),
(8, 3, 'Ultimate Cheesy Takoyaki', 1, 85.00, 0.00, 0, 85.00, '2025-02-08 23:08:27'),
(9, 4, 'Baby Octo Takoyaki', 2, 120.00, 12.00, 5, 228.00, '2025-02-08 23:26:50'),
(10, 4, 'Bacon Cheesy Takoyaki', 2, 105.00, 21.00, 10, 189.00, '2025-02-08 23:26:50'),
(11, 4, 'Ultimate Cheesy Takoyaki', 1, 85.00, 0.00, 0, 85.00, '2025-02-08 23:26:50'),
(12, 5, 'Baby Octo Takoyaki', 1, 120.00, 0.00, 0, 120.00, '2025-02-08 23:38:00'),
(13, 5, 'Bacon Cheesy Takoyaki', 1, 105.00, 0.00, 0, 105.00, '2025-02-08 23:38:00');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `unit_id` int(11) NOT NULL,
  `unit_type` varchar(255) DEFAULT NULL,
  `short_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`unit_id`, `unit_type`, `short_name`) VALUES
(1, 'Pieces', 'pcs'),
(2, 'Set', 'set'),
(3, 'Pack', 'pks'),
(4, 'Box', 'bx'),
(5, 'Kilogram', 'kgs'),
(6, 'Ounce', 'oz'),
(7, 'Pair', 'pr'),
(8, 'Slice', 'slc'),
(9, 'Pair', 'pr'),
(10, 'Grams', 'gr'),
(11, 'Liters', 'L');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `isEnabled` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `display_name`, `role_id`, `isEnabled`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$Yvn5wPKG1D1ovcMe5wM2lemqHaX40cKBJ7ybknP0rtYFkVSYt0MmK', 'Jerome De Lara', 1, 1, '2024-05-08 12:30:37', '2024-08-12 03:45:55'),
(19, 'cash', '$2y$10$.3Gx1hTxGEWw4rYM7KtHue2IBlYADXiygNO.VyK1o18t7i3Ua5hY2', 'cash', 5, 1, '2025-02-06 22:36:35', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`discount_id`) USING BTREE;

--
-- Indexes for table `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `ingredient_batch`
--
ALTER TABLE `ingredient_batch`
  ADD PRIMARY KEY (`batch_id`),
  ADD KEY `ingredient_batch_ibfk_1` (`ingredient_id`);

--
-- Indexes for table `ingredient_waste`
--
ALTER TABLE `ingredient_waste`
  ADD PRIMARY KEY (`waste_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`payment_id`) USING BTREE;

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fr_moduleid` (`module_id`),
  ADD KEY `permission_name` (`permission_name`) USING BTREE;

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_prod_category` (`category_id`);

--
-- Indexes for table `product_waste`
--
ALTER TABLE `product_waste`
  ADD PRIMARY KEY (`waste_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `fr_permission` (`permission_id`);

--
-- Indexes for table `system_option`
--
ALTER TABLE `system_option`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `transactions_item`
--
ALTER TABLE `transactions_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_item_ibfk_1` (`transaction_id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fr_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `ingredient_batch`
--
ALTER TABLE `ingredient_batch`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ingredient_waste`
--
ALTER TABLE `ingredient_waste`
  MODIFY `waste_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_waste`
--
ALTER TABLE `product_waste`
  MODIFY `waste_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_option`
--
ALTER TABLE `system_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions_item`
--
ALTER TABLE `transactions_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingredient`
--
ALTER TABLE `ingredient`
  ADD CONSTRAINT `ingredient_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`unit_id`);

--
-- Constraints for table `ingredient_batch`
--
ALTER TABLE `ingredient_batch`
  ADD CONSTRAINT `ingredient_batch_ibfk_1` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient` (`ingredient_id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `fr_moduleid` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_prod_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fr_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fr_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions_item`
--
ALTER TABLE `transactions_item`
  ADD CONSTRAINT `transactions_item_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fr_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

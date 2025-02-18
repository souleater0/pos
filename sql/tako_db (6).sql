-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2025 at 08:35 AM
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
(21, 1, 'AWTS', 5, '0000-00-00', '2025-02-09 23:58:59', '2025-02-10 14:49:42'),
(22, 1, NULL, 5, '0000-00-00', '2025-02-10 14:49:41', '2025-02-10 14:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_waste`
--

CREATE TABLE `ingredient_waste` (
  `waste_id` int(11) NOT NULL,
  `ingredient_barcode` varchar(255) DEFAULT NULL,
  `ingredient_name` varchar(255) DEFAULT NULL,
  `ingredient_price` decimal(10,2) DEFAULT NULL,
  `quantity_wasted` decimal(10,2) DEFAULT NULL,
  `units` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `reported_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredient_waste`
--

INSERT INTO `ingredient_waste` (`waste_id`, `ingredient_barcode`, `ingredient_name`, `ingredient_price`, `quantity_wasted`, `units`, `reason`, `reported_by`, `created_at`, `updated_at`) VALUES
(24, NULL, 'Batter Flour', 20.00, 5.00, 'kgs', 'Rejects', 'Jerome', '2025-02-10 00:01:11', '2025-02-10 00:12:41'),
(25, 'AWTS', 'Batter Flour', 20.00, 1.00, 'kgs', 'Dirty', 'Jerome', '2025-02-10 00:01:11', '2025-02-10 00:16:57'),
(26, 'AWTS', 'Batter Flour', 20.00, 2.00, 'kgs', 'Reject Item', 'Jerome', '2025-02-10 14:45:10', '2025-02-10 14:45:10');

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
(9, 'Product Waste', NULL),
(10, 'Ingredient Waste', NULL),
(11, 'Report', NULL),
(12, 'User', NULL),
(13, 'Role', NULL);

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
(25, 'manage_product_waste', 'MANAGE', 9),
(26, 'create_product_waste', 'CREATE', 9),
(27, 'update_product_waste', 'UPDATE', 9),
(28, 'delete_product_waste', 'DELETE', 9),
(29, 'manage_ingredient_waste', 'MANAGE', 10),
(30, 'create_ingredient_waste', 'CREATE', 10),
(31, 'update_ingredient_waste', 'UPDATE', 10),
(32, 'delete_ingredient_waste', 'DELETE', 10),
(33, 'manage_report', 'MANAGE', 11),
(34, 'manage_user', 'MANAGE', 12),
(35, 'create_user', 'CREATE', 12),
(36, 'update_user', 'UPDATE', 12),
(37, 'delete_user', 'DELETE', 12),
(38, 'manage_role', 'MANAGE', 13),
(39, 'create_role', 'CREATE', 13),
(40, 'update_role', 'UPDATE', 13),
(41, 'delete_role', 'DELETE', 13);

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
  `reported_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_waste`
--

INSERT INTO `product_waste` (`waste_id`, `product_name`, `product_price`, `quantity_wasted`, `reason`, `reported_by`, `created_at`, `updated_at`) VALUES
(2, 'Baby Octo Takoyaki', 120.00, 2, 'Went to the Sea', 'Jerome', '2025-02-09 18:59:11', '2025-02-10 00:12:30'),
(3, 'Bacon Cheesy Takoyaki', 105.00, 1, 'Over Cooked', 'Jerome', '2025-02-09 19:51:48', '2025-02-10 00:12:32');

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
(1, 38),
(1, 39),
(1, 40),
(1, 41),
(5, 2),
(5, 3),
(5, 33);

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
-- Table structure for table `trail_logs`
--

CREATE TABLE `trail_logs` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_action` varchar(255) DEFAULT NULL,
  `user_module` varchar(255) DEFAULT NULL,
  `user_detail` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trail_logs`
--

INSERT INTO `trail_logs` (`id`, `user_name`, `user_action`, `user_module`, `user_detail`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Login', 'Authentication', 'User logged in successfully', '2025-02-18 06:17:14', '2025-02-18 06:17:14'),
(2, 'admin', 'Sale Processed', 'POS', 'Transaction added with <span class=\"badge bg-success\">Invoice No: TKY00006</span>', '2025-02-18 07:25:27', '2025-02-18 07:28:17'),
(3, 'admin', 'Sale Processed', 'POS', 'Transaction added with <span class=\"badge bg-success\">Invoice No: TKY00007</span>', '2025-02-18 07:28:41', '2025-02-18 07:28:41'),
(4, 'admin', 'Logout', 'Authentication', 'User has logged out', '2025-02-18 07:28:58', '2025-02-18 07:28:58');

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
(1, 'TKY00001', 'admin', 'N/A', 'Cash', 400.00, 310.00, 0.00, 90.00, 310.00, '2025-02-10 11:37:33', 0, '2025-02-10 03:37:33', '2025-02-10 16:56:49'),
(2, 'TKY00002', 'admin', 'Joe', 'Gcash', 500.00, 502.00, 25.10, 23.10, 476.90, '2025-02-11 02:43:04', 0, '2025-02-10 18:43:04', '2025-02-10 18:43:04'),
(3, 'TKY00003', 'admin', 'N/A', 'Cash', 100.00, 85.00, 0.00, 15.00, 85.00, '2025-02-11 02:43:36', 0, '2025-02-10 18:43:36', '2025-02-10 18:43:36'),
(4, 'TKY00004', 'admin', 'N/A', 'Cash', 200.00, 190.00, 0.00, 10.00, 190.00, '2025-02-18 13:56:58', 0, '2025-02-18 05:56:58', '2025-02-18 05:56:58'),
(5, 'TKY00005', 'admin', 'N/A', 'Cash', 200.00, 190.00, 0.00, 10.00, 190.00, '2025-02-18 14:12:47', 0, '2025-02-18 06:12:47', '2025-02-18 06:12:47'),
(6, 'TKY00006', 'admin', 'N/A', 'Cash', 200.00, 190.00, 0.00, 10.00, 190.00, '2025-02-18 15:25:27', 0, '2025-02-18 07:25:27', '2025-02-18 07:25:27'),
(7, 'TKY00007', 'admin', 'N/A', 'Cash', 100.00, 65.00, 0.00, 35.00, 65.00, '2025-02-18 15:28:41', 0, '2025-02-18 07:28:41', '2025-02-18 07:28:41');

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
(1, 1, 'Baby Octo Takoyaki', 1, 120.00, 0.00, 0, 120.00, '2025-02-10 03:37:33'),
(2, 1, 'Bacon Cheesy Takoyaki', 1, 105.00, 0.00, 0, 105.00, '2025-02-10 03:37:33'),
(3, 1, 'Ultimate Cheesy Takoyaki', 1, 85.00, 0.00, 0, 85.00, '2025-02-10 03:37:33'),
(4, 2, 'Baby Octo Takoyaki', 2, 120.00, 12.00, 5, 228.00, '2025-02-10 18:43:04'),
(5, 2, 'Bacon Cheesy Takoyaki', 2, 105.00, 21.00, 10, 189.00, '2025-02-10 18:43:04'),
(6, 2, 'Ultimate Cheesy Takoyaki', 1, 85.00, 0.00, 0, 85.00, '2025-02-10 18:43:04'),
(7, 3, 'Ultimate Cheesy Takoyaki', 1, 85.00, 0.00, 0, 85.00, '2025-02-10 18:43:36'),
(8, 4, 'Bacon Cheesy Takoyaki', 1, 105.00, 0.00, 0, 105.00, '2025-02-18 05:56:58'),
(9, 4, 'Ultimate Cheesy Takoyaki', 1, 85.00, 0.00, 0, 85.00, '2025-02-18 05:56:58'),
(10, 5, 'Bacon Cheesy Takoyaki', 1, 105.00, 0.00, 0, 105.00, '2025-02-18 06:12:47'),
(11, 5, 'Ultimate Cheesy Takoyaki', 1, 85.00, 0.00, 0, 85.00, '2025-02-18 06:12:47'),
(12, 6, 'Bacon Cheesy Takoyaki', 1, 105.00, 0.00, 0, 105.00, '2025-02-18 07:25:27'),
(13, 6, 'Ultimate Cheesy Takoyaki', 1, 85.00, 0.00, 0, 85.00, '2025-02-18 07:25:27'),
(14, 7, 'Authentic Takoyaki', 1, 65.00, 0.00, 0, 65.00, '2025-02-18 07:28:41');

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
(1, 'admin', '$2y$10$Yvn5wPKG1D1ovcMe5wM2lemqHaX40cKBJ7ybknP0rtYFkVSYt0MmK', 'Jerome', 1, 1, '2024-05-08 12:30:37', '2025-02-10 00:12:18'),
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
-- Indexes for table `trail_logs`
--
ALTER TABLE `trail_logs`
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
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `ingredient_waste`
--
ALTER TABLE `ingredient_waste`
  MODIFY `waste_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_waste`
--
ALTER TABLE `product_waste`
  MODIFY `waste_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `trail_logs`
--
ALTER TABLE `trail_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transactions_item`
--
ALTER TABLE `transactions_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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

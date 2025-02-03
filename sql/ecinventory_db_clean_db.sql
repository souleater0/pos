-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 03:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

CREATE DATABASE IF NOT EXISTS tako_db;
USE tako_db;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecinventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `parent_category_id` int(11) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `category_prefix` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `customer_phone_no` varchar(13) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `customer_name`, `customer_email`, `company_name`, `customer_phone_no`, `created_at`, `updated_at`) VALUES
(1, 'ESKINA', 'ecxdev03@gmail.com', NULL, NULL, '2024-10-30 04:44:44', '2024-11-22 08:56:27'),
(2, 'EC CAFE BALIUAG', 'test1@gmail.com', NULL, NULL, '2024-10-30 04:45:32', '2024-10-30 10:13:48'),
(3, 'ECSTATICS', 'test2@gmail.com', NULL, NULL, '2024-10-30 04:45:38', '2024-10-30 10:14:04'),
(4, 'ECXPERIENCE', 'test3@gmail.com', NULL, NULL, '2024-10-30 04:46:56', '2024-10-30 10:14:04'),
(5, 'MATS DONUT FOOD HOUSE', 'test4@gmail.com', NULL, NULL, '2024-10-30 04:47:18', '2024-10-30 10:14:04'),
(6, 'EC SOLUTIONS BPO', 'test5@gmail.com', NULL, NULL, '2024-10-30 04:47:28', '2024-10-30 10:14:04'),
(7, 'BAZAAR & EVENTS', 'test6@gmail.com', NULL, NULL, '2024-10-30 04:47:40', '2024-10-30 10:14:04');

-- --------------------------------------------------------

--
-- Table structure for table `file_upload`
--

CREATE TABLE `file_upload` (
  `id` int(11) NOT NULL,
  `transaction_no` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
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
(1, 'Dashboard Management', NULL),
(2, 'Product Management', NULL),
(3, 'Category Management', NULL),
(4, 'Brand Management', NULL),
(5, 'Unit Management', NULL),
(6, 'Tax Management', NULL),
(7, 'Stock Management', NULL),
(9, 'Paybills', NULL),
(10, 'Supplier Management', NULL),
(11, 'Costing Management', NULL),
(12, 'Customer Management', NULL),
(13, 'User Management', NULL),
(14, 'Role Management', NULL),
(16, 'Inventory Stock Report', NULL),
(17, 'Stock Valuation Report', NULL),
(18, 'Stock Movement Report', NULL),
(19, 'Product History Report', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `transaction_no` varchar(255) DEFAULT NULL,
  `payment_refno` varchar(255) DEFAULT NULL,
  `payment_account` int(11) DEFAULT NULL,
  `payment_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_account`
--

CREATE TABLE `payment_account` (
  `id` int(11) NOT NULL,
  `pay_account_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_account`
--

INSERT INTO `payment_account` (`id`, `pay_account_name`) VALUES
(1, 'Cash in Bank-Unionbank'),
(2, 'Cash in Bank-Metro Bank'),
(3, 'Cash on Hand'),
(4, 'Petty Cash Fund'),
(5, 'Change Fund'),
(6, 'Gcash');

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
(2, 'show_product', 'SHOW', 2),
(3, 'manage_product', 'MANAGE', 2),
(4, 'create_product', 'CREATE', 2),
(5, 'update_product', 'UPDATE', 2),
(6, 'delete_product', 'DELETE', 2),
(7, 'manage_category', 'MANAGE', 3),
(8, 'create_category', 'CREATE', 3),
(9, 'update_category', 'UPDATE', 3),
(10, 'delete_category', 'DELETE', 3),
(11, 'manage_brand', 'MANAGE', 4),
(12, 'create_brand', 'CREATE', 4),
(13, 'update_brand', 'UPDATE', 4),
(14, 'delete_brand', 'DELETE', 4),
(15, 'manage_units', 'MANAGE', 5),
(16, 'create_units', 'CREATE', 5),
(17, 'update_units', 'UPDATE', 5),
(18, 'delete_units', 'DELETE', 5),
(19, 'manage_tax', 'MANAGE', 6),
(20, 'create_tax', 'CREATE', 6),
(21, 'update_tax', 'UPDATE', 6),
(22, 'delete_tax', 'DELETE', 6),
(23, 'manage_costing', 'MANAGE', 11),
(24, 'update_costing', 'UPDATE', 11),
(25, 'manage_supplier', 'MANAGE', 10),
(26, 'create_supplier', 'CREATE', 10),
(27, 'update_supplier', 'UPDATE', 10),
(28, 'delete_supplier', 'DELETE', 10),
(29, 'manage_customer', 'MANAGE', 12),
(30, 'create_customer', 'CREATE', 12),
(31, 'update_customer', 'UPDATE', 12),
(32, 'delete_customer', 'DELETE', 12),
(33, 'manage_transaction', 'MANAGE', 7),
(34, 'view_transaction', 'VIEW', 7),
(35, 'void_transaction', 'VOID', 7),
(36, 'create_transaction', 'CREATE', 7),
(37, 'update_transaction', 'UPDATE', 7),
(38, 'delete_transaction', 'DELETE', 7),
(39, 'pay_bills', 'PAY BILL', 7),
(40, 'create_bill', 'CREATE BILL', 7),
(41, 'create_expense', 'CREATE EXPENSE', 7),
(42, 'create_invoice', 'CREATE INVOICE', 7),
(43, 'manage_inventory_stock', 'MANAGE', 16),
(44, 'manage_stock_valuation', 'MANAGE', 17),
(45, 'manage_stock_movement', 'MANAGE', 18),
(46, 'manage_product_history', 'MANAGE', 19),
(47, 'manage_user', 'MANAGE', 13),
(48, 'create_user', 'CREATE', 13),
(49, 'update_user', 'UPDATE', 13),
(50, 'delete_user', 'DELETE', 13),
(51, 'manage_role', 'MANAGE', 14),
(52, 'create_role', 'CREATE', 14),
(53, 'update_role', 'UPDATE', 14),
(54, 'delete_role', 'DELETE', 14);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `product_pp` decimal(11,2) NOT NULL DEFAULT 0.00,
  `product_sp` decimal(11,2) NOT NULL DEFAULT 0.00,
  `product_min` int(11) DEFAULT NULL,
  `product_max` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `expiry_notice` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `receipt_no` varchar(255) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `receipt_type` enum('PURCHASE','BILL','') DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
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
(2, 'Purchasing'),
(3, 'Accountant');

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
(1, 42),
(1, 43),
(1, 44),
(1, 45),
(1, 46),
(1, 47),
(1, 48),
(1, 49),
(1, 50),
(1, 51),
(1, 52),
(1, 53),
(1, 54),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 7),
(2, 8),
(2, 9),
(2, 11),
(2, 12),
(2, 13),
(2, 15),
(2, 16),
(2, 17),
(2, 19),
(2, 20),
(2, 21),
(2, 25),
(2, 26),
(2, 27),
(2, 29),
(2, 30),
(2, 31),
(2, 33),
(2, 34),
(2, 36),
(2, 37),
(2, 39),
(2, 40),
(2, 41),
(2, 42),
(2, 43),
(2, 44),
(2, 45),
(2, 46);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`) VALUES
(1, 'In Stock'),
(2, 'Low Stock'),
(3, 'Out of Stock');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `vendor_company` varchar(255) DEFAULT NULL,
  `vendor_name` varchar(255) DEFAULT NULL,
  `vendor_address` varchar(255) DEFAULT NULL,
  `vendor_contact` varchar(255) DEFAULT NULL,
  `vendor_email` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `vendor_company`, `vendor_name`, `vendor_address`, `vendor_contact`, `vendor_email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Company A', 'Vendor A', 'Sto. Cristo Pulilan, Bulacan', '09123456789', 'company@gmail.com', 1, '2024-08-21 05:46:37', '2024-08-21 08:34:54'),
(2, 'Company B', 'Vendor B', 'Baliuag, Bulacan', '09123456789', 'company@gmail.com', 1, '2024-08-21 05:46:44', '2024-08-21 08:35:07'),
(3, 'Company C', 'Vendor C', 'Sto. Cristo Pulilan, Bulacan', '09123456789', 'company@gmail.com', 1, '2024-08-21 05:46:51', '2024-08-21 08:34:57'),
(4, 'Company D', 'Vendor D', 'Sto. Cristo Pulilan, Bulacan', '09123456789', 'company@gmail.com', 1, '2024-08-21 05:55:01', '2024-08-21 08:34:57'),
(5, 'Company E', 'Vendor E', 'Sto. Cristo Pulilan, Bulacan', '09123456789', 'company@gmail.com', 0, '2024-08-21 05:58:47', '2024-08-21 08:34:57');

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
(1, 'void_card', 'X7PL2C9K4QW8R5T1VJ6Z', '2024-07-19 04:29:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tax`
--

CREATE TABLE `tax` (
  `tax_id` int(11) NOT NULL,
  `tax_name` varchar(255) DEFAULT NULL,
  `tax_percentage` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tax`
--

INSERT INTO `tax` (`tax_id`, `tax_name`, `tax_percentage`) VALUES
(1, 'No Tax', 0),
(2, '1% Tax', 1),
(3, '3% Tax', 3),
(4, '5% Tax', 5),
(5, '10% Tax', 10),
(6, '4% Tax', 4);

-- --------------------------------------------------------

--
-- Stand-in structure for view `transaction_summary`
-- (See below for the actual view)
--
CREATE TABLE `transaction_summary` (
`Date` date
,`Type` varchar(7)
,`No` varchar(255)
,`Payee` varchar(255)
,`Total` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `trans_bill`
--

CREATE TABLE `trans_bill` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `bill_address` varchar(255) DEFAULT NULL,
  `bill_date` date DEFAULT NULL,
  `bill_due_date` date DEFAULT NULL,
  `bill_no` varchar(255) DEFAULT NULL,
  `transaction_no` varchar(255) NOT NULL,
  `tax_type` int(11) NOT NULL DEFAULT 3,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sales_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `payment_status` enum('paid','unpaid','partial') NOT NULL DEFAULT 'unpaid',
  `remarks` varchar(255) DEFAULT NULL,
  `isVoid` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trans_expense`
--

CREATE TABLE `trans_expense` (
  `id` int(11) NOT NULL,
  `payee_id` int(11) DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `expense_payment_method` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sales_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `expense_no` varchar(255) DEFAULT NULL,
  `transaction_no` varchar(255) DEFAULT NULL,
  `tax_type` int(11) NOT NULL DEFAULT 3,
  `remarks` varchar(255) DEFAULT NULL,
  `isVoid` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trans_invoice`
--

CREATE TABLE `trans_invoice` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `invoice_bill_address` varchar(255) DEFAULT NULL,
  `invoice_shipping_address` varchar(255) DEFAULT NULL,
  `invoice_ship_via` varchar(255) DEFAULT NULL,
  `invoice_ship_date` date DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `invoice_duedate` date DEFAULT NULL,
  `invoice_track_no` varchar(255) DEFAULT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `transaction_no` varchar(255) DEFAULT NULL,
  `tax_type` int(11) NOT NULL DEFAULT 3,
  `payment_status` enum('paid','notdeposited','deposited') DEFAULT 'paid',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sales_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) UNSIGNED NOT NULL DEFAULT 0.00,
  `remarks` varchar(255) DEFAULT NULL,
  `isVoid` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trans_item`
--

CREATE TABLE `trans_item` (
  `item_id` int(11) NOT NULL,
  `transaction_no` varchar(255) DEFAULT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `item_barcode` varchar(255) DEFAULT NULL,
  `item_qty` int(11) DEFAULT NULL,
  `item_rate` decimal(10,2) DEFAULT NULL,
  `item_tax` decimal(10,0) DEFAULT NULL,
  `item_amount` decimal(10,2) DEFAULT NULL,
  `transaction_type` enum('bill','expense','invoice','purchase_order') DEFAULT NULL,
  `item_expiry` date DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(10, 'Grams', 'gr');

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
(3, 'jhondell', '$2y$10$JRSUatHPUFuxnBTk2t1PzeHke3itwMQXgGiKJrvWT.55bKynetknq', 'Jhondell', 3, 1, '2024-05-22 07:11:58', '2024-08-07 06:30:26'),
(4, 'purchase', '$2y$10$fu8HEOtJ/Jq2yVXmua8meeAFO3i04W.V6lcj.jdoMXfkHhn1/KWC.', 'Purchasing', 2, 0, '2024-07-26 10:24:19', '2024-08-27 03:09:17'),
(6, 'johannie', '$2y$10$EGuMfwsxUqEbjRIR7OTDI.n/48v6pzHrlHlBmjYuSVjmHg2/cCg9O', 'johannie', 2, 1, '2024-11-28 01:47:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `waste`
--

CREATE TABLE `waste` (
  `waste_id` int(11) NOT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_pp` int(11) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `item_barcode` varchar(255) DEFAULT NULL,
  `item_qty` int(11) DEFAULT NULL,
  `item_expiry` date DEFAULT NULL,
  `waste_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste`
--

INSERT INTO `waste` (`waste_id`, `product_sku`, `product_name`, `product_pp`, `category_name`, `item_barcode`, `item_qty`, `item_expiry`, `waste_reason`, `created_at`) VALUES
(1, 'SN00002', 'BREAD PAN CHEESE & ONION 24G', 15, 'Snacks', '4800194153225', 5, '2024-08-07', 'expired item', '2024-08-08 21:36:00');

-- --------------------------------------------------------

--
-- Structure for view `transaction_summary`
--
DROP TABLE IF EXISTS `transaction_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `transaction_summary`  AS SELECT CASE WHEN `b`.`bill_date` is not null THEN `b`.`bill_date` WHEN `e`.`expense_date` is not null THEN `e`.`expense_date` WHEN `i`.`invoice_date` is not null THEN `i`.`invoice_date` END AS `Date`, CASE WHEN `b`.`id` is not null THEN 'Bill' WHEN `e`.`id` is not null THEN 'Expense' WHEN `i`.`id` is not null THEN 'Invoice' END AS `Type`, CASE WHEN `b`.`bill_no` is not null THEN `b`.`bill_no` WHEN `e`.`expense_no` is not null THEN `e`.`expense_no` WHEN `i`.`invoice_no` is not null THEN `i`.`invoice_no` END AS `No`, CASE WHEN `b`.`supplier_id` is not null THEN `s`.`vendor_name` WHEN `e`.`payee_id` is not null THEN `s`.`vendor_name` WHEN `i`.`customer_id` is not null THEN `c`.`customer_name` END AS `Payee`, sum(`ti`.`item_amount`) AS `Total` FROM (((((`trans_item` `ti` left join `trans_bill` `b` on(`ti`.`transaction_no` = `b`.`transaction_no`)) left join `trans_expense` `e` on(`ti`.`transaction_no` = `e`.`transaction_no`)) left join `trans_invoice` `i` on(`ti`.`transaction_no` = `i`.`transaction_no`)) left join `supplier` `s` on(`b`.`supplier_id` = `s`.`id` or `e`.`payee_id` = `s`.`id`)) left join `customer` `c` on(`i`.`customer_id` = `c`.`id`)) GROUP BY CASE WHEN `b`.`bill_date` is not null THEN `b`.`bill_date` WHEN `e`.`expense_date` is not null THEN `e`.`expense_date` WHEN `i`.`invoice_date` is not null THEN `i`.`invoice_date` END, CASE WHEN `b`.`id` is not null THEN 'Bill' WHEN `e`.`id` is not null THEN 'Expense' WHEN `i`.`id` is not null THEN 'Invoice' END, CASE WHEN `b`.`bill_no` is not null THEN `b`.`bill_no` WHEN `e`.`expense_no` is not null THEN `e`.`expense_no` WHEN `i`.`invoice_no` is not null THEN `i`.`invoice_no` END, CASE WHEN `b`.`supplier_id` is not null THEN `s`.`vendor_name` WHEN `e`.`payee_id` is not null THEN `s`.`vendor_name` WHEN `i`.`customer_id` is not null THEN `c`.`customer_name` END ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_upload`
--
ALTER TABLE `file_upload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_account`
--
ALTER TABLE `payment_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_name` (`permission_name`),
  ADD KEY `fr_moduleid` (`module_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fr_brand_id` (`brand_id`),
  ADD KEY `fr_category_id` (`category_id`),
  ADD KEY `fr_status_id` (`status_id`),
  ADD KEY `fr_unit_id` (`unit_id`),
  ADD KEY `fr_tax_id` (`tax_id`),
  ADD KEY `product_sku` (`product_sku`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_option`
--
ALTER TABLE `system_option`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax`
--
ALTER TABLE `tax`
  ADD PRIMARY KEY (`tax_id`);

--
-- Indexes for table `trans_bill`
--
ALTER TABLE `trans_bill`
  ADD PRIMARY KEY (`id`,`grand_total`) USING BTREE;

--
-- Indexes for table `trans_expense`
--
ALTER TABLE `trans_expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trans_invoice`
--
ALTER TABLE `trans_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trans_item`
--
ALTER TABLE `trans_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fr_prod_sku` (`product_sku`);

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
-- Indexes for table `waste`
--
ALTER TABLE `waste`
  ADD PRIMARY KEY (`waste_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `file_upload`
--
ALTER TABLE `file_upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_account`
--
ALTER TABLE `payment_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_option`
--
ALTER TABLE `system_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trans_bill`
--
ALTER TABLE `trans_bill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_expense`
--
ALTER TABLE `trans_expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_invoice`
--
ALTER TABLE `trans_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_item`
--
ALTER TABLE `trans_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `waste`
--
ALTER TABLE `waste`
  MODIFY `waste_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `fr_moduleid` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fr_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fr_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fr_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

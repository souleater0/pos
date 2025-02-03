-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 09:53 AM
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

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`brand_id`, `brand_name`) VALUES
(1, 'Great Taste'),
(2, 'Arla'),
(3, 'RMMM Meat Trading'),
(4, 'Susan And Willy'),
(5, 'Excel');

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

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `parent_category_id`, `category_name`, `category_prefix`) VALUES
(1, NULL, 'MEAT', 'MEAT'),
(2, 1, 'PORK', 'PORK'),
(3, 1, 'BEEF', 'BEEF'),
(4, NULL, 'SEAFOODS', 'SEAFOODS'),
(5, NULL, 'DAIRY', 'DAIRY'),
(6, NULL, 'NON-ALCOHOLIC', 'NALCOHOL'),
(7, NULL, 'GROCERIES', 'GROCERIES'),
(8, NULL, 'CLEANING SUPPLIES', 'CSUPP'),
(9, NULL, 'RESTAURANT SUPPLIES', 'RSUPP'),
(10, NULL, 'KITCHEN SUPPLIES', 'KSUPP'),
(11, NULL, 'PACKAGING SUPPLIES', 'PSUPPLIES'),
(12, 1, 'Poultry', 'poultry'),
(13, NULL, 'Alcoholic', 'Alcoholic'),
(14, NULL, 'Fruits', 'Fr'),
(15, NULL, 'Vegetables', 'Veg');

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
(16, 'Summary Report', NULL),
(17, 'Inventory Stock Report', NULL),
(18, 'Stock Valuation Report', NULL),
(19, 'Stock Movement Report', NULL),
(20, 'Product History Report', NULL);

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
(43, 'manage_summary_report', 'MANAGE', 16),
(44, 'manage_inventory_stock', 'MANAGE', 17),
(45, 'manage_stock_valuation', 'MANAGE', 18),
(46, 'manage_stock_movement', 'MANAGE', 19),
(47, 'manage_product_history', 'MANAGE', 20),
(48, 'manage_user', 'MANAGE', 13),
(49, 'create_user', 'CREATE', 13),
(50, 'update_user', 'UPDATE', 13),
(51, 'delete_user', 'DELETE', 13),
(52, 'manage_role', 'MANAGE', 14),
(53, 'create_role', 'CREATE', 14),
(54, 'update_role', 'UPDATE', 14),
(55, 'delete_role', 'DELETE', 14);

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

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_description`, `brand_id`, `category_id`, `status_id`, `product_sku`, `product_pp`, `product_sp`, `product_min`, `product_max`, `unit_id`, `tax_id`, `expiry_notice`, `created_at`, `updated_at`) VALUES
(1, 'Tokong', 'Tokong', 3, 2, NULL, 'PORK00001', 120.00, 132.00, 20, 40, 5, 1, 90, '2025-01-14 02:30:03', '2025-01-14 06:35:19'),
(2, 'Isaw', 'Isaw', 3, 2, NULL, 'PORK00002', 122.00, 0.00, 20, 40, 5, 1, 30, '2025-01-14 02:32:27', '2025-01-14 02:34:11'),
(3, 'Pork Ears', 'Pork Ears', 3, 2, NULL, 'PORK00003', 150.00, 0.00, 20, 40, 5, 1, 90, '2025-01-14 02:33:44', NULL),
(4, 'Hipon', 'Hipon', 4, 4, NULL, 'SEAFOODS00001', 420.00, 0.00, 20, 40, 5, 1, 60, '2025-01-14 03:46:45', NULL),
(5, 'Beef Shortplate - US', 'Beef Shortplate - US', 5, 1, NULL, 'BEEF00001', 510.00, 0.00, 20, 40, 5, 1, 150, '2025-01-14 10:11:05', '2025-01-14 10:12:36'),
(6, 'Toyomansi Sachet', 'Toyomansi Sachet', 5, 9, NULL, 'RSUPP00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-16 07:42:18', '2025-01-16 07:43:03'),
(7, 'Marshmallow  Tiny 1kg', 'Marshmallow  Tiny ', 5, 7, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-16 08:06:26', '2025-01-16 08:06:54'),
(8, 'Puratos 1kg', 'Puratos 1kg', 5, 7, NULL, 'GROCERIES00002', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:24:56', NULL),
(9, 'Curing Salt - pink 1kg ', 'Curing Salt - pink 1kg ', 5, 10, NULL, 'KSUPP00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-16 08:25:48', NULL),
(10, 'Tapioca Pearl 1kg ', 'Tapioca Pearl 1kg ', 5, 7, NULL, 'GROCERIES00003', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:27:31', NULL),
(11, 'Cheese Soft Serve Ice Cream 1kg', 'Cheese Soft Serve Ice Cream 1kg', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:29:20', NULL),
(12, 'Chocolate Top Serve 1kg ', 'Chocolate Top Serve 1kg ', 5, 7, NULL, 'GROCERIES00005', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:30:18', NULL),
(13, 'Vanilla Top Serve 1kg ', 'Vanilla Top Serve 1kg ', 5, 6, NULL, 'GROCERIES00006', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:31:57', '2025-01-17 03:23:11'),
(14, 'Caramel Syrup Bubbles 1kg', 'Caramel Syrup Bubbles 1kg', 5, 7, NULL, 'GROCERIES00007', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:33:06', NULL),
(15, 'Strawberry Syrup Bubbles 1kg', 'Strawberry Syrup Bubbles 1kg', 5, 7, NULL, 'GROCERIES00008', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:36:45', NULL),
(16, 'Caramel Easy Brand', 'Caramel Easy Brand', 5, 7, NULL, 'GROCERIES00009', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:41:37', NULL),
(17, 'White Beans - bottle', 'White Beans - bottle', 5, 7, NULL, 'GROCERIES00010', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:42:25', NULL),
(18, 'Nata de Coco - Red', 'Nata de Coco - Red', 5, 7, NULL, 'GROCERIES00011', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:44:45', NULL),
(19, 'Whippit 1kg', 'Whippit 1kg', 5, 7, NULL, 'GROCERIES00012', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:45:18', NULL),
(20, 'Kawami Matcha Powder ', 'Kawami Matcha Powder ', 5, 6, NULL, 'NALCOHOL00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:46:12', NULL),
(21, 'Eden Cheese 430gms', 'Eden Cheese 430gms', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:48:35', NULL),
(22, 'Long Beach Cucumber 740ml ', 'Long Beach Cucumber 740ml ', 5, 6, NULL, 'NALCOHOL00002', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:49:35', NULL),
(23, 'Long Beach Lemon w/ Honey 740ml ', 'Long Beach Lemon w/ Honey 740ml ', 5, 6, NULL, 'NALCOHOL00003', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:50:31', NULL),
(24, 'Long Beach Hazelnut 740ml ', 'Long Beach Hazelnut 740ml ', 5, 6, NULL, 'NALCOHOL00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:51:11', NULL),
(25, 'Long Beach Sakura 740ml ', 'Long Beach Sakura 740ml ', 5, 6, NULL, 'NALCOHOL00005', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:51:44', NULL),
(26, 'Mustard seed whole - sampler', 'Mustard seed whole - sampler', 5, 10, NULL, 'KSUPP00002', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:52:23', NULL),
(27, 'Long Beach Caramel 740ml syrup', 'Long Beach Caramel 740ml syrup', 5, 6, NULL, 'NALCOHOL00006', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:53:11', NULL),
(28, 'Mat\'s Coffee Beans', 'Mat\'s Coffee Beans', 5, 6, NULL, 'NALCOHOL00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-16 08:54:07', NULL),
(29, 'Mr. Gulaman Black Color', 'Mr. Gulaman Black Color', 5, 7, NULL, 'GROCERIES00013', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:54:50', NULL),
(30, 'White Rose Vanilla ', 'White Rose Vanilla ', 5, 7, NULL, 'GROCERIES00014', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:55:15', NULL),
(31, 'Marlboro Red', 'Marlboro Red', 5, 7, NULL, 'GROCERIES00015', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:55:56', NULL),
(32, 'Marlboro Lights', 'Marlboro Lights', 5, 7, NULL, 'GROCERIES00016', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:56:44', NULL),
(33, 'Marlboro Blue', 'Marlboro Blue', 5, 7, NULL, 'GROCERIES00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:57:51', NULL),
(34, 'Long Beach Strawberry 900ml sauce', 'Long Beach Strawberry 900ml sauce', 5, 6, NULL, 'GROCERIES00018', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:58:42', '2025-01-16 08:59:33'),
(35, 'Long Beach Caramel 900ml sauce', 'Long Beach Caramel 900ml sauce', 5, 6, NULL, 'NALCOHOL00008', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 08:59:17', NULL),
(36, 'Long Beach White Chocolate 900ml sauce', 'Long Beach White Chocolate 900ml sauce', 5, 6, NULL, 'NALCOHOL00009', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:00:14', NULL),
(37, 'Long Beach Dark  Chocolate 900ml sauce', 'Long Beach Dark  Chocolate 900ml sauce', 5, 6, NULL, 'NALCOHOL00010', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:00:59', NULL),
(38, 'Long Beach Mulberry Sauce 900ml ', 'Long Beach Mulberry Sauce 900ml ', 5, 6, NULL, 'NALCOHOL00011', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:01:55', NULL),
(39, 'Long Beach Matcha Sauce', 'Long Beach Matcha Sauce', 5, 6, NULL, 'NALCOHOL00012', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:02:44', NULL),
(40, 'Great Taste Coffee 50gms ', 'Great Taste Coffee 50gms ', 5, 7, NULL, 'GROCERIES00018', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:03:21', NULL),
(41, 'Muscovado Sugar 1kg', 'Muscovado Sugar 1kg', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:04:32', NULL),
(42, 'Dutch cocoa powder 250gms', 'Dutch cocoa powder 250gms', 5, 7, NULL, 'GROCERIES00020', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:05:27', NULL),
(43, 'Lily\'s Peanut Butter Paste 1kg', 'Lily\'s Peanut Butter Paste 1kg', 5, 7, NULL, 'GROCERIES00021', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:06:10', NULL),
(44, 'DLA Fondant Black', 'DLA Fondant Black', 5, 7, NULL, 'GROCERIES00022', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:07:10', '2025-01-16 09:08:05'),
(45, 'DLA Fondant White', 'DLA Fondant White', 5, 7, NULL, 'GROCERIES00023', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:07:45', '2025-01-16 09:08:15'),
(46, 'DLA Fondant Red', 'DLA Fondant Red', 5, 7, NULL, 'GROCERIES00024', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:08:45', NULL),
(47, 'Maraschino Cherries ', 'Maraschino Cherries ', 5, 7, NULL, 'GROCERIES00025', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:09:38', NULL),
(48, 'YS Langka ', 'YS Langka ', 5, 7, NULL, 'GROCERIES00026', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:10:23', NULL),
(49, 'YS Red Mongo Beans', 'YS Red Mongo Beans', 5, 7, NULL, 'GROCERIES00027', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:11:25', NULL),
(50, 'YS Green Kaong ', 'YS Green Kaong ', 5, 7, NULL, 'GROCERIES00028', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:12:02', NULL),
(51, 'YS White Kaong ', 'YS White Kaong ', 5, 7, NULL, 'GROCERIES00029', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:12:44', NULL),
(52, 'Peotraco Glucose', 'Peotraco Glucose', 5, 7, NULL, 'GROCERIES00030', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:13:26', NULL),
(53, 'Long Beach Vanilla Frapped 400gms', 'Long Beach Vanilla Frapped 400gms', 5, 6, NULL, 'NALCOHOL00013', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:14:59', NULL),
(54, 'Long Beach Assam Black Tea', 'Long Beach Assam Black Tea', 5, 6, NULL, 'NALCOHOL00014', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:16:20', NULL),
(55, 'Today\'s Mixes Fruits', 'Today\'s Mixes Fruits', 5, 7, NULL, 'GROCERIES00031', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:16:55', '2025-01-16 09:19:30'),
(56, 'Sunbest Strawberry in can', 'Sunbest Strawberry in can', 5, 7, NULL, 'GROCERIES00032', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:18:18', NULL),
(57, 'Sunbest Blueberry in can', 'Sunbest Blueberry in can', 5, 7, NULL, 'GROCERIES00033', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:19:02', NULL),
(58, 'DLA Red Cherry', 'DLA Red Cherry', 5, 7, NULL, 'GROCERIES00034', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:20:22', NULL),
(59, 'DLA Raspberry', 'DLA Raspberry', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:20:47', NULL),
(60, 'Cacao Tablea', 'Cacao Tablea', 5, 7, NULL, 'GROCERIES00036', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-16 09:21:17', '2025-01-16 09:21:39'),
(61, 'Graham Biscuit Crackers', 'Graham Biscuit Crackers', 5, 7, NULL, 'GROCERIES00037', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:22:16', NULL),
(62, 'Iodized salt 1kg', 'Iodized salt 1kg', 5, 7, NULL, 'GROCERIES00038', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:22:44', NULL),
(63, 'Red Horse in can', 'Red Horse in can', 5, 13, NULL, 'GROCERIES00039', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:23:19', NULL),
(64, 'Coke in can regular', 'Coke in can regular', 5, 6, NULL, 'NALCOHOL00015', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:23:49', NULL),
(65, 'Pineapple Juice Teatra pack 1L', 'Pineapple Juice Teatra pack 1L', 5, 7, NULL, 'GROCERIES00039', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:25:17', NULL),
(66, 'Kitkat minis', 'Kitkat minis', 5, 7, NULL, 'GROCERIES00040', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-16 09:25:42', '2025-01-16 09:30:17'),
(67, 'Biscoff spread 400gms ', 'Biscoff spread 400gms ', 5, 7, NULL, 'GROCERIES00041', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:26:08', NULL),
(68, 'Ground cinnamon 200gms', 'Ground cinnamon 200gms', 5, 7, NULL, 'GROCERIES00042', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:26:39', NULL),
(69, 'Flavored Ube 500gms', 'Flavored Ube 500gms', 5, 7, NULL, 'GROCERIES00043', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:28:10', NULL),
(70, 'Kitkat Spread', 'Kitkat Spread', 5, 7, NULL, 'GROCERIES00044', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:28:39', NULL),
(71, 'Century Tuna 420gms ', 'Century Tuna 420gms ', 5, 7, NULL, 'GROCERIES00045', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:29:10', NULL),
(72, 'Oreo biscuits ', 'Oreo biscuits ', 5, 7, NULL, 'GROCERIES00046', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:30:00', NULL),
(73, 'Arla Fresh Milk', 'Arla Fresh Milk', 5, 5, NULL, 'DAIRY00002', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-16 09:30:56', NULL),
(74, 'Mocafe Javachips powder', 'Mocafe Javachips powder', 5, 6, NULL, 'NALCOHOL00016', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:48:11', NULL),
(75, 'Mocafe Matcha Powder', 'Mocafe Matcha Powder', 5, 6, NULL, 'NALCOHOL00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:49:02', NULL),
(76, 'Sliced Almonds', 'Sliced Almonds', 5, 7, NULL, 'NALCOHOL00018', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:49:35', NULL),
(77, 'Gravy mix 550gms', 'Gravy mix 550gms', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:50:25', NULL),
(78, 'Klass Honey', 'Klass Honey', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:51:45', NULL),
(79, 'Ajinomoto umami seasoning 1kg', 'Ajinomoto umami seasoning 1kg', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:52:22', NULL),
(80, 'Potassium Sorbate', 'Potassium Sorbate', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:54:29', NULL),
(81, 'Maggi Chicken Powder 1kg', 'Maggi Chicken Powder 1kg', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:55:26', NULL),
(82, 'Maggi Beed Powder 1kg ', 'Maggi Beed Powder 1kg ', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:55:57', NULL),
(83, 'Rostip Chicken Seasoning Powder ', 'Rostip Chicken Seasoning Powder ', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:56:35', NULL),
(84, 'Demi glace brolon sauce 1kg', 'Demi glace brolon sauce 1kg', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:57:11', NULL),
(85, 'Datu puti Vinegar 1L', 'Datu puti Vinegar 1L', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 09:57:38', NULL),
(86, 'Dark Soy sauce - mushroom', 'Dark Soy sauce - mushroom', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-16 09:58:39', '2025-01-17 04:49:08'),
(87, 'Knorr Beef  Cubes in tub ', 'Knorr Beef  Cubes in tub ', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:00:38', NULL),
(88, 'Knorr Chicken  Cubes in tub ', 'Knorr Chicken  Cubes in tub ', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:01:04', NULL),
(89, 'Knorr Pork  Cubes in tub ', 'Knorr Pork  Cubes in tub ', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:01:38', '2025-01-20 03:12:29'),
(90, 'Gold Cup Oyster Sauce ', 'Gold Cup Oyster Sauce ', 5, 7, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:06:54', NULL),
(91, 'Datu puti Soy Sauce 3.7L', 'Datu puti Soy Sauce 3.7L', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:09:54', NULL),
(92, 'Knorr Sinigang mix ', 'Knorr Sinigang mix ', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:10:52', NULL),
(93, 'Knorr Seasoning Liquid 1L', 'Knorr Seasoning Liquid 1L', 5, 7, NULL, 'MEAT00001', 11.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:15:51', NULL),
(94, 'Reno liver spread 230gms ', 'Reno liver spread 230gms ', 5, 7, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:16:39', NULL),
(95, 'Whole corn kernel', 'Whole corn kernel', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:17:24', NULL),
(96, 'Cream Style Corn', 'Cream Style Corn', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:18:36', NULL),
(97, 'Ketchup in sachet', 'Ketchup in sachet', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:20:32', NULL),
(98, 'Torani Salted Caramel Syrup', 'Torani Salted Caramel Syrup', 5, 6, NULL, 'NALCOHOL00018', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:21:27', NULL),
(99, 'Torani French Vanilla Syrup', 'Torani French Vanilla Syrup', 5, 7, NULL, 'GROCERIES00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:22:28', NULL),
(100, 'Torani Caramel Syrup', 'Torani Caramel Syrup', 5, 6, NULL, 'NALCOHOL00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:23:33', NULL),
(101, 'Torani Vanilla Syrup', 'Torani Vanilla Syrup', 5, 6, NULL, 'NALCOHOL00020', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:24:21', NULL),
(102, 'Finest Call Lime', 'Finest Call Lime', 5, 6, NULL, 'NALCOHOL00021', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:24:48', NULL),
(103, 'Finest Call Blue Curacao', 'Finest Call Blue Curacao', 5, 6, NULL, 'NALCOHOL00022', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:25:20', NULL),
(104, 'Finest Call Triple Sec', 'Finest Call Triple Sec', 5, 6, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:26:42', NULL),
(105, 'Torani White Chocolate Sauce', 'Torani White Chocolate Sauce', 5, 6, NULL, 'NALCOHOL00023', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:27:33', NULL),
(106, 'Torani Caramel Sauce', 'Torani Caramel Sauce', 5, 6, NULL, 'NALCOHOL00024', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:28:22', NULL),
(107, 'Torani Dark Chocolate Sauce', 'Torani Dark Chocolate Sauce', 5, 6, NULL, 'NALCOHOL00025', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:29:08', NULL),
(108, 'Sunquick Lemon', 'Sunquick Lemon', 5, 6, NULL, 'NALCOHOL00026', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:30:04', NULL),
(109, 'Sunquick Orange', 'Sunquick Orange', 5, 6, NULL, 'NALCOHOL00027', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:30:39', NULL),
(110, 'Pineapple Juice in can 1.36L', 'Pineapple Juice in can 1.36L', 5, 6, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:35:21', NULL),
(111, 'Mango Puree', 'Mango Puree', 5, 6, NULL, 'NALCOHOL00028', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:35:52', NULL),
(112, 'Calamansi Puree', 'Calamansi Puree', 5, 6, NULL, 'NALCOHOL00029', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:36:29', NULL),
(113, 'Grenadine Walsh Brand', 'Grenadine Walsh Brand', 5, 6, NULL, 'NALCOHOL00030', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:37:10', '2025-01-16 10:37:34'),
(114, 'Finest Call Grenadine ', 'Finest Call Grenadine ', 5, 6, NULL, 'NALCOHOL00031', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:39:44', NULL),
(115, 'Finest Call Lime Sour', 'Finest Call Lime Sour', 5, 6, NULL, 'NALCOHOL00032', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:40:38', NULL),
(116, 'Club Mix Lime Juice', 'Club Mix Lime Juice', 5, 6, NULL, 'NALCOHOL00033', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:41:20', NULL),
(117, 'Lihia ', 'Lihia ', 5, 7, NULL, 'NALCOHOL00034', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:42:04', '2025-01-16 10:42:44'),
(118, 'Sesame Oil 2.1L', 'Sesame Oil 2.1L', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:43:28', NULL),
(119, 'Milin Peppermint', 'Milin Peppermint', 5, 6, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:44:50', NULL),
(120, 'Milin Melon', 'Milin Melon', 5, 6, NULL, 'NALCOHOL00034', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:45:28', NULL),
(121, 'Milin Lemon', 'Milin Lemon', 5, 6, NULL, 'NALCOHOL00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:46:26', NULL),
(122, 'Milin Lychee', 'Milin Lychee', 5, 6, NULL, 'NALCOHOL00036', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:47:19', NULL),
(123, 'Milin Green Apple', 'Milin Green Apple', 5, 6, NULL, 'NALCOHOL00037', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:47:50', NULL),
(124, 'Milin Watermelon', 'Milin Watermelon', 5, 6, NULL, 'NALCOHOL00038', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:49:44', NULL),
(125, 'Milin Passion Fruit', 'Milin Passion Fruit', 5, 6, NULL, 'NALCOHOL00039', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:50:24', NULL),
(126, 'Milin Mango', 'Milin Mango', 5, 6, NULL, 'NALCOHOL00040', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:50:53', NULL),
(127, 'Milin Strawberry', 'Milin Strawberry', 5, 6, NULL, 'NALCOHOL00041', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:51:43', NULL),
(128, 'Magic Sarap Seasoning 150gms granules', 'Magic Sarap Seasoning 150gms granules', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:52:25', NULL),
(129, 'Don Garcia Blanco 1L', 'Don Garcia Blanco 1L', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:52:56', NULL),
(130, 'Don Garcia Tinto 1L', 'Don Garcia Tinto 1L', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:53:37', NULL),
(131, 'Pineapple Tidbits', 'Pineapple Tidbits', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:53:59', NULL),
(132, 'Pineapple Sliced', 'Pineapple Sliced', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:54:29', NULL),
(133, 'Mr. Gulaman Red', 'Mr. Gulaman Red', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:55:01', NULL),
(134, 'Green Peas 230gms', 'Green Peas 230gms', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:55:29', NULL),
(135, 'Molinera Red kidney beans', 'Molinera Red kidney beans', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:56:13', NULL),
(136, 'Jolly Peach Halves', 'Jolly Peach Halves', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:56:44', NULL),
(137, 'Rogers Mayonnaise 3.5L', 'Rogers Mayonnaise 3.5L', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-16 10:57:15', NULL),
(138, 'Coffee Espresso - Easy ', 'Coffee Espresso - Easy ', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-17 04:46:28', NULL),
(139, 'Brown Sugar', 'Brown Sugar', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-17 04:49:47', NULL),
(140, 'Lady\'s Choice Mayonnaise 3.5L', 'Lady\'s Choice Mayonnaise 3.5L', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:14:11', NULL),
(141, 'Everwhip', 'Everwhip', 5, 5, NULL, 'DAIRY00003', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:14:37', NULL),
(142, 'Arla Mozzarella Cheese Block 2.3kgs', 'Arla Mozzarella Cheese Block 2.3kgs', 5, 5, NULL, 'DAIRY00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:15:16', NULL),
(143, 'Canaprole Mozzarella Cheese 5kg', 'Canaprole Mozzarella Cheese 5kg', 5, 5, NULL, 'DAIRY00005', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:16:03', '2025-01-20 02:16:30'),
(144, 'Magnolia Quickmelt Cheese 1.9kg', 'Magnolia Quickmelt Cheese 1.9kg', 5, 5, NULL, 'DAIRY00006', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:17:13', NULL),
(145, 'Magnolia Cheese Sauce in Jar 2.5kg', 'Magnolia Cheese Sauce in Jar 2.5kg', 5, 5, NULL, 'DAIRY00007', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:17:42', NULL),
(146, 'Showa Tempura Batter 700gms', 'Showa Tempura Batter 700gms', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:19:14', NULL),
(147, 'Peacan Nuts 500gms', 'Peacan Nuts 500gms', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:20:01', NULL),
(148, 'Arabica Coffee Bean Original', 'Arabica Coffee Bean Original', 5, 6, NULL, 'NALCOHOL00042', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:20:36', NULL),
(149, 'Arabica Coffee Bean Floral', 'Arabica Coffee Bean Floral', 5, 6, NULL, 'NALCOHOL00043', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:21:01', NULL),
(150, 'Cumin Powder 1kg', 'Cumin Powder 1kg', 5, 10, NULL, 'KSUPP00003', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:21:45', NULL),
(151, 'Sweet Baby Rays BBQ Sauce 1.3kgs', 'Sweet Baby Rays BBQ Sauce 1.3kgs', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:22:32', NULL),
(152, 'Gochujang Paste', 'Gochujang Paste', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:23:06', NULL),
(153, 'Mustard Mccormick Powder 3.4kg', 'Mustard Mccormick Powder 3.4kg', 5, 10, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:23:49', NULL),
(154, 'Siracha 455ml', 'Siracha 455ml', 5, 10, NULL, 'KSUPP00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:24:14', NULL),
(155, 'Tabasco 150ml', 'Tabasco 150ml', 5, 10, NULL, 'KSUPP00005', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:25:00', NULL),
(156, 'Pistachio Nuts 500gms', 'Pistachio Nuts 500gms', 5, 10, NULL, 'KSUPP00006', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:25:31', NULL),
(157, 'Dona Elena Diced Tomatoes 2.5kg', 'Dona Elena Diced Tomatoes 2.5kg', 5, 7, NULL, 'KSUPP00007', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:26:21', NULL),
(158, 'CIAO Diced Tomatoes 2.5kg', 'CIAO Diced Tomatoes 2.5kg', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:26:56', NULL),
(159, 'Chili Flakes 1kg', 'Chili Flakes 1kg', 5, 10, NULL, 'KSUPP00007', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:27:20', NULL),
(160, 'Thyme Whole Leaves 1kg', 'Thyme Whole Leaves 1kg', 5, 10, NULL, 'KSUPP00008', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:27:54', NULL),
(161, 'Oimace Olive Oil 5 liters', 'Oimace Olive Oil 5 liters', 5, 7, NULL, 'KSUPP00009', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:29:01', '2025-01-20 02:29:15'),
(162, 'Oregano Leave 1kg', 'Oregano Leave 1kg', 5, 10, NULL, 'KSUPP00009', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:29:50', NULL),
(163, 'Garlic Powder 1kg ', 'Garlic Powder 1kg ', 5, 10, NULL, 'KSUPP00010', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:30:21', NULL),
(164, 'White Sesame Seeds', 'White Sesame Seeds', 5, 10, NULL, 'KSUPP00011', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:31:01', NULL),
(165, 'Paprika Powder 1kg ', 'Paprika Powder 1kg ', 5, 10, NULL, 'KSUPP00012', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:32:11', NULL),
(166, 'Black Sesame Seeds', 'Black Sesame Seeds', 5, 10, NULL, 'KSUPP00013', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:32:57', NULL),
(167, 'Thyme Powder ', 'Thyme Powder ', 5, 10, NULL, 'KSUPP00014', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:33:22', NULL),
(168, 'Ground Black Pepper 1kg', 'Ground Black Pepper 1kg', 5, 10, NULL, 'KSUPP00015', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:33:46', NULL),
(169, 'Annato Powder 1kg ', 'Annato Powder 1kg ', 5, 10, NULL, 'KSUPP00016', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:35:18', NULL),
(170, 'Taco Mix 40gms', 'Taco Mix 40gms', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:35:50', NULL),
(171, 'Taco seasoning mccormick 1kg', 'Taco seasoning mccormick 1kg', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:36:27', NULL),
(172, 'Basil Leaves Ground 1kg ', 'Basil Leaves Ground 1kg ', 5, 10, NULL, 'KSUPP00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:36:58', NULL),
(173, 'Crack Black Pepper 1kg ', 'Crack Black Pepper 1kg ', 5, 10, NULL, 'KSUPP00018', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:37:28', NULL),
(174, 'Crack Black Pepper 500gms ', 'Crack Black Pepper 500gms ', 5, 10, NULL, 'KSUPP00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:38:01', NULL),
(175, 'Fried Garlic 1kg', 'Fried Garlic 1kg', 5, 10, NULL, 'KSUPP00020', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:38:27', NULL),
(176, 'Black Pepper Whole 1kg', 'Black Pepper Whole 1kg', 5, 10, NULL, 'KSUPP00021', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:38:58', NULL),
(177, 'Parsley Flakes 250gms ', 'Parsley Flakes 250gms ', 5, 10, NULL, 'KSUPP00022', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:39:25', NULL),
(178, 'Cayenne Powder 1kg', 'Cayenne Powder 1kg', 5, 10, NULL, 'KSUPP00023', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:39:51', NULL),
(179, 'Chili Powder 1kg', 'Chili Powder 1kg', 5, 10, NULL, 'KSUPP00024', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:40:19', NULL),
(180, 'Bread Crumbs ', 'Bread Crumbs ', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:40:44', NULL),
(181, 'Oregano Powder 1kg ', 'Oregano Powder 1kg ', 5, 10, NULL, 'KSUPP00025', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:41:08', NULL),
(182, 'Shitake Mushroom 200gms ', 'Shitake Mushroom 200gms ', 5, 10, NULL, 'KSUPP00026', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:41:31', NULL),
(183, 'Wooden Ear Sliced Dry - Japanese mushroom', 'Wooden Ear Sliced Dry - Japanese mushroom', 5, 10, NULL, 'KSUPP00027', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:42:05', NULL),
(184, 'Star Anise ', 'Star Anise ', 5, 10, NULL, 'KSUPP00028', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:42:22', NULL),
(185, 'Corn Syrup', 'Corn Syrup', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:44:56', NULL),
(186, 'Bihon 500gms ', 'Bihon 500gms ', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:45:20', NULL),
(187, 'Pancit Canton', 'Pancit Canton', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:45:47', NULL),
(188, 'Tomato Sauce Original 900ml', 'Tomato Sauce Original 900ml', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:47:29', NULL),
(189, 'Cheese Crackers in a can', 'Cheese Crackers in a can', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:48:07', NULL),
(190, 'Japanese Mayonnaise ', 'Japanese Mayonnaise ', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:48:33', NULL),
(191, 'Del Monte Spaghetti Filipino Style 900ml', 'Del Monte Spaghetti Filipino Style 900ml', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:49:11', NULL),
(192, 'Del Monte Spaghetti Sweet Style 900ml', 'Del Monte Spaghetti Sweet Style 900ml', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:49:52', NULL),
(193, 'Angel Hair Ideal 500gms', 'Angel Hair Ideal 500gms', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:50:15', NULL),
(194, 'Fussili Pasta 1kg', 'Fussili Pasta 1kg', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:50:42', NULL),
(195, 'Linguine Pasta 1kg', 'Linguine Pasta 1kg', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:51:09', NULL),
(196, 'Spaghetti pasta ideal 1kg', 'Spaghetti pasta ideal 1kg', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:51:38', NULL),
(197, 'Pastaricco Spaghetti pasta 500gms', 'Pastaricco Spaghetti pasta 500gms', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:52:13', NULL),
(198, 'Canada dry in can', 'Canada dry in can', 5, 6, NULL, 'NALCOHOL00044', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:52:38', NULL),
(199, 'Jolly Mushroom pieces & stem 2.4kg', 'Jolly Mushroom pieces & stem 2.4kg', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:53:34', NULL),
(200, 'Pickled Jalapenos ', 'Pickled Jalapenos ', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:55:50', NULL),
(201, 'Black Olives Sliced', 'Black Olives Sliced', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:56:15', NULL),
(202, 'Capers in vinegar', 'Capers in vinegar', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:56:51', NULL),
(203, 'Pancake Syrup 355ml', 'Pancake Syrup 355ml', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:57:27', NULL),
(204, 'White Truffle Oil', 'White Truffle Oil', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:57:53', NULL),
(205, 'Heinz Tomato sauce ketchup', 'Heinz Tomato sauce ketchup', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:58:24', NULL),
(206, 'Worceteshire lea & perrins', 'Worceteshire lea & perrins', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 02:59:57', NULL),
(207, 'Shao Xing rice wine vinegar', 'Shao Xing rice wine vinegar', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:00:28', NULL),
(208, 'White Rice vinegar narcissus', 'White Rice vinegar narcissus', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:00:58', NULL),
(209, 'Salsa tartufo - truffle paste', 'Salsa tartufo - truffle paste', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:01:26', NULL),
(210, 'A1 Steak Sauce', 'A1 Steak Sauce', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:01:53', NULL),
(211, 'Heinz Apple Cider Vinegar 945gms', 'Heinz Apple Cider Vinegar 945gms', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:02:25', NULL),
(212, 'Balsamic vinegar 500gms', 'Balsamic vinegar 500gms', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:02:56', NULL),
(213, 'Bambi Nachos', 'Bambi Nachos', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:03:28', NULL),
(214, 'Tender Juicy Hotdog', 'Tender Juicy Hotdog', 5, 2, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 03:04:18', NULL),
(215, 'Vida bacon', 'Vida bacon', 5, 2, NULL, 'PORK00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:04:41', NULL),
(216, 'Frozen Strawberry 1kg', 'Frozen Strawberry 1kg', 5, 14, NULL, 'Fr00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:05:14', NULL),
(217, 'Frozen Strawberry 500gms', 'Frozen Strawberry 500gms', 5, 14, NULL, 'Fr00002', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:05:51', NULL),
(218, 'French fries seasoned', 'French fries seasoned', 5, 7, NULL, 'Fr00003', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 03:06:19', '2025-01-20 03:07:53'),
(219, 'Frozen Spinach 1kg', 'Frozen Spinach 1kg', 5, 15, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:06:46', '2025-01-20 03:07:40'),
(220, 'Hashbrown', 'Hashbrown', 5, 7, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:07:26', NULL),
(221, 'Tocino Argentina ', 'Tocino Argentina ', 5, 1, NULL, 'GROCERIES00035', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:08:39', NULL),
(222, 'Corn & Carrots', 'Corn & Carrots', 5, 15, NULL, 'Veg00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:09:08', NULL),
(223, 'Brussel sprout', 'Brussel sprout', 5, 15, NULL, 'Veg00002', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:09:45', NULL),
(224, 'Chicken Breast', 'Chicken Breast', 5, 12, NULL, 'poultry00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 03:10:10', NULL),
(225, 'Pork Chop', 'Pork Chop', 5, 2, NULL, 'PORK00005', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 03:10:38', NULL),
(226, 'Pork Shoulder - Hamlet', 'Pork Shoulder - Hamlet', 5, 2, NULL, 'PORK00006', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 03:11:07', '2025-01-20 03:13:18'),
(227, 'Calamari Squid 1kg', 'Calamari Squid 1kg', 5, 4, NULL, 'SEAFOODS00002', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:14:03', NULL),
(228, 'Frozen Corn Kernel', 'Frozen Corn Kernel', 5, 14, NULL, 'Fr00003', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:19:39', NULL),
(229, 'Pork Belly', 'Pork Belly', 5, 2, NULL, 'Fr00004', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 03:20:11', NULL),
(230, 'Tuna Steak', 'Tuna Steak', 5, 4, NULL, 'PORK00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:20:52', NULL),
(231, 'Dana blue cheese - triangle', 'Dana blue cheese - triangle', 5, 5, NULL, 'SEAFOODS00003', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:21:40', NULL),
(232, 'Cheese emmethaler', 'Cheese emmethaler', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:22:05', NULL),
(233, 'Cheese brie', 'Cheese brie', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:22:26', NULL),
(234, 'Arla cheese cheddar sliced', 'Arla cheese cheddar sliced', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:22:54', NULL),
(235, 'Emborg cheese cheddar burger sliced', 'Emborg cheese cheddar burger sliced', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:23:32', NULL),
(236, 'Chevital cheese cheddar sliced', 'Chevital cheese cheddar sliced', 1, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:24:07', NULL),
(237, 'Milano Parmesan cheese refill', 'Milano Parmesan cheese refill', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:28:25', NULL),
(238, 'Emborg Sour cream 1L', 'Emborg Sour cream 1L', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:29:00', NULL),
(239, 'Jersey Butterpan', 'Jersey Butterpan', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:29:34', NULL),
(240, 'Jersey Full Cream Milk 1L', 'Jersey Full Cream Milk 1L', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:30:01', NULL),
(241, 'Bungee Whipping Cream', 'Bungee Whipping Cream', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:30:39', NULL),
(242, 'Emborg Cooking Cream', 'Emborg Cooking Cream', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:31:16', NULL),
(243, 'Magnolia Salted Butter ', 'Magnolia Salted Butter ', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:31:50', NULL),
(244, 'Magnolia unsalted Butter ', 'Magnolia unsalted Butter ', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:32:30', NULL),
(245, 'Vida ham', 'Vida ham', 5, 2, NULL, 'PORK00007', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:33:00', NULL),
(246, 'Isabel Longganisa', 'Isabel Longganisa', 5, 2, NULL, 'PORK00008', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:33:25', NULL),
(247, 'Devondale Cream Cheese', 'Devondale Cream Cheese', 5, 5, NULL, 'PORK00009', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:33:55', NULL),
(248, 'Jersey Sweeten Condensed milk', 'Jersey Sweeten Condensed milk', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:34:48', NULL),
(249, 'Frying Fat', 'Frying Fat', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:35:12', NULL),
(250, 'Beryl\'s Dark Chocolate 1kg', 'Beryl\'s Dark Chocolate 1kg', 5, 6, NULL, 'NALCOHOL00045', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:36:02', '2025-01-20 03:38:19'),
(251, 'Beryl\'s Milk Chocolate 1kg', 'Beryl\'s Milk Chocolate 1kg', 5, 6, NULL, 'NALCOHOL00046', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:36:33', '2025-01-20 03:38:09'),
(252, 'Beryl\'s White Chocolate 1kg', 'Beryl\'s White Chocolate 1kg', 5, 6, NULL, 'NALCOHOL00047', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:37:05', '2025-01-20 03:37:59'),
(253, 'DLA Strawberry', 'DLA Strawberry', 5, 6, NULL, 'NALCOHOL00048', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:37:47', NULL),
(254, 'DLA Blueberry', 'DLA Blueberry', 5, 6, NULL, 'NALCOHOL00049', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:39:38', NULL),
(255, 'DLA Mango', 'DLA Mango', 5, 6, NULL, 'NALCOHOL00050', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:40:21', NULL),
(256, 'Daily Quezo', 'Daily Quezo', 5, 5, NULL, 'NALCOHOL00051', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:40:45', NULL),
(257, 'Jersey All Purpose cream', 'Jersey All Purpose cream', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:41:16', NULL),
(258, 'Jersey Evaporada', 'Jersey Evaporada', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:43:06', NULL),
(259, 'Piping bag small', 'Piping bag small', 5, 9, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:43:33', NULL),
(260, 'Piping bag large', 'Piping bag large', 5, 9, NULL, 'RSUPP00002', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:44:06', NULL),
(261, 'Saf instant Yeast 500gms', 'Saf instant Yeast 500gms', 5, 7, NULL, 'RSUPP00003', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:44:47', NULL),
(262, 'White eggs by 30\'s', 'White eggs by 30\'s', 1, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 2, 1, 1, '2025-01-20 03:46:00', NULL),
(263, 'Almond Whole nuts', 'Almond Whole nuts', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:46:44', NULL),
(264, 'Crushed Graham 200gms x 24\'s', 'Crushed Graham 200gms x 24\'s', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 03:47:25', NULL),
(265, 'White Choco droplets', 'White Choco droplets', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:47:59', NULL),
(266, 'Dragees White ', 'Dragees White ', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:48:25', NULL),
(267, 'Dragees silver', 'Dragees silver', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:48:51', NULL),
(268, 'Dragees gold ', 'Dragees gold ', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:49:22', NULL),
(269, 'Green sprinkles', 'Green sprinkles', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:49:55', NULL),
(270, 'Chocolate sprinkles', 'Chocolate sprinkles', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:50:21', NULL),
(271, 'Freeto oil 16L', 'Freeto oil 16L', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:55:49', NULL),
(272, 'Black bag - garbage bag XL', 'Black bag - garbage bag XL', 5, 9, NULL, 'RSUPP00003', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 03:57:50', NULL),
(273, 'Sando bag XL', 'Sando bag XL', 5, 9, NULL, 'RSUPP00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:58:25', NULL),
(274, 'Shure ice bag', 'Shure ice bag', 5, 9, NULL, 'RSUPP00005', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 03:58:54', NULL),
(275, 'Fabric conditioner - fabcon', 'Fabric conditioner - fabcon', 5, 8, NULL, 'RSUPP00006', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-20 03:59:30', NULL),
(276, 'Alcohol 70%', 'Alcohol 70%', 5, 8, NULL, 'CSUPP00007', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-20 03:59:57', NULL),
(277, 'Hand soap', 'Hand soap', 5, 8, NULL, 'CSUPP00007', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-20 04:00:27', NULL),
(278, 'Dishwashing liquid', 'Dishwashing liquid', 5, 8, NULL, 'CSUPP00007', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-20 04:00:53', NULL),
(279, 'Dishwashing paste', 'Dishwashing paste', 5, 8, NULL, 'CSUPP00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:01:23', NULL),
(280, 'Patok 1 1/3 x10', 'Patok 1 1/3 x10', 5, 9, NULL, 'CSUPP00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:02:21', NULL),
(281, 'Sprite in can', 'Sprite in can', 5, 6, NULL, 'NALCOHOL00051', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:02:49', NULL),
(282, 'Coke zero in can', 'Coke zero in can', 5, 6, NULL, 'NALCOHOL00052', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:03:19', NULL),
(283, 'Royal in can', 'Royal in can', 5, 6, NULL, 'NALCOHOL00053', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:03:42', NULL),
(284, 'Walnuts ', 'Walnuts ', 5, 7, NULL, 'NALCOHOL00054', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:04:26', '2025-01-20 04:05:08'),
(285, 'California raisins', 'California raisins', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:05:41', NULL),
(286, 'Skimmed milk milk boy', 'Skimmed milk milk boy', 5, 5, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:06:58', NULL),
(287, 'Baron APF ', 'Baron APF ', 5, 7, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:07:30', NULL),
(288, 'White Sugar', 'White Sugar', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:08:23', NULL),
(289, 'Cornstarch', 'Cornstarch', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:08:44', NULL),
(290, 'Cake Flour', 'Cake Flour', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:09:07', NULL),
(291, 'Hasmine Rice - EC Cafe', 'Hasmine Rice - EC Cafe', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:09:44', NULL),
(292, 'Magic Castle yellow - ESKINA', 'Magic Castle yellow - ESKINA', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:10:16', NULL),
(293, 'Malaysian Creamer', 'Malaysian Creamer', 5, 5, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:10:45', NULL),
(294, 'Pencil', 'Pencil', 5, 9, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:11:11', NULL),
(295, 'Paper Clips ', 'Paper Clips ', 5, 9, NULL, 'RSUPP00006', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:11:42', NULL),
(296, 'Sticky notes small', 'Sticky notes small', 5, 9, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:12:18', NULL),
(297, 'Sticky notes medium', 'Sticky notes medium', 5, 9, NULL, 'RSUPP00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:12:49', NULL),
(298, 'Fastener', 'Fastener', 5, 9, NULL, 'RSUPP00008', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:13:19', NULL),
(299, 'Disinfectant spray - lavender', 'Disinfectant spray - lavender', 5, 9, NULL, 'RSUPP00009', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:13:54', NULL),
(300, 'White board marker ', 'White board marker ', 5, 9, NULL, 'RSUPP00010', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:15:51', NULL),
(301, 'Permanent Marker pen', 'Permanent Marker pen', 5, 9, NULL, 'RSUPP00011', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:16:27', NULL),
(302, 'Staple wire', 'Staple wire', 5, 9, NULL, 'RSUPP00012', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:16:51', NULL),
(303, 'Stapler', 'Stapler', 5, 9, NULL, 'RSUPP00013', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:17:17', NULL),
(304, 'HBW ballpen black', 'HBW ballpen black', 5, 9, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:18:04', NULL),
(305, 'HBW ballpen blue ', 'HBW ballpen blue ', 5, 9, NULL, 'RSUPP00014', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:18:34', NULL),
(306, 'Disposable gloves bear', 'Disposable gloves bear', 5, 9, NULL, 'RSUPP00015', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:19:06', NULL),
(307, 'Red ballpen', 'Red ballpen', 5, 9, NULL, 'RSUPP00016', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:19:36', NULL),
(308, 'Notebook', 'Notebook', 5, 9, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:20:07', NULL),
(309, 'Highlighter ', 'Highlighter ', 5, 9, NULL, 'RSUPP00018', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:20:29', NULL),
(310, 'Correction tape ', 'Correction tape ', 5, 9, NULL, 'RSUPP00019', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:21:11', NULL),
(311, 'Double sided tape', 'Double sided tape', 5, 9, NULL, 'RSUPP00020', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:21:36', NULL),
(312, 'Long Folder', 'Long Folder', 5, 9, NULL, 'RSUPP00021', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:21:55', NULL),
(313, 'Short Folder ', 'Short Folder ', 5, 9, NULL, 'RSUPP00022', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:22:20', NULL),
(314, 'Time Card', 'Time Card', 5, 9, NULL, 'RSUPP00023', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:22:42', NULL),
(315, 'Packaging Tape', 'Packaging Tape', 5, 9, NULL, 'RSUPP00024', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:23:17', NULL),
(316, 'File divider', 'File divider', 5, 9, NULL, 'RSUPP00025', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:24:41', NULL),
(317, 'Scotch Tape ', 'Scotch Tape ', 5, 9, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:27:11', NULL),
(318, 'Fries wax paper', 'Fries wax paper', 5, 9, NULL, 'RSUPP00026', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:27:38', NULL),
(319, 'Paper bag White Large', 'Paper bag White Large', 5, 9, NULL, 'RSUPP00027', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:28:16', NULL),
(320, 'Butane', 'Butane', 5, 9, NULL, 'RSUPP00028', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:28:40', NULL),
(321, 'Thermal paper roll POS - small', 'Thermal paper roll POS - small', 5, 9, NULL, 'RSUPP00029', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:29:08', NULL),
(322, 'Aluminum Foil ', 'Aluminum Foil ', 5, 9, NULL, 'RSUPP00030', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:29:35', NULL),
(323, 'Wax Paper', 'Wax Paper', 5, 9, NULL, 'RSUPP00031', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:30:00', NULL),
(324, 'Kraft Paper Brown wax paper', 'Kraft Paper', 5, 9, NULL, 'RSUPP00032', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:30:31', '2025-01-20 04:32:03'),
(325, 'Croco Wax paper', 'Croco Wax paper', 5, 9, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:32:39', NULL),
(326, 'Kraft paper  Liner Sheet', 'Kraft paper  Liner Sheet', 5, 9, NULL, 'RSUPP00033', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:33:05', NULL),
(327, 'Sponge', 'Sponge', 5, 9, NULL, 'RSUPP00034', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:33:28', NULL),
(328, 'Torch Gun Big ', 'Torch Gun Big ', 5, 9, NULL, 'RSUPP00035', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:33:51', NULL),
(329, 'Torch Gun small', 'Torch Gun small', 5, 9, NULL, 'RSUPP00036', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:34:12', NULL),
(330, 'Mouth Shield', 'Mouth Shield', 1, 1, NULL, 'RSUPP00037', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:34:32', NULL),
(331, 'Disposable gloves black', 'Disposable gloves black', 5, 9, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:38:25', NULL),
(332, 'Chef Master Color - mint green', 'Chef Master Color - mint green', 5, 9, NULL, 'RSUPP00037', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:39:00', NULL),
(333, 'Chef Master Color - baker rose ', 'Chef Master Color - baker rose ', 5, 9, NULL, 'RSUPP00038', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:39:29', NULL),
(334, 'Chef Master Color - christmas red', 'Chef Master Color - christmas red', 5, 9, NULL, 'RSUPP00039', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:40:04', NULL),
(335, 'Chef Master Color - royal blue', 'Chef Master Color - royal blue', 5, 9, NULL, 'RSUPP00040', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:40:24', NULL),
(336, 'Chef Master Color - golden yellow ', 'Chef Master Color - golden yellow ', 5, 10, NULL, 'RSUPP00041', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:40:51', NULL),
(337, 'Chef Master Color - purple', 'Chef Master Color - purple', 5, 9, NULL, 'KSUPP00042', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:41:12', NULL),
(338, 'Chef Master Color - lemon yellow ', 'Chef Master Color - lemon yellow ', 5, 9, NULL, 'RSUPP00041', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:41:37', NULL),
(339, 'Chef Master Color - buckeye brown', 'Chef Master Color - buckeye brown', 5, 9, NULL, 'KSUPP00042', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:42:22', NULL),
(340, 'Umbrella toothpick', 'Umbrella toothpick', 5, 9, NULL, 'RSUPP00042', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:42:44', NULL),
(341, 'Cocktail toothpick', 'Cocktail toothpick', 5, 9, NULL, 'RSUPP00043', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:43:09', NULL),
(342, 'Toothpick', 'Toothpick', 5, 9, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:43:38', NULL),
(343, 'Equal sugar in sachet', 'Equal sugar in sachet', 5, 9, NULL, 'RSUPP00044', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:45:41', NULL),
(344, 'Stevia in sachet', 'Stevia in sachet', 5, 9, NULL, 'RSUPP00045', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:46:21', NULL),
(345, 'Stirrer', 'Stirrer', 5, 9, NULL, 'RSUPP00046', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:46:43', NULL),
(346, 'News print', 'News print', 5, 9, NULL, 'RSUPP00047', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:47:08', NULL),
(347, 'Cleaning cloth', 'Cleaning cloth', 5, 9, NULL, 'RSUPP00048', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:47:29', NULL),
(348, 'Vacuum sealer plastic', 'Vacuum sealer plastic', 5, 9, NULL, 'RSUPP00049', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:48:02', NULL),
(349, 'Uniform XL', 'Uniform XL', 5, 9, NULL, 'RSUPP00050', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:48:28', NULL),
(350, 'Uniform XXL', 'Uniform XXL', 5, 9, NULL, 'RSUPP00051', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:49:01', NULL),
(351, 'Skirt', 'Skirt', 5, 9, NULL, 'RSUPP00052', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:49:17', NULL),
(352, 'Bond paper long size ', 'Bond paper long size ', 5, 9, NULL, 'RSUPP00053', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:49:44', NULL),
(353, 'Bond paper short size ', 'Bond paper short size ', 5, 9, NULL, 'RSUPP00054', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:50:06', NULL),
(354, 'Twine - plastic ', 'Twine - plastic ', 5, 9, NULL, 'RSUPP00055', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:50:32', NULL),
(355, 'masking tape 1 inch', 'masking tape 1 inch', 5, 9, NULL, 'RSUPP00056', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:50:57', NULL),
(356, 'Orange ribbon mats ', 'Orange ribbon mats ', 5, 9, NULL, 'RSUPP00057', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:51:23', NULL),
(357, 'Tape dispenser', 'Tape dispenser', 5, 9, NULL, 'RSUPP00058', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 04:51:44', NULL);
INSERT INTO `product` (`product_id`, `product_name`, `product_description`, `brand_id`, `category_id`, `status_id`, `product_sku`, `product_pp`, `product_sp`, `product_min`, `product_max`, `unit_id`, `tax_id`, `expiry_notice`, `created_at`, `updated_at`) VALUES
(358, 'Double AA battery', 'Double AA battery', 5, 9, NULL, 'RSUPP00059', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:52:11', NULL),
(359, '1/4 pad paper', '1/4 pad paper', 5, 9, NULL, 'RSUPP00060', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 04:52:37', '2025-01-20 04:52:49'),
(360, 'Stirrer w/ cover', 'Stirrer w/ cover', 5, 9, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:14:52', NULL),
(361, 'Burger stick', 'Burger stick', 5, 9, NULL, 'RSUPP00061', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:15:22', NULL),
(362, 'Twinning peach tea', 'Twinning peach tea', 5, 6, NULL, 'RSUPP00062', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:15:53', NULL),
(363, 'Milin nata', 'Milin nata', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:16:17', NULL),
(364, 'Milin asst rainbow jelly', 'Milin asst rainbow jelly', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:16:58', NULL),
(365, 'Popping booba strawberry', 'Popping booba strawberry', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:17:30', NULL),
(366, 'Muscovado sugar 250gms', 'Muscovado sugar 250gms', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:18:07', NULL),
(367, 'Brown sugar in sachet', 'Brown sugar in sachet', 5, 7, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:18:48', NULL),
(368, 'White sugar in sachet', 'White sugar in sachet', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:19:12', NULL),
(369, 'Creamer in sachet', 'Creamer in sachet', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:19:43', NULL),
(370, 'Mollini pizzuti type 00 flour 1kg', 'Mollini pizzuti type 00 flour 1kg', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:20:30', NULL),
(371, 'Rogers pickles relish in a tub 4L', 'Rogers pickles relish in a tub 4L', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:21:10', NULL),
(372, 'Double take out plastic bag - for drinks ', 'Double take out plastic bag - for drinks ', 5, 9, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:21:47', NULL),
(373, 'Single take out plastic bag - for drinks ', 'Single take out plastic bag - for drinks ', 5, 9, NULL, 'RSUPP00062', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:22:41', NULL),
(374, 'Diffuser - Shangrila scent', 'Diffuser - Shangrila scent', 5, 9, NULL, 'RSUPP00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:23:13', NULL),
(375, 'Transparent plastic', 'Transparent plastic', 5, 9, NULL, 'RSUPP00064', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:23:38', NULL),
(376, 'Cookie cutter', 'Cookie cutter', 5, 9, NULL, 'CSUPP00007', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:24:09', NULL),
(377, 'Grill stano fish type', 'Grill stano fish type', 5, 9, NULL, 'RSUPP00065', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:25:01', NULL),
(378, 'Donut cutter', 'Donut cutter', 5, 9, NULL, 'RSUPP00066', 1.00, 0.00, 20, 40, 2, 1, 1, '2025-01-20 06:25:33', NULL),
(379, 'White ribbon 1/2 inches', 'White ribbon 1/2 inches', 5, 9, NULL, 'RSUPP00067', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:26:06', NULL),
(380, 'Pink ribbon', 'Pink ribbon', 5, 9, NULL, 'RSUPP00068', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:26:28', NULL),
(381, 'Spatula', 'Spatula', 5, 9, NULL, 'RSUPP00069', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:26:56', NULL),
(382, 'Apple green food color', 'Apple green food color', 5, 9, NULL, 'RSUPP00070', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:27:24', NULL),
(383, 'Orange food', 'Orange food', 5, 9, NULL, 'RSUPP00071', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:27:50', NULL),
(384, 'Digital thermometer', 'Digital thermometer', 5, 9, NULL, 'RSUPP00072', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:28:20', NULL),
(385, 'Stainless Pitcher 600ml', 'Stainless Pitcher 600ml', 5, 9, NULL, 'RSUPP00073', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:28:40', '2025-01-20 06:28:58'),
(386, 'Stainless Pitcher 350ml', 'Stainless Pitcher 350ml', 5, 9, NULL, 'RSUPP00074', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:29:23', NULL),
(387, 'Price labeler ', 'Price labeler ', 5, 9, NULL, 'RSUPP00075', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:29:48', NULL),
(388, 'Red ribbon', 'Red ribbon', 5, 9, NULL, 'RSUPP00076', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:30:08', NULL),
(389, 'LED Dimmer', 'LED Dimmer', 5, 9, NULL, 'RSUPP00077', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:30:38', NULL),
(390, 'Disposable air cup', 'Disposable air cup', 5, 9, NULL, 'RSUPP00078', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:31:09', NULL),
(391, 'Violet ribbon', 'Violet ribbon', 5, 9, NULL, 'RSUPP00079', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:31:31', NULL),
(392, 'Tip box', 'Tip box', 5, 9, NULL, 'RSUPP00080', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:31:51', NULL),
(393, 'Thermometer', 'Thermometer', 5, 9, NULL, 'RSUPP00081', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:32:12', NULL),
(394, 'Zipper sealer', 'Zipper sealer', 5, 9, NULL, 'RSUPP00082', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:32:37', NULL),
(395, 'Birthday candle fireworks', 'Birthday candle fireworks', 5, 9, NULL, 'RSUPP00083', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:33:20', NULL),
(396, 'White rose banana ', 'White rose banana ', 5, 9, NULL, 'RSUPP00084', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:33:39', NULL),
(397, 'Lemon squeezer', 'Lemon squeezer', 5, 9, NULL, 'RSUPP00085', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:34:03', NULL),
(398, 'AC DC Adaptor', 'AC DC Adaptor', 5, 9, NULL, 'RSUPP00086', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:34:25', NULL),
(399, 'Acetate film', 'Acetate film', 5, 9, NULL, 'RSUPP00087', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:34:46', NULL),
(400, 'Fruit knife', 'Fruit knife', 5, 9, NULL, 'RSUPP00088', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:35:05', NULL),
(401, 'Bamboo tong 10cm ', 'Bamboo tong 10cm ', 5, 9, NULL, 'RSUPP00089', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:35:32', NULL),
(402, 'Inked ribbon cartridge', 'Inked ribbon cartridge', 5, 9, NULL, 'RSUPP00090', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:36:05', NULL),
(403, 'Battery AAA', 'Battery AAA', 5, 9, NULL, 'RSUPP00091', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:36:32', NULL),
(404, 'Measuring spoon w/ brush 1tbsp', 'Measuring spoon w/ brush 1tbsp', 5, 9, NULL, 'RSUPP00092', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:37:02', NULL),
(405, 'EC Cafe Tissue w/ logo', 'EC Cafe Tissue w/ logo', 5, 9, NULL, 'CSUPP00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:43:37', NULL),
(406, '12 x 12 cake Box', '12 x 12 cake Box', 5, 9, NULL, 'RSUPP00093', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:44:09', NULL),
(407, 'Mini donut box ', 'Mini donut box ', 5, 9, NULL, 'RSUPP00094', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:44:40', NULL),
(408, 'Half Roll Cake Box', 'Half Roll Cake Box', 5, 9, NULL, 'RSUPP00095', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:45:04', NULL),
(409, '8 x 8 Cake box ', '8 x 8 Cake box ', 5, 9, NULL, 'RSUPP00096', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:45:28', NULL),
(410, 'Mini Cake box ', 'Mini Cake box ', 5, 9, NULL, 'RSUPP00097', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:45:53', NULL),
(411, 'Brownie Box ', 'Brownie Box ', 5, 9, NULL, 'RSUPP00098', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:46:16', NULL),
(412, '12 x 12 Cake cover', '12 x 12 Cake cover', 5, 9, NULL, 'RSUPP00099', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:46:44', NULL),
(413, '10 x 10 Cake cover', '10 x 10 Cake cover', 5, 9, NULL, 'RSUPP00100', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:47:07', NULL),
(414, 'Gold muffin liner', 'Gold muffin liner', 5, 9, NULL, 'RSUPP00101', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:47:26', NULL),
(415, '8 x 8 Cake cover ', '8 x 8 Cake cover ', 5, 9, NULL, 'RSUPP00102', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:47:57', NULL),
(416, 'Brown / Silver box', 'Brown / Silver box', 5, 9, NULL, 'RSUPP00103', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:48:23', NULL),
(417, 'Plastic Jar container', 'Plastic Jar container', 5, 9, NULL, 'RSUPP00104', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:48:44', NULL),
(418, 'Hinged Cup 30ml', 'Hinged Cup 30ml', 5, 9, NULL, 'RSUPP00105', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:49:20', NULL),
(419, 'Hinged Cup 90ml', 'Hinged Cup 90ml', 5, 9, NULL, 'RSUPP00106', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:49:44', NULL),
(420, '14 inches white cake liner ', '14 inches white cake liner ', 5, 9, NULL, 'RSUPP00107', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:50:22', NULL),
(421, '14 inches gold cake liner ', '14 inches gold cake liner ', 5, 9, NULL, 'RSUPP00108', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:50:50', NULL),
(422, '12 inches white cake liner ', '12 inches white cake liner ', 5, 9, NULL, 'RSUPP00109', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:51:30', NULL),
(423, '10 inches white cake liner ', '10 inches white cake liner ', 5, 9, NULL, 'RSUPP00110', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:52:00', NULL),
(424, '6 inches white cake liner ', '6 inches white cake liner ', 5, 9, NULL, 'KSUPP00042', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:52:32', NULL),
(425, 'Salad liner', 'Salad liner', 5, 9, NULL, 'RSUPP00111', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:53:37', NULL),
(426, 'Soup lid ', 'Soup lid ', 5, 9, NULL, 'RSUPP00112', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:54:01', NULL),
(427, 'Ensaymada Liner', 'Ensaymada Liner', 5, 9, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:57:19', NULL),
(428, 'Disposable fork', 'Disposable fork', 5, 9, NULL, 'RSUPP00113', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 06:57:43', NULL),
(429, 'Disposable spoon', 'Disposable spoon', 5, 9, NULL, 'RSUPP00114', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 06:58:10', NULL),
(430, 'Trigem 2D White', 'Trigem 2D White', 5, 9, NULL, 'RSUPP00115', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:58:40', NULL),
(431, 'Glass Jar container', 'Glass Jar container', 5, 9, NULL, 'RSUPP00116', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 06:59:20', NULL),
(432, 'Black straw big', 'Black straw big', 5, 9, NULL, 'RSUPP00117', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 06:59:47', NULL),
(433, 'Black straw small', 'Black straw small', 5, 9, NULL, 'RSUPP00118', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:00:18', NULL),
(434, 'Red Gate - Matcha Flavor', 'Red Gate - Matcha Flavor', 5, 6, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:09:02', NULL),
(435, 'Red Gate - Okinawa Flavor', 'Red Gate - Okinawa Flavor', 5, 6, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:09:43', NULL),
(436, 'Bear brand 1210gms', 'Bear brand 1210gms', 5, 5, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:10:09', NULL),
(437, 'Calumet baking powder', 'Calumet baking powder', 5, 7, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:10:42', NULL),
(438, 'Nestle cucumber lemonade', 'Nestle cucumber lemonade', 5, 6, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:11:11', NULL),
(439, 'Nestle lemonade', 'Nestle lemonade', 5, 6, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:11:34', NULL),
(440, 'Nestle Lychee', 'Nestle Lychee', 5, 6, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:11:51', NULL),
(441, 'Nestle Blue lemonade', 'Nestle Blue lemonade', 5, 6, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:12:14', NULL),
(442, 'Nestle strawberry red tea', 'Nestle strawberry red tea', 5, 6, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:12:46', NULL),
(443, 'Top series - Cheesy mango', 'Top series - Cheesy mango', 5, 6, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:13:16', NULL),
(444, 'Glutinous Rice Flour 1kg', 'Glutinous Rice Flour 1kg', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:14:26', NULL),
(445, 'Glutinous Rice Flour 500gms ', 'Glutinous Rice Flour 500gms ', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:14:48', NULL),
(446, 'Blended gold flakes', 'Blended gold flakes', 5, 7, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:15:11', NULL),
(447, 'Caramel machiato powder 500gms', 'Caramel machiato powder 500gms', 5, 6, NULL, 'GROCERIES00004', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:15:38', NULL),
(448, 'Bianca snow powder 1kg', 'Bianca snow powder 1kg', 5, 7, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:16:11', NULL),
(449, 'ESKINA 22oz cold cup', 'ESKINA 22oz cold cup', 5, 9, NULL, 'RSUPP00119', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:17:21', NULL),
(450, 'ESKINA 16oz cold cup', 'ESKINA 16oz cold cup', 5, 9, NULL, 'RSUPP00120', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:17:47', NULL),
(451, 'SNACKBAR 22oz cold cup', 'SNACKBAR 22oz cold cup', 5, 9, NULL, 'RSUPP00121', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:18:17', NULL),
(452, 'SNACKBAR 16oz cold cup', 'SNACKBAR 16oz cold cup', 5, 9, NULL, 'RSUPP00122', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:18:44', NULL),
(453, 'SNACKBAR 8oz cold cup', 'SNACKBAR 8oz cold cup', 5, 9, NULL, 'RSUPP00123', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:19:06', NULL),
(454, 'SNACKBAR 5oz cold cup', 'SNACKBAR 5oz cold cup', 5, 9, NULL, 'RSUPP00124', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:19:40', NULL),
(455, 'SNACKBAR 12oz hot cup', 'SNACKBAR 12oz hot cup', 5, 9, NULL, 'RSUPP00125', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:20:11', NULL),
(456, 'SNACKBAR 8oz hot cup', 'SNACKBAR 8oz hot cup', 5, 9, NULL, 'RSUPP00126', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:20:36', NULL),
(457, 'MATS hot cup 12oz orange', 'MATS hot cup 12oz orange', 5, 9, NULL, 'RSUPP00127', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:21:13', NULL),
(458, 'MATS hot cup 8oz orange', 'MATS hot cup 8oz orange', 5, 9, NULL, 'RSUPP00128', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:21:37', NULL),
(459, 'MATS hot cup 16oz blue', 'MATS hot cup 16oz blue', 5, 9, NULL, 'RSUPP00129', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:22:06', NULL),
(460, 'MATS hot cup 22oz blue', 'MATS hot cup 22oz blue', 5, 9, NULL, 'RSUPP00130', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:22:41', NULL),
(461, 'EC Cafe Hot Cup', 'EC Cafe Hot Cup', 5, 9, NULL, 'RSUPP00131', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:23:25', NULL),
(462, 'ESKINA 16oz dome lid ', 'ESKINA 16oz dome lid ', 5, 9, NULL, 'RSUPP00132', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:23:57', '2025-01-20 07:24:50'),
(463, 'ESKINA 22oz dome lid ', 'ESKINA 22oz dome lid ', 5, 9, NULL, 'RSUPP00133', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:24:23', NULL),
(464, 'Pop can w/ logo', 'Pop can w/ logo', 5, 9, NULL, 'RSUPP00134', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:26:05', NULL),
(465, 'Pop can ', 'Pop can ', 5, 9, NULL, 'RSUPP00135', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:26:40', NULL),
(466, 'Half Roll cake black tray', 'Half Roll cake black tray', 5, 9, NULL, 'RSUPP00136', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:27:10', NULL),
(467, 'Chef Tissue ', 'Chef Tissue ', 5, 9, NULL, 'RSUPP00137', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:27:30', NULL),
(468, 'Tissue roll', 'Tissue roll', 5, 9, NULL, 'RSUPP00138', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:27:48', NULL),
(469, 'Halo halo cup', 'Halo halo cup', 5, 9, NULL, 'RSUPP00139', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:28:11', NULL),
(470, 'Pop up tissue ', 'Pop up tissue ', 5, 9, NULL, 'RSUPP00140', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:28:35', NULL),
(471, 'Sundae Cup', 'Sundae Cup', 5, 9, NULL, 'RSUPP00141', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:28:57', NULL),
(472, 'RE 2D Black', 'RE 2D Black', 5, 9, NULL, 'RSUPP00142', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:29:22', '2025-01-20 07:30:29'),
(473, 'RE 3D Black', 'RE 3D Black', 5, 9, NULL, 'RSUPP00143', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:30:02', NULL),
(474, 'Iskrambol Cup', 'Iskrambol Cup', 5, 9, NULL, 'RSUPP00144', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:30:56', NULL),
(475, '5oz cup', '5oz cup', 5, 9, NULL, 'RSUPP00145', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:31:21', NULL),
(476, 'Chicken box white', 'Chicken box white', 5, 9, NULL, 'RSUPP00146', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:31:44', NULL),
(477, 'Spaghetti box white ', 'Spaghetti box white ', 5, 9, NULL, 'RSUPP00147', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:32:11', NULL),
(478, 'Burger box ', 'Burger box ', 5, 9, NULL, 'RSUPP00148', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:32:38', NULL),
(479, 'Hotdog tray', 'Hotdog tray', 5, 9, NULL, 'RSUPP00149', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:33:09', NULL),
(480, 'Footlong tray', 'Footlong tray', 1, 9, NULL, 'RSUPP00150', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:33:40', NULL),
(481, 'Chicken box brown/silver', 'Chicken box brown/silver', 1, 9, NULL, 'RSUPP00151', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:34:08', NULL),
(482, 'Spaghetti box brown/silver', 'Spaghetti box brown/silver', 5, 9, NULL, 'RSUPP00152', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:34:36', NULL),
(483, '1oz Cup', '1oz Cup', 5, 9, NULL, 'RSUPP00153', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:34:54', NULL),
(484, 'Trigem Meal Box 2D', 'Trigem Meal Box 2D', 5, 9, NULL, 'RSUPP00154', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:35:35', NULL),
(485, 'RE 250ml ', 'RE 250ml ', 5, 9, NULL, 'RSUPP00155', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:36:00', NULL),
(486, 'RE 500ml ', 'RE 500ml ', 5, 9, NULL, 'RSUPP00156', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:36:22', NULL),
(487, 'RE 750ml ', 'RE 750ml ', 5, 9, NULL, 'RSUPP00157', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:36:46', NULL),
(488, 'RE 1000ml ', 'RE 1000ml ', 5, 9, NULL, 'RSUPP00158', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:37:10', NULL),
(489, 'Bento Box lid', 'Bento Box lid', 5, 9, NULL, 'RSUPP00159', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:37:44', NULL),
(490, 'Pizza box ', 'Pizza box ', 5, 9, NULL, 'RSUPP00160', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:40:07', NULL),
(491, 'MATS Box of 3', 'MATS Box of 3', 5, 9, NULL, 'RSUPP00161', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:40:31', NULL),
(492, 'MATS Box of 6', 'MATS Box of 6', 5, 9, NULL, 'RSUPP00162', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:40:50', NULL),
(493, 'Cello sheet', 'Cello sheet', 5, 9, NULL, 'RSUPP00163', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:42:18', NULL),
(494, 'Muffin liner brown', 'Muffin liner brown', 5, 9, NULL, 'RSUPP00164', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:42:44', NULL),
(495, 'Pasty bag small', 'Pasty bag small', 5, 9, NULL, 'RSUPP00165', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:43:08', NULL),
(496, 'Front clear pouch', 'Front clear pouch', 5, 9, NULL, 'RSUPP00166', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:43:32', NULL),
(497, 'Paper plate 25\'s', 'Paper plate 25\'s', 5, 9, NULL, 'RSUPP00167', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:43:56', NULL),
(498, 'BBQ Bamboo Stick', 'BBQ Bamboo Stick', 5, 9, NULL, 'RSUPP00168', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:44:20', NULL),
(499, 'Garbage bag black small', 'Garbage bag black small', 5, 9, NULL, 'RSUPP00169', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:44:45', NULL),
(500, 'Garbage bag black large', 'Garbage bag black large', 5, 9, NULL, 'RSUPP00170', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:45:13', NULL),
(501, 'Garbage bag black extra large', 'Garbage bag black extra large', 5, 9, NULL, 'RSUPP00171', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:45:37', NULL),
(502, 'Garbage bag black double extra large', 'Garbage bag black double extra large', 5, 9, NULL, 'RSUPP00172', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:46:05', NULL),
(503, 'Garbage bag clear small', 'Garbage bag clear small', 5, 9, NULL, 'RSUPP00173', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:46:26', NULL),
(504, 'Garbage bag clear large', 'Garbage bag clear large', 5, 9, NULL, 'RSUPP00174', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:46:55', NULL),
(505, 'Garbage bag clear extra large', 'Garbage bag clear extra large', 5, 9, NULL, 'RSUPP00175', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:47:19', NULL),
(506, 'Wheat Flour', 'Wheat Flour', 5, 7, NULL, 'RSUPP00176', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:47:47', NULL),
(507, 'Bread improver', 'Bread improver', 5, 7, NULL, 'GROCERIES00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:48:56', NULL),
(508, 'Powdered Sugar', 'Powdered Sugar', 5, 7, NULL, 'GROCERIES00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:49:18', NULL),
(509, 'Cello food packaging large', 'Cello food packaging large', 5, 7, NULL, 'GROCERIES00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:49:52', NULL),
(510, 'Washed sugar', 'Washed sugar', 5, 7, NULL, 'GROCERIES00007', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:50:19', NULL),
(511, 'Diced cashew', 'Diced cashew', 5, 7, NULL, 'GROCERIES00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:50:44', NULL),
(512, 'Sliced cashew ', 'Sliced cashew ', 5, 7, NULL, 'GROCERIES00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:51:10', NULL),
(513, 'SMB pale pilsen in can', 'SMB pale pilsen in can', 5, 13, NULL, 'Alcoholic00040', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 07:51:46', NULL),
(514, 'San mig light in can', 'San mig light in can', 5, 13, NULL, 'Alcoholic00040', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 07:52:16', NULL),
(515, 'Birthday candle ', 'Birthday candle ', 5, 9, NULL, 'Alcoholic00040', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:52:51', NULL),
(516, 'Cake bento box ', 'Cake bento box ', 5, 9, NULL, 'GROCERIES00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:53:31', NULL),
(517, 'Rice flour', 'Rice flour', 5, 9, NULL, 'RSUPP00176', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:54:46', NULL),
(518, 'Loaf plastic bag', 'Loaf plastic bag', 5, 9, NULL, 'RSUPP00177', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 07:55:09', NULL),
(519, 'Wax paper - croco', 'Wax paper - croco', 5, 9, NULL, 'RSUPP00178', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:55:40', NULL),
(520, 'Measuring spoon 1tbsp', 'Measuring spoon 1tbsp', 5, 9, NULL, 'RSUPP00179', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:56:09', NULL),
(521, 'Black tongs', 'Black tongs', 5, 9, NULL, 'RSUPP00180', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:56:29', NULL),
(522, 'White brush', 'White brush', 5, 9, NULL, 'RSUPP00181', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:57:04', NULL),
(523, 'Wooden brush', 'Wooden brush', 5, 9, NULL, 'RSUPP00182', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:57:26', NULL),
(524, 'Milk tea sealer', 'Milk tea sealer', 5, 9, NULL, 'RSUPP00183', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:57:57', NULL),
(525, 'Acetate film 8cm', 'Acetate film 8cm', 5, 9, NULL, 'RSUPP00184', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:58:57', NULL),
(526, 'Acetate film 6cm', 'Acetate film 6cm', 5, 9, NULL, 'RSUPP00185', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:59:14', NULL),
(527, 'Electrical tape', 'Electrical tape', 5, 9, NULL, 'RSUPP00186', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:59:37', NULL),
(528, 'Number 1 Candle ', 'Number 1 Candle ', 5, 9, NULL, 'RSUPP00187', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 07:59:59', NULL),
(529, 'Confetti', 'Confetti', 5, 9, NULL, 'RSUPP00188', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:00:20', NULL),
(530, 'metal spoon 6\'s ', 'metal spoon 6\'s ', 5, 9, NULL, 'RSUPP00189', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:01:01', NULL),
(531, 'Vinyl paper clip 33mm', 'Vinyl paper clip 33mm', 5, 9, NULL, 'RSUPP00190', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 08:01:39', NULL),
(532, 'Cutter blade', 'Cutter blade', 5, 9, NULL, 'RSUPP00191', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:02:08', '2025-01-20 08:02:26'),
(533, 'Coffee tamper ', 'Coffee tamper ', 5, 9, NULL, 'RSUPP00192', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:02:54', NULL),
(534, 'Brown ribbon', 'Brown ribbon', 5, 9, NULL, 'RSUPP00193', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:04:34', NULL),
(535, 'Weighing scale ', 'Weighing scale ', 5, 9, NULL, 'RSUPP00194', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:05:39', NULL),
(536, 'Money detector', 'Money detector', 5, 9, NULL, 'RSUPP00195', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:07:00', NULL),
(537, 'Thermal paper big', 'Thermal paper big', 5, 9, NULL, 'RSUPP00196', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:07:28', NULL),
(538, 'Cold lid 50\'s ', 'Cold lid 50\'s ', 5, 9, NULL, 'RSUPP00197', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:08:05', NULL),
(539, 'Hot lid large 50\'s ', 'Hot lid large 50\'s ', 5, 9, NULL, 'RSUPP00198', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:08:35', NULL),
(540, 'Hot lid small 50\'s ', 'Hot lid small 50\'s ', 5, 9, NULL, 'RSUPP00199', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:09:02', NULL),
(541, 'Mineral water bottle mats ', 'Mineral water bottle mats ', 5, 9, NULL, 'RSUPP00200', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:09:38', '2025-01-20 08:10:28'),
(542, 'Wet mineral water', 'Wet mineral water', 5, 9, NULL, 'RSUPP00201', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:10:11', NULL),
(543, 'Sliced cake take out case', 'Sliced cake take out case', 5, 9, NULL, 'RSUPP00202', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:11:07', NULL),
(544, 'Interfolded tissue ', 'Interfolded tissue ', 5, 9, NULL, 'RSUPP00203', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:11:42', NULL),
(545, 'Wax paper jumbo roll ', 'Wax paper jumbo roll ', 5, 9, NULL, 'RSUPP00204', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:12:44', NULL),
(546, 'Pork Bulaklak', 'Pork Bulaklak', 5, 2, NULL, 'RSUPP00205', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:13:15', NULL),
(547, 'Pork Kasim', 'Pork Kasim', 5, 2, NULL, 'PORK00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:13:48', NULL),
(548, 'Chicken leg quarter (CLQ)', 'Chicken leg quarter (CLQ)', 5, 12, NULL, 'poultry00002', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:14:48', NULL),
(549, 'Pork Liempo', 'Pork Liempo', 5, 2, NULL, 'PORK00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:15:21', NULL),
(550, 'Pork Jowls ', 'Pork Jowls ', 5, 2, NULL, 'PORK00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:15:51', NULL),
(551, 'Tender Juicy Hotdog 3kgs - 60pcs per bag', 'Tender Juicy Hotdog 3kgs - 60pcs per bag', 5, 2, NULL, 'PORK00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:16:32', NULL),
(552, 'Kamatis', 'Kamatis', 5, 15, NULL, 'PORK00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:17:05', NULL),
(553, 'Tokwa', 'Tokwa', 5, 7, NULL, 'Veg00003', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:17:28', NULL),
(554, 'Pipino', 'Pipino', 5, 14, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:17:50', NULL),
(555, 'Pugo', 'Pugo', 5, 7, NULL, 'Fr00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:18:13', NULL),
(556, 'Talong ', 'Talong ', 5, 14, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:19:42', '2025-01-20 08:28:25'),
(557, 'Lumpia wrapper ', 'Lumpia wrapper ', 5, 7, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:28:06', NULL),
(558, 'Tinapa', 'Tinapa', 5, 4, NULL, 'Fr00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:28:58', NULL),
(559, 'Kangkong ', 'Kangkong ', 5, 15, NULL, 'Veg00003', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:29:35', NULL),
(560, 'Kalamansi', 'Kalamansi', 5, 15, NULL, 'Veg00004', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:30:13', NULL),
(561, 'Siling Green', 'Siling Green', 5, 15, NULL, 'Veg00005', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:30:33', NULL),
(562, 'Potatoes', 'Potatoes', 5, 15, NULL, 'Veg00006', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:30:55', NULL),
(563, 'Mama sitas palabok mix', 'Mama sitas palabok mix', 5, 7, NULL, 'Veg00007', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:31:28', NULL),
(564, 'Sitaw', 'Sitaw', 5, 15, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:31:51', NULL),
(565, 'Ampalaya', 'Ampalaya', 5, 15, NULL, 'Veg00007', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:32:12', NULL),
(566, 'Repolyo', 'Repolyo', 5, 15, NULL, 'Veg00008', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:32:29', '2025-01-20 08:33:27'),
(567, 'Red bell pepper', 'Red bell pepper', 5, 15, NULL, 'Veg00009', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:33:01', NULL),
(568, 'Carrots', 'Carrots', 5, 15, NULL, 'Veg00010', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:33:48', NULL),
(569, 'Sayote', 'Sayote', 5, 15, NULL, 'Veg00011', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:34:10', NULL),
(570, 'Pechay baguio', 'Pechay baguio', 5, 15, NULL, 'Veg00012', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:34:34', NULL),
(571, 'Kalabasa', 'Kalabasa', 5, 15, NULL, 'Veg00013', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:35:01', NULL),
(572, 'Oyster sauce', 'Oyster sauce', 5, 7, NULL, 'Veg00014', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-20 08:35:30', NULL),
(573, 'Tilapya', 'Tilapya', 5, 4, NULL, 'SEAFOODS00003', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:37:10', NULL),
(574, 'Pusit', 'Pusit', 5, 4, NULL, 'SEAFOODS00004', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:37:44', NULL),
(575, 'Isaw manok', 'Isaw manok', 5, 12, NULL, 'SEAFOODS00005', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:38:20', NULL),
(576, 'Fine nuts ', 'Fine nuts ', 5, 7, NULL, 'poultry00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:38:42', NULL),
(577, 'Fresh rosemary', 'Fresh rosemary', 5, 7, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:39:06', NULL),
(578, 'Chicken wing ', 'Chicken wing ', 5, 12, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 08:39:31', NULL),
(579, 'Beef Trimmings', 'Beef Trimmings', 5, 1, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:40:07', NULL),
(580, 'Onion Red - OME VEG. - TL JEN', 'Onion Red - OME VEG. - TL JEN', 5, 15, NULL, 'MEAT00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:41:31', NULL),
(581, 'Onion White - OME VEG. - TL JEN', 'Onion White - OME VEG. - TL JEN', 5, 15, NULL, 'Veg00014', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:41:55', NULL),
(582, 'Calamansi - OME VEG. - TL JEN', 'Calamansi - OME VEG. - TL JEN', 5, 15, NULL, 'Veg00015', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:42:31', NULL),
(583, 'Toyo - MY FAVORITE SAUCES ', 'Toyo - MY FAVORITE SAUCES ', 5, 7, NULL, 'Veg00016', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-20 08:43:14', NULL),
(584, 'Suka - MY FAVORITE SAUCES ', 'Suka - MY FAVORITE SAUCES ', 5, 7, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-20 08:44:07', NULL),
(585, 'Salted Egg - BUNSO', 'Salted Egg - BUNSO', 5, 7, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:44:42', NULL),
(586, 'Talbos ng kalabasa', 'Talbos ng kalabasa', 5, 15, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:45:12', NULL),
(587, 'Coffee sleeves', 'Coffee sleeves', 5, 9, NULL, 'Veg00016', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:45:44', NULL),
(588, 'Surf powder ', 'Surf powder ', 5, 8, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:46:11', NULL),
(589, 'Champion Powder', 'Champion Powder', 5, 8, NULL, 'CSUPP00018', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:46:40', NULL),
(590, 'Fresh mango', 'Fresh mango', 5, 14, NULL, 'CSUPP00018', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:47:10', NULL),
(591, 'Fresh banana', 'Fresh banana', 5, 14, NULL, 'Fr00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:47:32', NULL),
(592, 'Mosa Charger', 'Mosa Charger', 5, 9, NULL, 'Fr00001', 1.00, 0.00, 20, 40, 4, 1, 1, '2025-01-20 08:54:50', NULL),
(593, 'Mineral Water - AQUAKIA', 'Mineral Water - AQUAKIA', 5, 6, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-20 08:55:24', NULL),
(594, 'Fresh cucumber', 'Fresh cucumber', 5, 14, NULL, 'Fr00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 08:55:48', NULL),
(595, 'Kraft paper bag - BROWN', 'Kraft paper bag - BROWN', 5, 9, NULL, 'Fr00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:56:25', NULL),
(596, 'EC CAFE Cold cup 22oz', 'EC CAFE Cold cup 22oz', 5, 9, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:56:59', NULL),
(597, 'EC CAFE Cold cup 16oz', 'EC CAFE Cold cup 16oz', 5, 9, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:57:18', NULL),
(598, 'EC CAFE Cold cup 12oz', 'EC CAFE Cold cup 12oz', 5, 9, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:57:39', NULL),
(599, 'EC CAFE Cold cup 8oz', 'EC CAFE Cold cup 8oz', 5, 9, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:58:00', NULL),
(600, 'Cling wrap', 'Cling wrap', 5, 9, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:58:22', NULL),
(601, 'Easy Cream Cheese powder - salted ', 'Easy Cream Cheese powder - salted ', 5, 5, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:58:54', NULL),
(602, 'Birthday Toppers HBD', 'Birthday Toppers HBD', 5, 9, NULL, 'DAIRY00001', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 08:59:23', NULL),
(603, 'Sandwich paper ', 'Sandwich paper ', 5, 9, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 08:59:56', NULL),
(604, 'Sprite 1.5L', 'Sprite 1.5L', 5, 6, NULL, 'RSUPP00017', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 09:00:40', NULL),
(605, 'Garlic ', 'Garlic ', 5, 7, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 09:01:09', NULL),
(606, 'Pork Tenga', 'Pork Tenga', 5, 2, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 09:01:36', NULL),
(607, 'Palabok 500gms', 'Palabok 500gms', 5, 7, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 09:02:02', NULL),
(608, 'Lettuce remaine', 'Lettuce remaine', 5, 15, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 09:02:55', NULL),
(609, 'Red cabbage ', 'Red cabbage ', 5, 15, NULL, 'Veg00016', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 09:04:55', '2025-01-20 09:05:16'),
(610, 'Fresh lemon', 'Fresh lemon', 5, 14, NULL, 'Veg00017', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 09:05:51', NULL),
(611, 'Fresh orange', 'Fresh orange', 5, 14, NULL, 'Fr00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 09:06:15', NULL),
(612, 'Fresh mint leaves', 'Fresh mint leaves', 5, 7, NULL, 'Fr00001', 1.00, 0.00, 20, 40, 5, 1, 1, '2025-01-20 09:06:42', NULL),
(613, 'Mineral water - 7 Drops Precious Water', 'Mineral water - 7 Drops Precious Water', 5, 6, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 11, 1, 1, '2025-01-20 09:07:25', NULL),
(614, 'Nata Green', 'Nata Green', 5, 7, NULL, 'NALCOHOL00063', 1.00, 0.00, 20, 40, 1, 1, 1, '2025-01-20 09:08:18', NULL),
(615, 'Cranberries 1kg ', 'Cranberries 1kg ', 5, 14, NULL, 'GROCERIES00001', 1.00, 0.00, 20, 40, 3, 1, 1, '2025-01-20 09:08:48', NULL);

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
(3, 'Accountant'),
(5, 'ojt');

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
(1, 55),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 14),
(2, 15),
(2, 16),
(2, 17),
(2, 18),
(2, 19),
(2, 20),
(2, 21),
(2, 22),
(2, 23),
(2, 24),
(2, 25),
(2, 26),
(2, 27),
(2, 28),
(2, 29),
(2, 30),
(2, 31),
(2, 32),
(2, 33),
(2, 34),
(2, 35),
(2, 36),
(2, 37),
(2, 38),
(2, 39),
(2, 40),
(2, 41),
(2, 42),
(2, 43),
(2, 44),
(2, 45),
(2, 46),
(2, 47),
(3, 1),
(3, 19),
(3, 20),
(3, 21),
(3, 22),
(3, 23),
(3, 24),
(3, 43),
(3, 44),
(3, 45),
(3, 46),
(3, 47),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(5, 10),
(5, 11),
(5, 12),
(5, 13),
(5, 14),
(5, 15),
(5, 16),
(5, 17),
(5, 18),
(5, 19),
(5, 20),
(5, 21),
(5, 22),
(5, 25),
(5, 26),
(5, 27),
(5, 28),
(5, 29),
(5, 30),
(5, 31),
(5, 32),
(5, 33),
(5, 34),
(5, 35),
(5, 36),
(5, 37),
(5, 38),
(5, 39),
(5, 40),
(5, 41),
(5, 42);

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
(1, 'Susan and Willy', 'Susan and Willy', 'address', '09123456789', 'company@gmail.com', 1, '2024-08-21 05:46:37', '2025-01-14 03:51:38'),
(2, 'RMMM Meat Trading', 'RMMM Meat Trading', 'Baliuag, Bulacan', '09123456789', 'company@gmail.com', 1, '2024-08-21 05:46:44', '2025-01-14 03:52:24'),
(3, 'Boss Mike Meat Shop', 'Boss Mike Meat Shop', 'Sto. Cristo Pulilan, Bulacan', '09123456789', 'company@gmail.com', 1, '2024-08-21 05:46:51', '2025-01-14 10:07:03'),
(4, 'Beginning Balance-123124', 'Beginning Balance-123124', 'Baliuag Bulacan', '09123456789', 'company@gmail.com', 1, '2024-08-21 05:55:01', '2025-01-21 02:15:16'),
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
(0, '12% Tax', 12),
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

--
-- Dumping data for table `trans_bill`
--

INSERT INTO `trans_bill` (`id`, `supplier_id`, `bill_address`, `bill_date`, `bill_due_date`, `bill_no`, `transaction_no`, `tax_type`, `total_amount`, `sales_tax`, `grand_total`, `payment_status`, `remarks`, `isVoid`, `created_at`, `updated_at`) VALUES
(2, 2, '', '2025-01-06', '2025-01-14', '5823', 'BILL-20250114-001', 3, 4910.00, 0.00, 4910.00, 'unpaid', '', 0, '2025-01-14 04:02:39', '2025-01-14 04:02:39'),
(4, 3, NULL, '2025-01-12', '2025-01-12', '25847', 'BILL-20250114-002', 3, 15728.40, 0.00, 15728.40, 'unpaid', 'For EC Cafe', 0, '2025-01-14 10:14:51', '2025-01-14 10:14:51');

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

--
-- Dumping data for table `trans_invoice`
--

INSERT INTO `trans_invoice` (`id`, `customer_id`, `customer_email`, `invoice_bill_address`, `invoice_shipping_address`, `invoice_ship_via`, `invoice_ship_date`, `invoice_date`, `invoice_duedate`, `invoice_track_no`, `invoice_no`, `transaction_no`, `tax_type`, `payment_status`, `total_amount`, `sales_tax`, `grand_total`, `remarks`, `isVoid`, `created_at`, `updated_at`) VALUES
(3, 2, 'test1@gmail.com', 'Baliuag Bulacan', NULL, NULL, '2025-01-17', '2025-01-12', '2025-01-15', NULL, NULL, 'INV-20250114-001', 3, 'paid', 17301.24, 0.00, 17301.24, 'Transfer to EC Cafe', 0, '2025-01-14 10:16:50', NULL);

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
  `transaction_type` enum('bill','expense','invoice','purchase_order','inventory_adjustment') DEFAULT NULL,
  `item_expiry` date NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trans_item`
--

INSERT INTO `trans_item` (`item_id`, `transaction_no`, `product_sku`, `item_barcode`, `item_qty`, `item_rate`, `item_tax`, `item_amount`, `transaction_type`, `item_expiry`, `customer_id`, `created_at`) VALUES
(2, 'BILL-20250114-001', 'PORK00001', '', 12, 120.00, NULL, 1440.00, 'bill', '0000-00-00', NULL, '2025-01-14 04:02:39'),
(3, 'BILL-20250114-001', 'PORK00002', '', 10, 122.00, NULL, 1220.00, 'bill', '0000-00-00', NULL, '2025-01-14 04:02:39'),
(4, 'BILL-20250114-001', 'PORK00003', '', 15, 150.00, NULL, 2250.00, 'bill', '0000-00-00', NULL, '2025-01-14 04:02:39'),
(9, 'BILL-20250114-002', 'BEEF00001', '', 30, 510.00, NULL, 15728.40, 'bill', '0000-00-00', NULL, '2025-01-14 10:14:51'),
(10, 'INV-20250114-001', 'BEEF00001', '', -30, 561.00, NULL, 16830.00, 'invoice', '0000-00-00', NULL, '2025-01-14 10:16:50'),
(11, 'ADJ-20250114-001', 'PORK00003', NULL, -5, 150.00, NULL, 750.00, 'inventory_adjustment', '0000-00-00', NULL, '2025-01-24 02:10:27');

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
(11, 'Gallon', 'gal');

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
(6, 'johannie', '$2y$10$EGuMfwsxUqEbjRIR7OTDI.n/48v6pzHrlHlBmjYuSVjmHg2/cCg9O', 'johannie', 2, 1, '2024-11-28 01:47:21', NULL),
(7, 'anthony', '$2y$10$t1dtDhwRafqu7HZrBgwQkOktHnSSXQnQQuFYCId/kkr8hI6vpGcoy', 'Anthony', 1, 1, '2025-01-13 05:28:46', '2025-01-14 03:31:33'),
(8, 'accounts1', '$2y$10$40jkY9PkeqjnFXDDiGihc.VL4QlSdgOSCjpV0HVDylHrZfhKcZNYO', 'accounts1', 5, 1, '2025-01-14 02:49:45', '2025-01-14 02:52:27'),
(9, 'accounts2', '$2y$10$kT/09TcK6zejSyYfZZMcgeyWETb188lfSOD2GV1B.Phv/s9W/M1n2', 'accounts2', 5, 1, '2025-01-14 02:49:58', '2025-01-14 02:52:22'),
(10, 'audit1', '$2y$10$rcV0m7oRvehtaEb0/3WUWeJ/kASN/7d0KHcGLAoxtU/KbMtdrY7Ii', 'audit1', 5, 1, '2025-01-14 02:50:25', '2025-01-14 02:52:17');

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
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=616;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trans_expense`
--
ALTER TABLE `trans_expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_invoice`
--
ALTER TABLE `trans_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `trans_item`
--
ALTER TABLE `trans_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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

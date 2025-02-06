-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 01:01 PM
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
(4, '15%', 15);

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
-- Table structure for table `ingredient`
--

CREATE TABLE `ingredient` (
  `ingredient_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) DEFAULT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `ingredient_qty` int(11) NOT NULL,
  `warn_qty` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredient`
--

INSERT INTO `ingredient` (`ingredient_id`, `ingredient_name`, `price_per_unit`, `ingredient_qty`, `warn_qty`, `unit_id`, `created_at`, `updated_at`) VALUES
(1, 'Batter Flour', 20.00, 24, 3, 5, '2025-02-05 09:18:15', '2025-02-06 11:50:05'),
(2, 'Cheese Bits', 20.00, 5, 10, 3, '2025-02-05 09:18:18', '2025-02-06 08:17:53'),
(3, 'Octopus Bits', 20.00, 0, 10, 3, '2025-02-05 09:18:20', '2025-02-06 08:17:55'),
(4, 'Kewpie Mayo', 20.00, 20, 10, 3, '2025-02-05 09:18:22', '2025-02-06 08:17:48'),
(5, 'Takoyaki Sauce', 20.00, 20, 10, 3, '2025-02-05 09:18:24', '2025-02-06 08:17:48'),
(6, 'Seaweed Flakes', 20.00, 20, 10, 3, '2025-02-05 09:18:26', '2025-02-06 08:17:48'),
(7, 'Cooking Oil', 20.00, 20, 10, 3, '2025-02-05 09:21:21', '2025-02-06 08:17:48'),
(8, 'Styrofoam Box', 20.00, 20, 10, 3, '2025-02-05 09:21:24', '2025-02-06 08:17:48'),
(9, 'Cheese Sauce', 20.00, 20, 10, 3, '2025-02-05 09:21:25', '2025-02-06 08:17:48'),
(10, 'Grated Cheese', 20.00, 20, 10, 3, '2025-02-05 09:21:27', '2025-02-06 08:17:48'),
(11, 'Baby Octopus', 20.00, 20, 10, 3, '2025-02-05 09:21:31', '2025-02-06 08:17:48'),
(12, 'Bacon Strips', 20.00, 20, 10, 3, '2025-02-05 09:21:33', '2025-02-06 08:17:48');

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
(9, '', NULL),
(10, '', NULL),
(11, 'Product Waste', NULL),
(12, 'Ingredient Waste', NULL),
(13, 'Report', NULL),
(14, 'User', NULL),
(15, 'Role', NULL);

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
(1, 'manage_dashboard', 'MANAGE', NULL),
(2, 'manage_pos', 'MANAGE', NULL),
(3, 'manage_product', 'MANAGE', NULL),
(4, 'create_product', 'CREATE', NULL),
(5, 'update_product', 'UPDATE', NULL),
(6, 'delete_product', 'DELETE', NULL),
(7, 'manage_ingredient', 'MANAGE', NULL),
(8, 'create_ingredient', 'CREATE', NULL),
(9, 'update_ingredient', 'UPDATE', NULL),
(10, 'delete_ingredient', 'DELETE', NULL),
(11, 'manage_category', 'MANAGE', NULL),
(12, 'create_category', 'CREATE', NULL),
(13, 'update_category', 'UPDATE', NULL),
(14, 'delete_category', 'DELETE', NULL),
(15, 'manage_unit', 'MANAGE', NULL),
(16, 'create_unit', 'CREATE', NULL),
(17, 'update_unit', 'UPDATE', NULL),
(18, 'delete_unit', 'DELETE', NULL),
(20, 'manage_discount', 'MANAGE', NULL),
(21, 'create_discount', 'CREATE', NULL),
(22, 'update_discount', 'UPDATE', NULL),
(23, 'delete_discount', 'DELETE', NULL);

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
(5, 'Octobits', 15.00, 0, 'assets/images/products/image.png', 2, 0, '2025-02-04 15:22:06', '2025-02-04 15:56:00'),
(6, 'Cheese Sauce', 15.00, 0, 'assets/images/products/image.png', 2, 0, '2025-02-04 15:22:12', '2025-02-04 15:56:00'),
(7, 'Kewpie Sauce', 15.00, 0, 'assets/images/products/image.png', 2, 0, '2025-02-04 15:22:46', '2025-02-04 15:56:00'),
(8, 'Takoyaki Sauce', 15.00, 0, 'assets/images/products/image.png', 2, 0, '2025-02-04 15:22:47', '2025-02-04 15:56:00'),
(9, 'Cheese Bits', 15.00, 0, 'assets/images/products/image.png', 2, 0, '2025-02-04 15:22:50', '2025-02-05 09:56:28');

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
(2, 'Cashier');

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
(1, 20),
(1, 21),
(1, 22),
(1, 23);

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
(1, 'Tax', '1.5', '2024-07-19 04:29:28', '2025-02-06 08:47:47');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `cashier_name` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `subtotal` varchar(255) DEFAULT NULL,
  `transaction_paid` decimal(10,2) NOT NULL,
  `transaction_subtotal` decimal(10,2) NOT NULL,
  `transaction_discount` decimal(10,2) NOT NULL,
  `transaction_change` decimal(10,2) NOT NULL,
  `transaction_grandtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `transaction_summary`
-- (See below for the actual view)
--
CREATE TABLE `transaction_summary` (
);

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
(4, 'purchase', '$2y$10$fu8HEOtJ/Jq2yVXmua8meeAFO3i04W.V6lcj.jdoMXfkHhn1/KWC.', 'Purchasing', 2, 0, '2024-07-26 10:24:19', '2024-08-27 03:09:17'),
(6, 'johannie', '$2y$10$EGuMfwsxUqEbjRIR7OTDI.n/48v6pzHrlHlBmjYuSVjmHg2/cCg9O', 'johannie', 2, 1, '2024-11-28 01:47:21', NULL);

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
-- Indexes for table `file_upload`
--
ALTER TABLE `file_upload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD KEY `unit_id` (`unit_id`);

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
  ADD UNIQUE KEY `permission_name` (`permission_name`),
  ADD KEY `fr_moduleid` (`module_id`);

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `file_upload`
--
ALTER TABLE `file_upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ingredient_waste`
--
ALTER TABLE `ingredient_waste`
  MODIFY `waste_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_option`
--
ALTER TABLE `system_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingredient`
--
ALTER TABLE `ingredient`
  ADD CONSTRAINT `ingredient_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`unit_id`);

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
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fr_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

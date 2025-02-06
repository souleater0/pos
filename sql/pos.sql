-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 10:12 AM
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
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartid` int(11) NOT NULL,
  `INID` int(11) NOT NULL,
  `product_Name` varchar(50) NOT NULL,
  `Bar_code` varchar(50) NOT NULL,
  `qty` varchar(20) NOT NULL,
  `Unit_Price` varchar(20) NOT NULL,
  `Total_Price` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartid`, `INID`, `product_Name`, `Bar_code`, `qty`, `Unit_Price`, `Total_Price`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bacon cheesy takoyaki', '4006381333931', '1', '105.0', '105.0', '2025-02-03 08:58:55', '2025-02-03 08:58:55'),
(2, 1, 'Ultimate Cheesy Takoyaki', '4901234567894', '1', '85.0', '85.0', '2025-02-03 08:58:55', '2025-02-03 08:58:55'),
(3, 1, 'Addons Octobits', '8712345678903', '2', '15.0', '30.0', '2025-02-03 08:58:55', '2025-02-03 08:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cid` int(11) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `cp_Number` bigint(11) NOT NULL,
  `Address` varchar(150) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cid`, `customer_name`, `cp_Number`, `Address`, `Type`, `created_at`, `updated_at`) VALUES
(7, 'Customer 1', 9503601184, 'Tondo,Manila', 'N/A', '2025-01-07 14:50:34', NULL),
(8, 'Customer 2', 9503887432, 'Banga 1st, Plaridel', '-', '2025-01-07 14:50:34', NULL),
(9, 'Customer 3', 9865473892, 'Cutcot,Pulilan', '-', '2025-01-07 14:50:34', NULL),
(10, 'Customer 4', 974537304, 'Sto.Nino, Plaridel,Bulacan', '-', '2025-01-07 14:50:34', NULL),
(13, 'Customer 5', 9474647383, 'Plaridel,Bulacan', 'Gcash', '2025-01-07 14:50:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `extra`
--

CREATE TABLE `extra` (
  `exid` int(11) NOT NULL,
  `val` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `extra`
--

INSERT INTO `extra` (`exid`, `val`) VALUES
(1, '2');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` int(11) NOT NULL,
  `ingredient_name` varchar(200) NOT NULL,
  `barcode` varchar(10) NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `ingredient_name`, `barcode`, `price_per_unit`, `qty`, `unit_id`, `created_at`, `updated_at`) VALUES
(1, 'Batter Flour', '111', 2.00, 8712.00, 1, '2025-01-17 03:36:58', '2025-02-03 08:58:55'),
(2, 'Cheese Bits', '222', 1.50, 8940.00, 2, '2025-01-17 03:36:58', '2025-02-03 08:58:55'),
(3, 'Octopus Bits', '333', 5.00, 8880.00, 2, '2025-01-17 03:36:58', '2025-02-03 08:58:55'),
(4, 'Kewpie Mayo', '444', 3.00, 8856.00, 2, '2025-01-17 03:36:58', '2025-02-03 08:58:55'),
(5, 'Takoyaki Sauce', '555', 1.00, 8724.00, 1, '2025-01-17 03:36:58', '2025-02-03 08:58:55'),
(6, 'Seaweed Flakes', '666', 0.50, 8996.00, 2, '2025-01-17 03:36:58', '2025-02-03 08:58:55'),
(7, 'Cooking Oil', '777', 0.10, 8940.00, 1, '2025-01-17 03:36:58', '2025-02-03 08:58:55'),
(8, 'Styrofoam Box', '888', 0.20, 8998.00, 3, '2025-01-17 03:36:58', '2025-02-03 08:58:55'),
(9, 'Cheese Sauce', '999', 1.80, 8928.00, 2, '2025-01-17 03:36:58', '2025-02-03 08:58:55'),
(10, 'Grated Cheese', '123', 2.50, 8988.00, 2, '2025-01-17 03:36:58', '2025-02-03 08:58:55'),
(11, 'Baby Octopus', '124', 6.00, 9000.00, 2, '2025-01-17 03:36:58', '2025-01-20 16:30:00'),
(12, 'Bacon Strips', '125', 4.00, 8994.00, 3, '2025-01-17 04:22:15', '2025-02-03 08:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pid` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `barcode` varchar(20) NOT NULL,
  `sell_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pid`, `product_name`, `barcode`, `sell_price`, `created_at`, `updated_at`) VALUES
(1, 'Bacon cheesy takoyaki', '4006381333931', 105.00, '2025-01-07 14:51:04', '2025-01-20 11:48:28'),
(2, 'Ultimate Cheesy Takoyaki', '4901234567894', 85.00, '2025-01-07 14:51:04', '2025-01-20 11:48:32'),
(3, 'Baby Octo Takoyaki', '4012345678901', 120.00, '2025-01-07 14:51:04', '2025-01-20 12:13:57'),
(4, 'Authentic Takoyaki', '4391234567896', 65.00, '2025-01-13 17:27:26', '2025-01-20 12:13:58'),
(6, 'Addons Octobits', '8712345678903', 15.00, '2025-01-20 11:49:14', '2025-01-20 12:02:57'),
(7, 'Addons Cheese Sauce', '8801234567891', 15.00, '2025-01-20 11:49:53', '2025-01-20 12:03:19'),
(8, 'Addons Kewpie Sauce', '8934567890128', 15.00, '2025-01-20 11:50:15', '2025-01-20 12:03:49'),
(9, 'Addons Takoyaki Sauce', '9012345678906', 15.00, '2025-01-20 11:50:33', '2025-01-20 12:03:54'),
(10, 'Addons Cheese Bits', '9301234567890 ', 15.00, '2025-01-20 11:50:51', '2025-01-20 12:03:59');

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `recipe_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ingredient_id` int(11) DEFAULT NULL,
  `qty` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`recipe_id`, `product_id`, `ingredient_id`, `qty`) VALUES
(1, 1, 1, 144.00),
(2, 1, 2, 30.00),
(3, 1, 3, 30.00),
(4, 1, 4, 72.00),
(5, 1, 5, 138.00),
(6, 1, 6, 2.00),
(7, 1, 7, 30.00),
(8, 1, 8, 1.00),
(9, 1, 12, 6.00),
(10, 2, 1, 144.00),
(11, 2, 2, 30.00),
(12, 2, 3, 30.00),
(13, 2, 4, 72.00),
(14, 2, 9, 72.00),
(15, 2, 10, 12.00),
(16, 2, 5, 138.00),
(17, 2, 6, 2.00),
(18, 2, 7, 30.00),
(19, 2, 8, 1.00),
(20, 3, 1, 144.00),
(21, 3, 2, 30.00),
(22, 3, 11, 100.00),
(23, 3, 4, 72.00),
(24, 3, 9, 72.00),
(25, 3, 10, 12.00),
(26, 3, 5, 138.00),
(27, 3, 6, 2.00),
(28, 3, 7, 30.00),
(29, 3, 8, 1.00),
(30, 4, 1, 50.00),
(31, 6, 3, 30.00),
(32, 7, 9, 72.00),
(33, 8, 4, 72.00),
(34, 9, 5, 138.00),
(35, 10, 2, 30.00);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `saleid` int(11) NOT NULL,
  `INID` int(11) NOT NULL,
  `Cid` int(11) NOT NULL,
  `Customer_Name` varchar(255) NOT NULL,
  `Total_Qty` decimal(11,2) NOT NULL,
  `Total_Bill` decimal(11,2) NOT NULL,
  `Status` varchar(10) NOT NULL,
  `Balance` decimal(11,2) NOT NULL,
  `isVoid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`saleid`, `INID`, `Cid`, `Customer_Name`, `Total_Qty`, `Total_Bill`, `Status`, `Balance`, `isVoid`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'Customer 1', 4.00, 220.00, 'Unpaid', 220.00, 0, '2025-02-03 08:58:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(255) DEFAULT NULL,
  `unit_prefix` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unit_name`, `unit_prefix`, `created_at`, `updated_at`) VALUES
(1, 'Milimeter', 'ml', '2025-01-17 03:31:22', '2025-01-17 03:31:50'),
(2, 'Grams', 'gms', '2025-01-17 03:32:09', NULL),
(3, 'Pieces', 'pcs', '2025-01-17 03:32:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_type` enum('Cashier','Admin') NOT NULL DEFAULT 'Cashier',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role_type`, `created_at`, `updated_at`) VALUES
(-1, 'root', 'takoadmin', 'Admin', '2025-01-21 00:36:08', '2025-01-21 00:37:25'),
(1, 'cash', '111', 'Cashier', '0000-00-00 00:00:00', '2025-01-15 13:39:29'),
(2, 'admin', '222', 'Admin', '0000-00-00 00:00:00', '2025-01-15 13:36:55');

-- --------------------------------------------------------

--
-- Table structure for table `waste`
--

CREATE TABLE `waste` (
  `waste_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `waste_qty` int(11) DEFAULT NULL,
  `waste_sell_price` decimal(10,2) DEFAULT NULL,
  `waste_reason` varchar(255) DEFAULT NULL,
  `reported_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste`
--

INSERT INTO `waste` (`waste_id`, `product_name`, `waste_qty`, `waste_sell_price`, `waste_reason`, `reported_by`, `created_at`, `updated_at`) VALUES
(5, 'Baby Octo Takoyaki', 2, 120.00, 'Went to the Sea', 'admin', '2025-01-19 17:02:34', NULL),
(6, 'Ultimate Cheesy Takoyaki', 1, 85.00, 'Expired', 'admin', '2025-01-19 17:02:53', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartid`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `extra`
--
ALTER TABLE `extra`
  ADD PRIMARY KEY (`exid`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `unit` (`unit_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`recipe_id`) USING BTREE,
  ADD KEY `fr_ingred` (`ingredient_id`),
  ADD KEY `fr_prod` (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`saleid`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `waste`
--
ALTER TABLE `waste`
  ADD PRIMARY KEY (`waste_id`) USING BTREE,
  ADD KEY `waste_prod_id` (`product_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `extra`
--
ALTER TABLE `extra`
  MODIFY `exid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `saleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `waste`
--
ALTER TABLE `waste`
  MODIFY `waste_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD CONSTRAINT `unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `recipe`
--
ALTER TABLE `recipe`
  ADD CONSTRAINT `fr_ingred` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fr_prod` FOREIGN KEY (`product_id`) REFERENCES `product` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 19, 2023 at 03:21 AM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lingqp`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `expired_date` enum('Yes','No') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `expired_date`, `created`) VALUES
(1, 'Beverages', 'Soft drinks, coffees, teas, beers, and ales', 'Yes', '2023-08-02 11:56:17'),
(2, 'Condiments', 'Sweet and savory sauces, relishes, spreads, and seasonings', 'Yes', '2023-08-02 11:56:49'),
(3, 'Confections', 'Desserts, candies, and sweet breads', 'Yes', '2023-08-02 11:57:13'),
(4, 'Dairy Products', 'Cheeses', 'Yes', '2023-08-02 11:57:50'),
(5, 'Grains/Cereals', 'Breads, crackers, pasta, and cereal', 'Yes', '2023-08-02 11:58:00'),
(6, 'Meat/Poultry', 'Prepared meats', 'Yes', '2023-08-02 11:58:15'),
(7, 'Produce', 'Dried fruit and bean curd', 'Yes', '2023-08-02 11:58:25'),
(8, 'Seafood', 'Seaweed and fish', 'Yes', '2023-08-02 11:58:34'),
(9, 'Kitchen Appliances', 'Oven, kettle and blender', 'No', '2023-09-12 07:12:18'),
(10, 'Furniture', 'Chairs, tables, sofas, and cabinets', 'No', '2023-09-12 09:02:55');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `username` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `first_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gender` enum('Male','Female') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `registration_date_and_time` datetime NOT NULL,
  `account_status` enum('Active','Inactive','Pending') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`username`, `email`, `password`, `first_name`, `last_name`, `gender`, `date_of_birth`, `registration_date_and_time`, `account_status`, `image`) VALUES
('Quan95', 'quan@mail.com', '25d55ad283aa400af464c76d713c07ad', 'Quan', 'Lim', 'Male', '1995-01-01', '2023-08-06 12:18:52', 'Pending', ''),
('kimkim', 'kimkim@mail.com', '25d55ad283aa400af464c76d713c07ad', 'Jane', 'Kim', 'Female', '2002-06-01', '2023-08-06 12:20:06', 'Active', ''),
('Nathan02', 'nathan02@mail.com', '25d55ad283aa400af464c76d713c07ad', 'Nathan', 'How', 'Male', '2002-03-20', '2023-08-06 12:29:02', 'Inactive', ''),
('Belloooo', 'Bello@gmail.com', '$2y$10$3sGQ63tC8Ag/Nlfl8E1XtuM9m59GrCJCFofgWTt366betSeRL2ywy', 'Bellie', 'Hsu', 'Male', '1999-03-18', '2023-08-07 03:15:18', 'Active', 'uploads/600a8f1953637ef9cc833b1d2b4bf7eb66a274fa-user-a.jpg'),
('Estella', 'estella02@gmail.com', '$2y$10$Sx4I8AhCEiT.JkJDTbwkIuaFuOliPODcVlXS4WTVqH0b9VzqmQZly', 'Loh', 'Estella', 'Female', '2002-05-28', '2023-08-07 04:21:32', 'Active', ''),
('Chewvy11', 'chewvy@mail.com', '$2y$10$r39ToTeRax5fi9rGNsm1xe2M7WU8Z80Di7OXazaYQ9Hqk63C7Ti5a', 'Chewvy', 'Lau', 'Male', '2002-11-11', '2023-08-07 13:55:10', 'Inactive', ''),
('Mickey', 'mickey@mail.com', '$2y$10$MvJtoGoKcxl4DnVgPQTvV.fhnSnSjvUgD5ZzsRV8S0831liJTJ93y', 'Mouse', 'Mickey', 'Male', '1928-10-01', '2023-08-07 13:57:01', 'Pending', 'uploads/8cf765b5221347d64187582d37d05682a0c958b6-user-mickey.jpeg'),
('Minnie', 'minnie@gmail.com', '$2y$10$cF.7MUb2uWW7SX6f2YPSlOqH52djkc3Iml4hPlokTnHoKkw7E4eeO', 'Minnie', 'Mouse', 'Female', '1996-07-02', '2023-08-08 03:37:29', 'Active', 'uploads/fa79c498628871283786f677293f3a606fbb30f9-user-minnie.jpg'),
('low_tang', 'low@mail.com', '$2y$10$YZ9B1VRWPrQMqf6fTFLdIeBkDaOP2e8yMV.TQJzFY/F/d4ef./F86', 'Tang', 'Low', 'Male', '2004-01-27', '2023-08-27 22:22:29', 'Active', ''),
('MayJune', 'mayjune@mail.com', '$2y$10$24LcITpTRQE2qAuuoAsfv.EmjvNYBeVsXIJpD8jV8s27umKSs3rOm', 'May', 'June', 'Female', '2000-12-12', '2023-09-04 15:04:36', 'Active', ''),
('donald_D', 'donald@mail.com', '$2y$10$sfBdOKfnklkLhzBU2.hIeeyLwXk2sqc3m/zd1ilDi9xIvL.kKKkCq', 'Donald', 'Duck', 'Male', '1986-05-05', '2023-09-04 15:14:00', 'Inactive', 'uploads/8f16f42040aa750589e26334d9906e2792b36110-user-donald.jpg'),
('daisy_daisy', 'daisy@mail.com', '$2y$10$hUC1Lyxpx70r1IpsBC0yXukKe3FTEwSFLRwmf5mVTKbCxgvMLWqhO', 'Daisy', 'Duck', 'Female', '1985-05-08', '2023-09-04 15:19:46', 'Inactive', 'uploads/a898d65ee7c13b68f4218e1d87e2f5e889810806-user-daisy.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `order_detail_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`order_detail_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 1, 3, 3),
(2, 1, 13, 5),
(3, 1, 17, 8),
(4, 2, 21, 1),
(5, 2, 15, 20),
(6, 2, 6, 30),
(7, 3, 7, 5),
(8, 3, 5, 10),
(9, 3, 21, 5),
(10, 4, 7, 5),
(11, 4, 5, 10),
(12, 4, 21, 15),
(13, 5, 4, 2),
(14, 5, 14, 4),
(15, 5, 17, 6),
(16, 5, 13, 8),
(17, 5, 23, 10),
(18, 6, 20, 2),
(19, 6, 24, 5),
(20, 7, 2, 4),
(21, 7, 15, 8),
(22, 8, 2, 3),
(23, 13, 2, 3),
(24, 14, 4, 7),
(53, 43, 2, 2),
(26, 19, 10, 10),
(27, 20, 5, 5),
(28, 22, 1, 3),
(29, 22, 3, 4),
(30, 23, 1, 5),
(31, 23, 2, 4),
(32, 24, 11, 5),
(33, 25, 9, 10),
(34, 28, 1, 11),
(84, 44, 19, 20),
(36, 30, 16, 5),
(37, 31, 6, 6),
(38, 32, 14, 14),
(39, 33, 4, 16),
(83, 44, 16, 15),
(41, 35, 2, 5),
(42, 35, 12, 3),
(43, 40, 2, 3),
(44, 40, 18, 6),
(45, 40, 17, 9),
(46, 41, 1, 5),
(47, 41, 17, 10),
(48, 41, 14, 15),
(49, 41, 19, 20),
(54, 43, 15, 4),
(82, 44, 6, 5),
(81, 44, 14, 10),
(79, 45, 47, 6),
(78, 45, 32, 4),
(77, 45, 34, 2),
(93, 46, 37, 9),
(92, 46, 13, 8),
(91, 46, 11, 7),
(90, 46, 32, 9),
(80, 45, 23, 8);

-- --------------------------------------------------------

--
-- Table structure for table `order_summary`
--

DROP TABLE IF EXISTS `order_summary`;
CREATE TABLE IF NOT EXISTS `order_summary` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `order_date_time` datetime NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_summary`
--

INSERT INTO `order_summary` (`order_id`, `username`, `order_date_time`) VALUES
(1, 'Mickey', '2023-08-14 19:31:31'),
(2, 'Belloooo', '2023-08-14 19:32:12'),
(3, 'Nathan02', '2023-08-20 09:42:55'),
(4, 'Nathan02', '2023-08-20 09:44:13'),
(5, 'kimkim', '2023-08-21 04:10:21'),
(6, 'Quan95', '2023-08-21 04:28:40'),
(7, 'Belloooo', '2023-08-21 21:59:32'),
(8, 'Minnie', '2023-08-21 22:03:06'),
(13, 'Minnie', '2023-08-21 23:56:02'),
(14, 'Chewvy11', '2023-08-22 00:02:29'),
(43, 'donald_D', '2023-09-12 09:19:09'),
(19, 'Estella', '2023-08-22 00:44:02'),
(20, 'Belloooo', '2023-08-22 00:45:36'),
(46, 'Nathan02', '2023-09-16 07:43:12'),
(22, 'kimkim', '2023-08-22 00:52:27'),
(23, 'Belloooo', '2023-08-22 00:59:46'),
(24, 'Chewvy11', '2023-08-22 01:00:42'),
(25, 'kimkim', '2023-08-22 01:01:24'),
(28, 'Chewvy11', '2023-08-22 02:46:13'),
(44, 'donald_D', '2023-09-16 07:38:15'),
(30, 'Belloooo', '2023-08-22 08:51:15'),
(31, 'Mickey', '2023-08-22 08:51:29'),
(32, 'Estella', '2023-08-22 08:52:03'),
(33, 'Nathan02', '2023-08-22 08:53:58'),
(45, 'MayJune', '2023-09-16 07:39:58'),
(35, 'Minnie', '2023-08-22 09:43:13'),
(40, 'Nathan02', '2023-08-27 05:37:52'),
(41, 'Quan95', '2023-08-27 17:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `category_id` int NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `promotion_price` double NOT NULL,
  `manufacture_date` date NOT NULL,
  `expired_date` date NOT NULL,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `description`, `price`, `promotion_price`, `manufacture_date`, `expired_date`, `image`, `created`, `modified`) VALUES
(1, 'Premium Colombian Coffee ', 1, 'Rich Colombian coffee beans with notes of chocolate and citrus.', 12.99, 0, '2022-07-01', '2026-11-01', '', '2023-08-09 14:42:10', '2023-09-09 23:10:11'),
(2, 'Sparkling Raspberry Lemonade', 1, 'Bubbly blend of real raspberries and zesty lemon.', 2.49, 0, '2023-01-01', '2024-12-31', 'uploads/1f67f03a9e9c99daef6f65da54f30cef4036da6c-Sparkling Raspberry Lemonade.jpg', '2023-08-09 14:43:11', '2023-09-05 03:26:54'),
(3, 'Green Tea Infusion Kit', 1, 'Explore diverse green tea flavors with a glass teapot set.', 29.99, 0, '2023-06-20', '2026-06-19', 'uploads/bda5c99b06b0235aa3a478d15923697786e0b770-Green Tea Infusion Kit.jpg', '2023-08-09 14:44:07', '2023-09-02 22:37:35'),
(4, 'Organic Balsamic Vinegar', 2, ' Aged for rich flavor, our vinegar adds a tangy twist to salads.', 8.99, 0, '2023-05-15', '2025-05-15', 'uploads/ad365fa7b94f2dd9a7971485fdd2bf300fca30e4-Organic Balsamic Vinegar.jpg', '2023-08-09 14:46:16', '2023-09-02 22:39:38'),
(5, 'Spicy Mango Chutney', 2, 'Elevate your dishes with the zesty kick of our spicy mango chutney.', 5.49, 5, '2023-04-10', '2024-04-10', 'uploads/8034fe4c1fe39c81b7d93de71ebdd1dd8604f61f-Spicy Mango Chutney.jpg', '2023-08-09 14:47:07', '2023-09-03 15:05:30'),
(6, 'Roasted Garlic Aioli', 2, 'An exquisite companion to sandwiches and fries.', 4.79, 0, '2022-06-20', '2023-12-20', 'uploads/edca7e00657e6298aafef01949f68b5f3a282c81-Roasted Garlic Aioli.jpg', '2023-08-09 14:48:37', '2023-09-12 02:34:34'),
(7, 'Chocolate Truffles', 3, 'Indulge in the velvety decadence of our handcrafted chocolate truffles.', 12.99, 11.99, '2023-07-01', '2023-12-31', '', '2023-08-09 14:51:09', '2023-08-09 14:51:09'),
(8, 'Gummy Bears', 3, 'Chewy candy shaped like a bear, made from gelatin and flavored with fruit.', 2.49, 0, '2022-06-15', '2023-10-15', '', '2023-08-09 14:54:06', '2023-09-03 15:21:36'),
(9, 'Vanilla Cupcakes', 3, 'Baked to perfection with a moist and tender crumb.', 3.99, 0, '2023-08-08', '2023-08-15', '', '2023-08-09 14:57:15', '2023-08-09 14:57:15'),
(10, 'Creamy Greek Yogurt', 4, 'A perfect blend of protein and probiotics.', 2.99, 0, '2023-08-01', '2023-08-15', '', '2023-08-09 14:59:46', '2023-08-09 14:59:46'),
(11, 'Freshly Churned Butter', 4, 'Elevate your culinary creations with our freshly churned butter.', 4.49, 0, '2023-07-20', '2023-09-20', '', '2023-08-09 15:00:39', '2023-08-09 15:14:40'),
(12, 'Artisanal Cheese Selection', 4, 'A delightful medley of textures and tastes.', 8.99, 7.99, '2023-07-05', '2023-12-05', '', '2023-08-09 15:01:18', '2023-08-09 15:01:18'),
(13, 'Honey Almond Granola', 5, 'A delightful blend of rolled oats, almonds, and sweet honey clusters.', 4.99, 0, '2023-08-10', '2024-02-10', '', '2023-08-09 15:02:31', '2023-08-09 15:02:31'),
(14, 'Whole Wheat Pancake Mix', 5, 'A blend of whole grain goodness perfect for hearty breakfasts.', 2.79, 0, '2023-07-15', '2023-12-15', 'uploads/fa035432b36f6acdc721fa0ec892c317b764f144-Whole Wheat Pancake Mix.jpg', '2023-08-09 15:03:36', '2023-09-12 02:43:23'),
(15, 'Maple Pecan Granola Bars', 5, 'A harmonious blend of oats, pecans, and a touch of maple sweetness.', 2.29, 0, '2023-08-05', '2023-12-05', 'uploads/3d37ff6ab3574ddda98886fdb52726f1f5cba9cc-Maple Pecan Granola Bars.jpg', '2023-08-09 15:05:22', '2023-09-12 02:45:29'),
(16, 'Creamy Chicken Alfredo', 6, 'Savor the comfort of our creamy chicken alfredo.', 9.99, 8.99, '2023-08-12', '2023-08-18', '', '2023-08-09 15:07:09', '2023-08-28 17:12:58'),
(17, 'Smoky Bacon-Wrapped Chops', 6, 'Elevate your dining experience with this smoky bacon-wrapped chops.', 7.49, 0, '2023-07-28', '2023-08-10', 'uploads/a1c8888da784446f0921b350c5e7d8afef1411c3-Smoky Bacon-Wrapped Chops.jpg', '2023-08-09 15:08:06', '2023-09-12 02:41:06'),
(18, 'Zesty Turkey Meatballs', 6, 'Delight in the zestful burst of flavors from our zesty turkey meatballs.', 5.99, 0, '2023-08-05', '2023-08-15', '', '2023-08-09 15:09:09', '2023-08-09 15:09:09'),
(19, 'Organic Mixed Berries', 7, 'A medley of sweet strawberries, juicy blueberries, and tangy raspberries.', 6.99, 0, '2023-08-10', '2023-08-15', '', '2023-08-09 15:10:23', '2023-08-09 15:10:23'),
(20, 'Crisp Baby Spinach', 7, 'A nutritious and tender leafy green packed with vitamins and minerals.', 2.49, 0, '2023-08-05', '2023-08-12', '', '2023-08-09 15:11:03', '2023-08-27 13:43:15'),
(21, 'Ripe Avocados', 7, 'Perfect for adding a luscious texture to your guacamole or toast.', 1.99, 1.89, '2023-08-08', '2023-08-14', '', '2023-08-09 15:11:44', '2023-08-09 15:11:44'),
(22, 'Wild-caught Salmon Fillet', 8, 'Indulge in the exquisite flavor of our wild-caught salmon fillet.', 14.99, 0, '2023-08-10', '2023-08-15', '', '2023-08-09 15:12:35', '2023-08-09 15:12:35'),
(23, 'Jumbo Shrimp Skewers', 8, 'Elevate your barbecue with our jumbo shrimp skewers.', 9.49, 9.29, '2023-08-05', '2023-08-12', '', '2023-08-09 15:13:13', '2023-08-09 15:13:13'),
(24, 'Fresh Oyster Selection', 8, 'A culinary adventure of distinct flavors from various coastal regions.', 2.99, 0, '2023-08-08', '2023-08-14', '', '2023-08-09 15:13:47', '2023-08-09 15:13:47'),
(25, 'Organic Quinoa', 5, 'Nutrient-rich organic quinoa, a versatile superfood packed with protein and fiber.', 15.99, 0, '2003-01-01', '2025-01-01', '', '2023-08-14 15:48:51', '2023-09-05 01:06:53'),
(26, 'Whole Grain Brown Rice', 5, 'Premium whole grain brown rice, known for its hearty texture and nutty flavor, ideal for a variety of dishes.', 35.99, 0, '2023-03-05', '2023-03-12', '', '2023-08-14 15:48:54', '2023-09-05 01:05:05'),
(27, 'Organic Chicken Breast', 6, 'Tender and lean organic chicken breast, free from antibiotics and raised with care.', 7.49, 0, '2023-05-09', '2023-10-09', '', '2023-08-14 15:49:05', '2023-09-05 01:05:57'),
(28, 'Salmon Fillet', 8, 'Perfect for a variety of culinary creations.', 32.99, 0, '2023-08-24', '2023-09-01', '', '2023-08-27 04:13:51', '2023-08-26 20:13:51'),
(29, 'Mix Berries Yogurt', 4, 'A delightful dairy treat bursting with natural flavors.', 1.99, 1.89, '2023-08-21', '2023-08-28', '', '2023-08-27 04:18:36', '2023-08-26 20:18:36'),
(30, 'Heinz Ketchup', 2, 'America\'s favorite ketchup, known for its rich tomato flavor and smooth texture. ', 3.99, 0, '2023-08-24', '2024-11-14', 'uploads/686d2c18a1ce33b36c67900f8f49c9bcbdce6caf-Heinz Ketchup.jpg', '2023-09-03 05:51:10', '2023-09-02 22:36:07'),
(32, 'Grey Poupon Dijon Mustard', 2, 'A classic Dijon mustard with a bold, savory taste, perfect for sandwiches and dressings.', 2.49, 2.39, '2023-01-01', '2024-12-31', 'uploads/ca8feeccc92512c3d4658ff4bd45d07b066a39ed-Grey Poupon Dijon Mustard.jpeg', '2023-09-03 20:26:24', '2023-09-03 12:26:24'),
(34, 'Ferrero Rocher Chocolates', 3, 'Luxurious hazelnut-filled chocolates with a crispy shell, wrapped in gold foil.', 19.99, 0, '2022-11-17', '2023-11-23', 'uploads/826ce809ed31af1f2d351d74beec7b1a7caa3331-Ferrero Rocher Chocolates.jpg', '2023-09-03 23:42:49', '2023-09-03 15:42:49'),
(35, 'Organic Avocado', 4, 'Ripe and creamy organic avocados, perfect for salads, guacamole, or a healthy snack.', 2.55, 0, '0023-04-09', '2023-10-09', 'uploads/c83a9404c89f21d8656e6cbd44a24fe01495dd00-Organic Avocado.jpeg', '2023-09-04 15:45:54', '2023-09-04 07:47:33'),
(36, 'Fresh Strawberries', 7, 'Juicy and sweet fresh strawberries, bursting with flavor and great for desserts, smoothies, or snacking.', 19.99, 18.99, '2023-04-09', '2023-10-09', 'uploads/7f5ad2dbd4ecad7f4f93b5b607b0f95917de705f-Fresh Strawberries.jpg', '2023-09-05 03:38:22', '2023-09-18 19:02:28'),
(37, 'Jumbo Gulf Shrimp', 8, 'Large, succulent Gulf shrimp, perfect for grilling, sautéing, or adding to your favorite seafood dishes.', 39.99, 0, '2023-03-09', '2023-09-13', '', '2023-09-05 03:40:25', '2023-09-12 01:53:15'),
(47, 'Electronic Blender', 9, 'High-speed electric blender with multiple blending options.', 59.99, 0, '2022-08-09', '0000-00-00', '', '2023-09-12 09:43:50', '2023-09-18 18:58:31');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

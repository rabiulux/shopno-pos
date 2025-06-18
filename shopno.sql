-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2025 at 08:01 PM
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
-- Database: `shopno`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Fashion', 1, '2025-06-07 00:26:16', '2025-06-07 00:26:16'),
(2, 'Electronics', 1, '2025-06-07 00:44:59', '2025-06-07 00:44:59'),
(3, 'New Arrival', 1, '2025-06-07 00:48:23', '2025-06-07 00:48:23'),
(4, 'New Trend', 1, '2025-06-07 00:49:08', '2025-06-07 03:13:09'),
(7, 'Men\'s', 1, '2025-06-07 03:14:14', '2025-06-07 03:14:14'),
(8, 'Women\'s', 1, '2025-06-07 03:14:27', '2025-06-07 03:14:27'),
(10, 'Mobile', 1, '2025-06-07 11:02:28', '2025-06-07 11:02:28'),
(11, 'Kids', 1, '2025-06-18 11:45:53', '2025-06-18 11:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `mobile`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'MD RABIUL ISLAM', 'rabin@gmail.com', '12346', 1, '2025-06-07 03:54:44', '2025-06-07 03:54:44'),
(3, 'Aziz', 'aziz@gmail.com', '11111', 1, '2025-06-12 02:22:00', '2025-06-12 02:22:00'),
(4, 'Taseen', 'tasween@gmail.com', '123', 1, '2025-06-12 02:22:25', '2025-06-12 02:22:25'),
(8, 'Rafiqul Islam', 'rafiq@gmail.com', '98746321', 1, '2025-06-18 11:45:17', '2025-06-18 11:45:27');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `total` varchar(255) NOT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `vat` varchar(255) DEFAULT NULL,
  `payable` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `total`, `discount`, `vat`, `payable`, `user_id`, `customer_id`, `created_at`, `updated_at`) VALUES
(12, '129774.75', '325.25', '6488.74', '136263.49', 1, 3, '2025-06-18 11:49:50', '2025-06-18 11:49:50'),
(13, '600', '0', '30', '630', 1, 8, '2025-06-17 11:50:33', '2025-06-17 11:50:33'),
(14, '100', '0', '5', '105', 1, 4, '2025-06-18 11:50:47', '2025-06-18 11:50:47'),
(15, '300100', '0', '15005', '315105', 1, 1, '2025-06-18 11:51:21', '2025-06-18 11:51:21');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_products`
--

CREATE TABLE `invoice_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `sale_price` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_products`
--

INSERT INTO `invoice_products` (`id`, `invoice_id`, `user_id`, `product_id`, `quantity`, `sale_price`, `created_at`, `updated_at`) VALUES
(16, 12, 1, 6, '1', '10000.00', '2025-06-18 11:49:50', '2025-06-18 11:49:50'),
(17, 12, 1, 4, '1', '100.00', '2025-06-18 11:49:50', '2025-06-18 11:49:50'),
(18, 12, 1, 7, '1', '120000.00', '2025-06-18 11:49:50', '2025-06-18 11:49:50'),
(19, 13, 1, 9, '1', '600.00', '2025-06-17 11:50:33', '2025-06-17 11:50:33'),
(20, 14, 1, 4, '1', '100.00', '2025-06-18 11:50:47', '2025-06-18 11:50:47'),
(21, 15, 1, 6, '6', '60000.00', '2025-06-18 11:51:21', '2025-06-18 11:51:21'),
(22, 15, 1, 4, '1', '100.00', '2025-06-18 11:51:21', '2025-06-18 11:51:21'),
(23, 15, 1, 7, '2', '240000.00', '2025-06-18 11:51:21', '2025-06-18 11:51:21');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(9, '0001_01_01_000000_create_users_table', 1),
(10, '0001_01_01_000001_create_cache_table', 1),
(11, '0001_01_01_000002_create_jobs_table', 1),
(12, '2025_06_02_142740_add_user_role_column_to_users_table', 1),
(13, '2025_06_07_051635_create_categories_table', 2),
(14, '2025_06_07_081714_create_customers_table', 3),
(16, '2025_06_07_102759_create_products_table', 4),
(17, '2025_06_12_065654_create_invoices_table', 5),
(18, '2025_06_12_071752_create_invoice_products_table', 6),
(19, '2025_06_16_170940_create_suppliers_table', 7),
(20, '2025_06_17_104318_create_purchases_table', 8),
(22, '2025_06_17_105340_create_purchase_products_table', 9),
(24, '2025_06_17_163814_create_product_stocks_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `quantity` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `unit`, `quantity`, `image`, `user_id`, `category_id`, `created_at`, `updated_at`) VALUES
(4, 'Shirt New', 'Mens new', 100.00, 'No', 200, 'images/products/1_1750008349.jpeg', 1, 4, '2025-06-07 06:23:41', '2025-06-15 11:25:49'),
(6, 'Redmi A3', 'Mobile', 10000.00, 'No', 100, 'images/products/1-1749315728.jpeg', 1, 10, '2025-06-07 11:02:08', '2025-06-07 11:18:52'),
(7, 'Sony TV 66', 'new TV', 120000.00, 'No', 20, 'images/products/1-1749318874.jpeg', 1, 2, '2025-06-07 11:54:34', '2025-06-07 18:23:03'),
(8, 'Mercedese new', 'mmm new', 1000000.00, 'no', 100, 'images/products/1_1750008332.jpeg', 1, 3, '2025-06-07 18:31:00', '2025-06-15 11:25:32'),
(9, 'Gents Shirt Half Sleves', 'New Gents Shirt', 600.00, 'Piece', 100, 'images/products/1_1750268847.jpeg', 1, 7, '2025-06-18 11:46:57', '2025-06-18 11:47:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_stocks`
--

CREATE TABLE `product_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_stocks`
--

INSERT INTO `product_stocks` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '7', '2025-06-17 11:16:07', '2025-06-18 11:51:21'),
(2, 1, 6, '15', '2025-06-17 11:16:07', '2025-06-18 11:51:21'),
(3, 1, 9, '9', '2025-06-18 11:48:10', '2025-06-18 11:50:33'),
(4, 1, 8, '1', '2025-06-18 11:48:35', '2025-06-18 11:48:35'),
(5, 1, 7, '13', '2025-06-18 11:49:23', '2025-06-18 11:51:21');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `total` varchar(255) NOT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `vat` varchar(255) DEFAULT NULL,
  `payable` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `total`, `discount`, `vat`, `payable`, `user_id`, `supplier_id`, `created_at`, `updated_at`) VALUES
(2, '100', '0', '5', '105', 1, 3, '2025-06-17 06:25:06', '2025-06-17 06:25:06'),
(3, '10000', '0', '500', '10500', 1, 2, '2025-06-17 08:41:05', '2025-06-17 08:41:05'),
(4, '120000', '0', '6000', '126000', 1, 3, '2025-06-17 08:47:12', '2025-06-17 08:47:12'),
(5, '130000', '0', '6500', '136500', 1, 5, '2025-06-17 08:52:19', '2025-06-17 08:52:19'),
(7, '121000', '0', '6050', '127050', 1, 3, '2025-06-17 11:16:07', '2025-06-17 11:16:07'),
(8, '6000', '0', '300', '6300', 1, 3, '2025-06-18 11:48:10', '2025-06-18 11:48:10'),
(9, '1000000', '0', '50000', '1050000', 1, 2, '2025-06-18 11:48:35', '2025-06-18 11:48:35'),
(10, '100000', '0', '5000', '105000', 1, 5, '2025-06-18 11:48:52', '2025-06-18 11:48:52'),
(11, '1905600', '14400', '95280', '2000880', 1, 4, '2025-06-18 11:49:23', '2025-06-18 11:49:23');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE `purchase_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `purchase_price` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_products`
--

INSERT INTO `purchase_products` (`id`, `user_id`, `purchase_id`, `product_id`, `quantity`, `purchase_price`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 4, '1', '100.00', '2025-06-17 06:25:06', '2025-06-17 06:25:06'),
(2, 1, 3, 6, '1', '10000.00', '2025-06-17 08:41:05', '2025-06-17 08:41:05'),
(3, 1, 4, 7, '1', '120000.00', '2025-06-17 08:47:12', '2025-06-17 08:47:12'),
(4, 1, 5, 6, '1', '10000.00', '2025-06-17 08:52:19', '2025-06-17 08:52:19'),
(5, 1, 5, 7, '1', '120000.00', '2025-06-17 08:52:19', '2025-06-17 08:52:19'),
(9, 1, 7, 4, '10', '1000.00', '2025-06-17 11:16:07', '2025-06-17 11:16:07'),
(10, 1, 7, 6, '12', '120000.00', '2025-06-17 11:16:07', '2025-06-17 11:16:07'),
(11, 1, 8, 9, '10', '6000.00', '2025-06-18 11:48:10', '2025-06-18 11:48:10'),
(12, 1, 9, 8, '1', '1000000.00', '2025-06-18 11:48:35', '2025-06-18 11:48:35'),
(13, 1, 10, 6, '10', '100000.00', '2025-06-18 11:48:52', '2025-06-18 11:48:52'),
(14, 1, 11, 7, '16', '1920000.00', '2025-06-18 11:49:23', '2025-06-18 11:49:23');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('GE5rOrk5pNB3U8OPpIZtE3j7kpjn6Hv2OPbUrMok', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS0FodEFFUFZUYXZGSWlzeGcyUkEyYmNqS1lxbGZFS1FRbXBKdXJURiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zYWxlcy1yZXBvcnQvMjAyNS0wNi0wMS8yMDI1LTA2LTE4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1750269619);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `email`, `mobile`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Unilever', 'contact@unilever.com.bd', '1231', 1, '2025-06-16 18:15:54', '2025-06-16 18:43:19'),
(2, 'Bashundhara Multi Food Products Limited', 'abdullah-faisal@bga-bd.com', '8801313094557', 1, '2025-06-16 18:17:23', '2025-06-16 18:17:23'),
(3, 'ACI', 'contact@aci.com', '1234', 1, '2025-06-16 18:17:55', '2025-06-16 18:17:55'),
(4, 'Pran RFL Group', 'contact@pran-rflgroup.com', '12345', 1, '2025-06-16 18:18:46', '2025-06-16 18:40:59'),
(5, 'Olympic', 'contact@olympic.com', '123456', 1, '2025-06-16 18:19:09', '2025-06-16 18:19:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `role` enum('admin','store_keeper','customer') NOT NULL DEFAULT 'customer',
  `otp` varchar(255) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `role`, `otp`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Rabin', 'admin@gmail.com', '01714581234', 'admin', '0', NULL, '$2y$12$Qtkvw/zEpBkN1bCnXHljGOqORTwDFZ3LR.8LPw8qOHm2YJInfz4Pq', NULL, '2025-06-02 11:09:57', '2025-06-06 22:48:27'),
(2, 'MD RABIUL ISLAM', 'rabin.ku11@gmail.com', '01978561111', 'customer', '0', NULL, '$2y$12$uf630QYGCOUxluH2P8bRXOPu5Qs73ocdynodTkNkVXIrOBpq4ber2', NULL, '2025-06-06 03:38:37', '2025-06-06 03:38:37'),
(3, 'MD RABIUL ISLAM', 'itexpertrabin@gmail.com', '01714581236', 'customer', '0', NULL, '$2y$12$IygkcF0Urs/nTmci5XyvWuHRhYgadI5wlVXGwf37mqNQr.ADvTpD2', NULL, '2025-06-06 10:08:22', '2025-06-18 11:40:40'),
(4, 'MD RABIUL ISLAM', 'rabin123@gmail.com', '12364', 'customer', '405880', NULL, '$2y$12$ONDR/FK.iRvxvC3tRKrST.RVa4hxi/XChbB36mfP9V8ItN8HmtrN.', NULL, '2025-06-18 11:20:58', '2025-06-18 11:21:42'),
(5, 'MD RABIUL ISLAM', 'rabin1@gmail.com', '1239746000', 'customer', '0', NULL, '$2y$12$.zlMOnM3rtEEcmv4ojpwzOPF9.j.c8.WuBpx1L2G4yGz5rP1i7bIK', NULL, '2025-06-18 11:23:32', '2025-06-18 11:23:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD KEY `categories_user_id_foreign` (`user_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`),
  ADD UNIQUE KEY `customers_mobile_unique` (`mobile`),
  ADD KEY `customers_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_user_id_foreign` (`user_id`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `invoice_products`
--
ALTER TABLE `invoice_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_products_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_products_user_id_foreign` (`user_id`),
  ADD KEY `invoice_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_name_unique` (`name`),
  ADD KEY `products_user_id_foreign` (`user_id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_stocks`
--
ALTER TABLE `product_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_stocks_user_id_foreign` (`user_id`),
  ADD KEY `product_stocks_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_user_id_foreign` (`user_id`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_products_user_id_foreign` (`user_id`),
  ADD KEY `purchase_products_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_email_unique` (`email`),
  ADD UNIQUE KEY `suppliers_mobile_unique` (`mobile`),
  ADD KEY `suppliers_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_mobile_unique` (`mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `invoice_products`
--
ALTER TABLE `invoice_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_stocks`
--
ALTER TABLE `product_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `invoice_products`
--
ALTER TABLE `invoice_products`
  ADD CONSTRAINT `invoice_products_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `product_stocks`
--
ALTER TABLE `product_stocks`
  ADD CONSTRAINT `product_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `product_stocks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD CONSTRAINT `purchase_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_products_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 05:14 AM
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
-- Database: `parkaid`
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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-parking_slots', 'a:4:{i:0;a:6:{s:2:\"id\";s:4:\"A-01\";s:6:\"number\";s:2:\"01\";s:5:\"floor\";s:12:\"Ground Floor\";s:4:\"type\";s:7:\"Regular\";s:8:\"occupied\";b:0;s:4:\"time\";s:8:\"just now\";}i:1;a:6:{s:2:\"id\";s:4:\"A-02\";s:6:\"number\";s:2:\"02\";s:5:\"floor\";s:12:\"Ground Floor\";s:4:\"type\";s:7:\"Regular\";s:8:\"occupied\";b:0;s:4:\"time\";s:8:\"just now\";}i:2;a:6:{s:2:\"id\";s:4:\"B-02\";s:6:\"number\";s:2:\"02\";s:5:\"floor\";s:12:\"Ground Floor\";s:4:\"type\";s:7:\"Regular\";s:8:\"occupied\";b:0;s:4:\"time\";s:8:\"just now\";}i:3;a:6:{s:2:\"id\";s:4:\"B-01\";s:6:\"number\";s:2:\"01\";s:5:\"floor\";s:12:\"Ground Floor\";s:4:\"type\";s:7:\"Regular\";s:8:\"occupied\";b:0;s:4:\"time\";s:8:\"just now\";}}', 1778468882);

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
-- Table structure for table `management_logs`
--

CREATE TABLE `management_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `slot_id` varchar(255) DEFAULT NULL,
  `floor` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `logged_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `management_logs`
--

INSERT INTO `management_logs` (`id`, `user_id`, `action`, `slot_id`, `floor`, `type`, `quantity`, `logged_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'slot_added', 'D-1', 'Ground Floor', 'Regular', 1, '2026-05-09 18:59:55', '2026-05-09 18:59:55', '2026-05-09 18:59:55'),
(2, 1, 'slot_added', 'C-1', 'Ground Floor', 'Regular', 1, '2026-05-09 19:08:49', '2026-05-09 19:08:49', '2026-05-09 19:08:49'),
(3, 1, 'slot_deleted', 'B-02', 'Ground Floor', 'Regular', 1, '2026-05-09 19:09:20', '2026-05-09 19:09:20', '2026-05-09 19:09:20'),
(4, 1, 'space_added', 'D-1', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:10:24', '2026-05-09 19:10:24', '2026-05-09 19:10:24'),
(5, 1, 'space_added', 'D-2', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:10:25', '2026-05-09 19:10:25', '2026-05-09 19:10:25'),
(6, 1, 'space_added', 'D-3', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:10:26', '2026-05-09 19:10:26', '2026-05-09 19:10:26'),
(7, 1, 'space_added', 'D-4', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:10:26', '2026-05-09 19:10:26', '2026-05-09 19:10:26'),
(8, 1, 'slot_deleted', 'D-3', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:11:17', '2026-05-09 19:11:17', '2026-05-09 19:11:17'),
(9, 1, 'slot_deleted', 'D-4', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:11:18', '2026-05-09 19:11:18', '2026-05-09 19:11:18'),
(10, 1, 'slot_deleted', 'D-1', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:11:37', '2026-05-09 19:11:37', '2026-05-09 19:11:37'),
(11, 1, 'slot_deleted', 'C-1', 'Ground Floor', 'Regular', 1, '2026-05-09 19:11:38', '2026-05-09 19:11:38', '2026-05-09 19:11:38'),
(12, 1, 'slot_deleted', 'A-02', 'Ground Floor', 'Regular', 1, '2026-05-09 19:11:38', '2026-05-09 19:11:38', '2026-05-09 19:11:38'),
(13, 1, 'slot_deleted', 'A-01', 'Ground Floor', 'Regular', 1, '2026-05-09 19:11:39', '2026-05-09 19:11:39', '2026-05-09 19:11:39'),
(14, 1, 'slot_deleted', 'D-2', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:11:40', '2026-05-09 19:11:40', '2026-05-09 19:11:40'),
(15, 1, 'slot_deleted', 'A-02', 'Ground Floor', 'Regular', 1, '2026-05-09 19:11:47', '2026-05-09 19:11:47', '2026-05-09 19:11:47'),
(16, 1, 'slot_deleted', 'A-01', 'Ground Floor', 'Regular', 1, '2026-05-09 19:11:48', '2026-05-09 19:11:48', '2026-05-09 19:11:48'),
(17, 1, 'slot_deleted', 'D-1', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:11:49', '2026-05-09 19:11:49', '2026-05-09 19:11:49'),
(18, 1, 'slot_deleted', 'C-1', 'Ground Floor', 'Regular', 1, '2026-05-09 19:11:50', '2026-05-09 19:11:50', '2026-05-09 19:11:50'),
(19, 1, 'slot_deleted', 'D-2', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:11:51', '2026-05-09 19:11:51', '2026-05-09 19:11:51'),
(20, 1, 'slot_deleted', 'A-01', 'Ground Floor', 'Regular', 1, '2026-05-09 19:12:21', '2026-05-09 19:12:21', '2026-05-09 19:12:21'),
(21, 1, 'slot_deleted', 'A-02', 'Ground Floor', 'Regular', 1, '2026-05-09 19:12:22', '2026-05-09 19:12:22', '2026-05-09 19:12:22'),
(22, 1, 'slot_deleted', 'C-1', 'Ground Floor', 'Regular', 1, '2026-05-09 19:12:23', '2026-05-09 19:12:23', '2026-05-09 19:12:23'),
(23, 1, 'slot_deleted', 'D-1', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:12:24', '2026-05-09 19:12:24', '2026-05-09 19:12:24'),
(24, 1, 'slot_deleted', 'D-2', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:12:26', '2026-05-09 19:12:26', '2026-05-09 19:12:26'),
(25, 1, 'slot_deleted', 'A-01', 'Ground Floor', 'Regular', 1, '2026-05-09 19:12:49', '2026-05-09 19:12:49', '2026-05-09 19:12:49'),
(26, 1, 'slot_deleted', 'A-02', 'Ground Floor', 'Regular', 1, '2026-05-09 19:12:49', '2026-05-09 19:12:49', '2026-05-09 19:12:49'),
(27, 1, 'slot_deleted', 'C-1', 'Ground Floor', 'Regular', 1, '2026-05-09 19:12:50', '2026-05-09 19:12:50', '2026-05-09 19:12:50'),
(28, 1, 'slot_deleted', 'D-1', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:12:50', '2026-05-09 19:12:50', '2026-05-09 19:12:50'),
(29, 1, 'slot_deleted', 'D-2', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:12:51', '2026-05-09 19:12:51', '2026-05-09 19:12:51'),
(30, 1, 'slot_deleted', 'D-1', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:13:08', '2026-05-09 19:13:08', '2026-05-09 19:13:08'),
(31, 1, 'slot_deleted', 'C-1', 'Ground Floor', 'Regular', 1, '2026-05-09 19:13:09', '2026-05-09 19:13:09', '2026-05-09 19:13:09'),
(32, 1, 'slot_deleted', 'A-02', 'Ground Floor', 'Regular', 1, '2026-05-09 19:13:09', '2026-05-09 19:13:09', '2026-05-09 19:13:09'),
(33, 1, 'slot_deleted', 'A-01', 'Ground Floor', 'Regular', 1, '2026-05-09 19:13:10', '2026-05-09 19:13:10', '2026-05-09 19:13:10'),
(34, 1, 'slot_deleted', 'D-2', 'Floor 1', 'EV Charging', 1, '2026-05-09 19:13:12', '2026-05-09 19:13:12', '2026-05-09 19:13:12');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_09_230312_parking_slots', 2),
(5, '2026_05_09_232719_management_logs', 3),
(6, '2026_05_10_004159_add_user_to_parking_history', 4),
(7, '2026_05_10_004242_add_user_to_management_logs', 4);

-- --------------------------------------------------------

--
-- Table structure for table `parking_history`
--

CREATE TABLE `parking_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slot_id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `floor` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'Regular',
  `status` enum('vacant','occupied') NOT NULL,
  `distance` double DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parking_slots`
--

CREATE TABLE `parking_slots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slot_id` varchar(255) NOT NULL,
  `number` varchar(10) NOT NULL,
  `floor` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'Regular',
  `occupied` tinyint(1) NOT NULL DEFAULT 0,
  `distance` double DEFAULT NULL,
  `last_updated` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parking_slots`
--

INSERT INTO `parking_slots` (`id`, `slot_id`, `number`, `floor`, `type`, `occupied`, `distance`, `last_updated`, `created_at`, `updated_at`) VALUES
(1, 'A-01', '01', 'Ground Floor', 'Regular', 0, NULL, 'just now', '2026-05-09 15:07:07', '2026-05-09 15:07:07'),
(2, 'A-02', '02', 'Ground Floor', 'Regular', 0, NULL, 'just now', '2026-05-09 15:07:07', '2026-05-09 15:07:07'),
(16, 'D-1', '1', 'Floor 1', 'EV Charging', 0, NULL, 'just now', '2026-05-09 18:04:42', '2026-05-09 19:10:24'),
(21, 'C-1', '1', 'Ground Floor', 'Regular', 0, NULL, 'just now', '2026-05-09 19:08:50', '2026-05-09 19:08:50'),
(22, 'D-2', '2', 'Floor 1', 'EV Charging', 0, NULL, 'just now', '2026-05-09 19:10:24', '2026-05-09 19:10:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('kenflekzz@gmail.com', '$2y$12$DvM9rG/79mRssSXI5JXJ5en0wcZz8tRGRzxWh6AVViMoNMquYogl2', '2026-05-02 20:12:00');

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
('ePhkTChhPnjNiQn4QnfBmnIq7a3s44JSyQsH4emI', 1, '192.168.254.104', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicnp5d1lpUVJUd2NOOGpiVzc0RUtIU2VVQm85azJwWmtaRW8walN0bSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xOTIuMTY4LjI1NC4xMDQ6ODAwMC9wYXJraW5nLWZsb29ycyI7czo1OiJyb3V0ZSI7czoxNDoicGFya2luZy5mbG9vcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1778382792);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Kenneth Camasura', 'kenflekzz@gmail.com', NULL, '$2y$12$/EtoyHOvk91yzbGnXyqs6OLJsIXnZ1lJLszdEniGU6Nd97IZn2u8.', 'sML04ypXmwpkeCnVxkVsflpitkDtRJMZqc7IUpY3QXSEkFR6ElOcLdCLnSEc', '2026-05-02 00:26:57', '2026-05-02 21:05:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `management_logs`
--
ALTER TABLE `management_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `management_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking_history`
--
ALTER TABLE `parking_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parking_history_user_id_foreign` (`user_id`);

--
-- Indexes for table `parking_slots`
--
ALTER TABLE `parking_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parking_slots_slot_id_unique` (`slot_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `management_logs`
--
ALTER TABLE `management_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `parking_history`
--
ALTER TABLE `parking_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parking_slots`
--
ALTER TABLE `parking_slots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `management_logs`
--
ALTER TABLE `management_logs`
  ADD CONSTRAINT `management_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `parking_history`
--
ALTER TABLE `parking_history`
  ADD CONSTRAINT `parking_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

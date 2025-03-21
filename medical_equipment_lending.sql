/*
 Navicat Premium Data Transfer

 Source Server         : FinancialReport
 Source Server Type    : MySQL
 Source Server Version : 100428 (10.4.28-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : medical_equipment_lending

 Target Server Type    : MySQL
 Target Server Version : 100428 (10.4.28-MariaDB)
 File Encoding         : 65001

 Date: 21/03/2025 10:12:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for asset_categories
-- ----------------------------
DROP TABLE IF EXISTS `asset_categories`;
CREATE TABLE `asset_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of asset_categories
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for assets
-- ----------------------------
DROP TABLE IF EXISTS `assets`;
CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `status` enum('available','borrowed','damaged','lost') DEFAULT 'available',
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`asset_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `asset_categories` (`category_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of assets
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for borrow_records
-- ----------------------------
DROP TABLE IF EXISTS `borrow_records`;
CREATE TABLE `borrow_records` (
  `borrow_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date NOT NULL,
  `actual_return` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','returned','canceled') DEFAULT 'pending',
  `admin_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`borrow_id`),
  KEY `user_id` (`user_id`),
  KEY `asset_id` (`asset_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `borrow_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `borrow_records_ibfk_2` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE,
  CONSTRAINT `borrow_records_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of borrow_records
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for damaged_assets
-- ----------------------------
DROP TABLE IF EXISTS `damaged_assets`;
CREATE TABLE `damaged_assets` (
  `damage_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `report_date` date NOT NULL,
  `status` enum('pending','under_review','fixed','discarded') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`damage_id`),
  KEY `asset_id` (`asset_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `damaged_assets_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE,
  CONSTRAINT `damaged_assets_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of damaged_assets
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of departments
-- ----------------------------
BEGIN;
INSERT INTO `departments` (`department_id`, `name`, `created_at`, `updated_at`) VALUES (1, 'Health', '2025-03-20 17:31:20', '2025-03-20 17:40:19');
COMMIT;

-- ----------------------------
-- Table structure for lost_assets
-- ----------------------------
DROP TABLE IF EXISTS `lost_assets`;
CREATE TABLE `lost_assets` (
  `lost_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `report_date` date NOT NULL,
  `status` enum('pending','investigating','confirmed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`lost_id`),
  KEY `asset_id` (`asset_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `lost_assets_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE,
  CONSTRAINT `lost_assets_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of lost_assets
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for repair_requests
-- ----------------------------
DROP TABLE IF EXISTS `repair_requests`;
CREATE TABLE `repair_requests` (
  `repair_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `request_date` date NOT NULL,
  `status` enum('pending','in_progress','completed','canceled') DEFAULT 'pending',
  `technician` varchar(100) DEFAULT NULL,
  `repair_cost` decimal(10,2) DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`repair_id`),
  KEY `asset_id` (`asset_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `repair_requests_ibfk_1` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE,
  CONSTRAINT `repair_requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of repair_requests
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for supplies
-- ----------------------------
DROP TABLE IF EXISTS `supplies`;
CREATE TABLE `supplies` (
  `supply_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`supply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of supplies
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for supply_requests
-- ----------------------------
DROP TABLE IF EXISTS `supply_requests`;
CREATE TABLE `supply_requests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `supply_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`request_id`),
  KEY `user_id` (`user_id`),
  KEY `supply_id` (`supply_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `supply_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `supply_requests_ibfk_2` FOREIGN KEY (`supply_id`) REFERENCES `supplies` (`supply_id`) ON DELETE CASCADE,
  CONSTRAINT `supply_requests_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of supply_requests
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','staff','employee') NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` (`user_id`, `username`, `password`, `fullname`, `email`, `phone`, `role`, `department_id`, `created_at`, `updated_at`) VALUES (2, 'admin', '$2y$10$qSghdt3/ATbwo0t7pXhapOOkoUfguFZORLeRSHKrt0DvL5UVlluIe', 'วีระยุทธ มณีกาศ', 'mrkickme101@gmail.com', '0875798053', 'admin', 1, '2025-03-20 18:06:08', '2025-03-20 18:14:46');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

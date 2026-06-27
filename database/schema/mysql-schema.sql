/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `design_asset_id` bigint unsigned NOT NULL,
  `guest_id` bigint unsigned NOT NULL,
  `stamp_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generated_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certificates_design_asset_id_foreign` (`design_asset_id`),
  KEY `certificates_guest_id_foreign` (`guest_id`),
  CONSTRAINT `certificates_design_asset_id_foreign` FOREIGN KEY (`design_asset_id`) REFERENCES `design_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `certificates_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `committee_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `committee_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int NOT NULL,
  `scope` enum('inti','divisi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `committee_roles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `design_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `design_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned DEFAULT NULL,
  `asset_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `design_assets_event_id_foreign` (`event_id`),
  KEY `design_assets_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `design_assets_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `design_assets_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `division_sprints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `division_sprints` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_division_id` bigint unsigned NOT NULL,
  `sprint_number` int unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evt_div_sprint_unique` (`event_division_id`,`sprint_number`),
  CONSTRAINT `division_sprints_event_division_id_foreign` FOREIGN KEY (`event_division_id`) REFERENCES `event_divisions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `divisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `divisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `period_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `singkatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('bph','divisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'divisi',
  `base_points` int NOT NULL DEFAULT '100',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisions_period_id_slug_unique` (`period_id`,`slug`),
  CONSTRAINT `divisions_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_committees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_committees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `committee_role_id` bigint unsigned NOT NULL,
  `event_division_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_committees_event_id_user_id_unique` (`event_id`,`user_id`),
  KEY `event_committees_user_id_foreign` (`user_id`),
  KEY `event_committees_committee_role_id_foreign` (`committee_role_id`),
  KEY `event_committees_event_division_id_foreign` (`event_division_id`),
  CONSTRAINT `event_committees_committee_role_id_foreign` FOREIGN KEY (`committee_role_id`) REFERENCES `committee_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_committees_event_division_id_foreign` FOREIGN KEY (`event_division_id`) REFERENCES `event_divisions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_committees_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_committees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_divisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_divisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_divisions_event_id_slug_unique` (`event_id`,`slug`),
  CONSTRAINT `event_divisions_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `period_id` bigint unsigned NOT NULL,
  `work_program_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `event_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('planning','preparation','ongoing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planning',
  `budget_total` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_period_id_foreign` (`period_id`),
  KEY `events_work_program_id_foreign` (`work_program_id`),
  KEY `events_created_by_foreign` (`created_by`),
  CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `events_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE,
  CONSTRAINT `events_work_program_id_foreign` FOREIGN KEY (`work_program_id`) REFERENCES `work_programs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `guests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('VIP','Dosen','Peserta','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Peserta',
  `attendance_status` tinyint(1) NOT NULL DEFAULT '0',
  `dietary_restrictions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `guests_event_id_foreign` (`event_id`),
  CONSTRAINT `guests_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_quantity` int NOT NULL,
  `available_quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `inventory_loans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_loans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint unsigned NOT NULL,
  `event_id` bigint unsigned DEFAULT NULL,
  `borrower_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL,
  `status` enum('Diajukan','Dipinjam','Dikembalikan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Diajukan',
  `photo_before_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_after_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_loans_inventory_id_foreign` (`inventory_id`),
  KEY `inventory_loans_event_id_foreign` (`event_id`),
  KEY `inventory_loans_borrower_id_foreign` (`borrower_id`),
  CONSTRAINT `inventory_loans_borrower_id_foreign` FOREIGN KEY (`borrower_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_loans_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_loans_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `letters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `letters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('surat_masuk','surat_keluar') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'surat_keluar',
  `draft_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `letter_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_by` bigint unsigned DEFAULT NULL,
  `scan_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `status` enum('drafted','verified','signed_and_uploaded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'drafted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `letters_event_id_foreign` (`event_id`),
  KEY `letters_verified_by_foreign` (`verified_by`),
  KEY `letters_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `letters_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `letters_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `letters_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `medical_inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medical_inventories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `medicine_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `expiration_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `division_id` bigint unsigned NOT NULL,
  `org_position_id` bigint unsigned NOT NULL,
  `position_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `joined_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_user_id_division_id_unique` (`user_id`,`division_id`),
  KEY `members_division_id_foreign` (`division_id`),
  KEY `members_org_position_id_foreign` (`org_position_id`),
  CONSTRAINT `members_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `members_org_position_id_foreign` FOREIGN KEY (`org_position_id`) REFERENCES `org_positions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `org_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `org_positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `org_positions_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `periods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `archived_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `progress_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `progress_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `work_task_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachments` json DEFAULT NULL,
  `status_update` enum('in_progress','completed','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `progress_reports_work_task_id_foreign` (`work_task_id`),
  KEY `progress_reports_user_id_foreign` (`user_id`),
  KEY `progress_reports_reviewed_by_foreign` (`reviewed_by`),
  CONSTRAINT `progress_reports_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `progress_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `progress_reports_work_task_id_foreign` FOREIGN KEY (`work_task_id`) REFERENCES `work_tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rundowns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rundowns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `activity_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('pending','ongoing','delayed','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `pic_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rundowns_event_id_foreign` (`event_id`),
  KEY `rundowns_pic_id_foreign` (`pic_id`),
  CONSTRAINT `rundowns_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rundowns_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sponsors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sponsors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Prospect','Contacted','Terkirim','Deal','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Prospect',
  `pic_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sponsors_event_id_foreign` (`event_id`),
  KEY `sponsors_pic_id_foreign` (`pic_id`),
  CONSTRAINT `sponsors_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sponsors_pic_id_foreign` FOREIGN KEY (`pic_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dietary_restrictions` text COLLATE utf8mb4_unicode_ci,
  `global_role` enum('super_admin','kahim','kadiv','anggota') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'anggota',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `violations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `violations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `point_deduction` int NOT NULL,
  `logged_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `violations_member_id_foreign` (`member_id`),
  KEY `violations_logged_by_foreign` (`logged_by`),
  CONSTRAINT `violations_logged_by_foreign` FOREIGN KEY (`logged_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `violations_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `work_programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_programs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `division_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('event','non_event') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'non_event',
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget_plan` decimal(15,2) DEFAULT NULL,
  `status` enum('draft','planned','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_programs_division_id_foreign` (`division_id`),
  CONSTRAINT `work_programs_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `work_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `work_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `work_program_id` bigint unsigned DEFAULT NULL,
  `event_id` bigint unsigned DEFAULT NULL,
  `division_id` bigint unsigned DEFAULT NULL,
  `event_division_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `attachments` json DEFAULT NULL,
  `revision_note` text COLLATE utf8mb4_unicode_ci,
  `assigned_to` bigint unsigned NOT NULL,
  `assigned_by` bigint unsigned NOT NULL,
  `sprint_number` int unsigned DEFAULT NULL,
  `sprint_start_date` date DEFAULT NULL,
  `sprint_end_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `priority` enum('low','medium','high') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('todo','waiting','revisi','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'todo',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_tasks_work_program_id_foreign` (`work_program_id`),
  KEY `work_tasks_event_id_foreign` (`event_id`),
  KEY `work_tasks_division_id_foreign` (`division_id`),
  KEY `work_tasks_event_division_id_foreign` (`event_division_id`),
  KEY `work_tasks_assigned_to_foreign` (`assigned_to`),
  KEY `work_tasks_assigned_by_foreign` (`assigned_by`),
  CONSTRAINT `work_tasks_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `work_tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `work_tasks_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `work_tasks_event_division_id_foreign` FOREIGN KEY (`event_division_id`) REFERENCES `event_divisions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `work_tasks_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `work_tasks_work_program_id_foreign` FOREIGN KEY (`work_program_id`) REFERENCES `work_programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2026_06_26_000001_create_periods_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2026_06_26_000002_create_org_positions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2026_06_26_000003_create_divisions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2026_06_26_000004_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2026_06_26_000005_create_members_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2026_06_26_000006_create_work_programs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2026_06_26_000007_create_committee_roles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2026_06_26_000008_create_events_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2026_06_26_000009_create_event_divisions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2026_06_26_000010_create_event_committees_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2026_06_26_000011_create_work_tasks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2026_06_26_000012_create_progress_reports_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2026_06_26_000013_create_rundowns_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2026_06_26_000014_create_letters_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2026_06_26_000015_create_guests_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2026_06_26_000016_create_sponsors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2026_06_26_000017_create_design_assets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2026_06_26_000018_create_certificates_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2026_06_26_000019_create_inventories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2026_06_26_000020_create_inventory_loans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2026_06_26_000021_create_violations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2026_06_26_000022_create_medical_inventories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2026_06_26_190545_create_division_sprints_table',1);

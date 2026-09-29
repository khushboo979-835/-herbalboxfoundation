-- ============================================================================
-- NGO Management System - Production Database Schema
-- Database Name: ngo_management
-- MySQL Version: 8.0+ / MariaDB 10.4+
-- Character Set: utf8mb4 / Collation: utf8mb4_unicode_ci / Engine: InnoDB
-- ============================================================================

-- ----------------------------------------------------------------------------
-- For Hostinger/cPanel: Import directly inside your database in phpMyAdmin.
-- If creating locally on root MySQL, you can uncomment the 2 lines below:
-- CREATE DATABASE IF NOT EXISTS `u467991428_ngo_management` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `u467991428_ngo_management`;
-- ----------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================================================
-- 1. SYSTEM, AUTHENTICATION & CONFIGURATION
-- ============================================================================

-- Table 1: admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('super_admin', 'admin', 'editor') NOT NULL DEFAULT 'admin',
  `profile_image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `last_login` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_admins_email` (`email`),
  KEY `idx_admins_status` (`status`),
  KEY `idx_admins_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 2: admin_sessions
DROP TABLE IF EXISTS `admin_sessions`;
CREATE TABLE `admin_sessions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` BIGINT UNSIGNED NOT NULL,
  `session_token` VARCHAR(255) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL DEFAULT '127.0.0.1',
  `user_agent` TEXT DEFAULT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_sessions_token` (`session_token`),
  KEY `idx_sessions_admin_id` (`admin_id`),
  KEY `idx_sessions_expires` (`expires_at`),
  CONSTRAINT `fk_admin_sessions_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 3: site_settings
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_name` VARCHAR(255) NOT NULL DEFAULT 'Seva Arogya & Shiksha Foundation',
  `tagline` VARCHAR(255) DEFAULT 'Serving Humanity Through Healthcare, Education & Community Welfare',
  `logo` VARCHAR(255) DEFAULT NULL,
  `favicon` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(191) NOT NULL DEFAULT 'info@ngoseva.org',
  `phone` VARCHAR(50) NOT NULL DEFAULT '+91 98765 43210',
  `whatsapp` VARCHAR(50) DEFAULT '+919876543210',
  `alternate_phone` VARCHAR(50) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT 'New Delhi',
  `state` VARCHAR(100) DEFAULT 'Delhi',
  `pincode` VARCHAR(20) DEFAULT '110001',
  `google_map_url` TEXT DEFAULT NULL,
  `latitude` DECIMAL(10,8) DEFAULT NULL,
  `longitude` DECIMAL(11,8) DEFAULT NULL,
  `office_hours` VARCHAR(200) DEFAULT 'Mon - Sat: 9:00 AM - 6:00 PM',
  `footer_description` TEXT DEFAULT NULL,
  `copyright_text` VARCHAR(255) DEFAULT 'All Rights Reserved.',
  `default_meta_title` VARCHAR(255) DEFAULT 'Seva Foundation | Healthcare, Education & Community Welfare NGO',
  `default_meta_description` TEXT DEFAULT NULL,
  `default_og_image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 4: social_links
DROP TABLE IF EXISTS `social_links`;
CREATE TABLE `social_links` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `platform` ENUM('facebook', 'instagram', 'youtube', 'twitter', 'whatsapp', 'google_business', 'linkedin') NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `icon` VARCHAR(100) NOT NULL DEFAULT 'fab fa-facebook',
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_social_status` (`status`),
  KEY `idx_social_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 5: about_us
DROP TABLE IF EXISTS `about_us`;
CREATE TABLE `about_us` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `short_description` TEXT DEFAULT NULL,
  `full_description` LONGTEXT DEFAULT NULL,
  `history` LONGTEXT DEFAULT NULL,
  `mission` TEXT DEFAULT NULL,
  `vision` TEXT DEFAULT NULL,
  `objectives` LONGTEXT DEFAULT NULL,
  `values` LONGTEXT DEFAULT NULL,
  `founder_name` VARCHAR(150) DEFAULT NULL,
  `founder_designation` VARCHAR(150) DEFAULT NULL,
  `founder_image` VARCHAR(255) DEFAULT NULL,
  `registration_number` VARCHAR(100) DEFAULT NULL,
  `registration_details` TEXT DEFAULT NULL,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_about_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 6: about_documents
DROP TABLE IF EXISTS `about_documents`;
CREATE TABLE `about_documents` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `document_type` ENUM('registration', 'certificate', 'annual_report', 'other') NOT NULL DEFAULT 'certificate',
  `file_path` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `is_public` TINYINT(1) NOT NULL DEFAULT 1,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_about_docs_type` (`document_type`),
  KEY `idx_about_docs_status` (`status`),
  KEY `idx_about_docs_public` (`is_public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 7: media_files
DROP TABLE IF EXISTS `media_files`;
CREATE TABLE `media_files` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_name` VARCHAR(255) NOT NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_type` VARCHAR(50) NOT NULL,
  `mime_type` VARCHAR(100) NOT NULL,
  `file_size` BIGINT UNSIGNED NOT NULL,
  `uploaded_by` BIGINT UNSIGNED DEFAULT NULL,
  `alt_text` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_media_uploaded_by` (`uploaded_by`),
  KEY `idx_media_type` (`file_type`),
  CONSTRAINT `fk_media_files_admin` FOREIGN KEY (`uploaded_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 8: activity_logs
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` BIGINT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(100) NOT NULL,
  `module` VARCHAR(100) NOT NULL,
  `record_id` BIGINT UNSIGNED DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) NOT NULL DEFAULT '127.0.0.1',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_logs_admin_id` (`admin_id`),
  KEY `idx_logs_module` (`module`),
  KEY `idx_logs_created_at` (`created_at`),
  CONSTRAINT `fk_activity_logs_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 2. PROGRAM & HEALTHCARE/EDUCATION SERVICES
-- ============================================================================

-- Table 9: program_categories
DROP TABLE IF EXISTS `program_categories`;
CREATE TABLE `program_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `icon` VARCHAR(100) DEFAULT 'fa-heartbeat',
  `image` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_program_categories_slug` (`slug`),
  KEY `idx_program_categories_status` (`status`),
  KEY `idx_program_categories_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 10: programs
DROP TABLE IF EXISTS `programs`;
CREATE TABLE `programs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `short_description` TEXT DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `icon` VARCHAR(100) DEFAULT 'fa-hand-holding-heart',
  `target_group` VARCHAR(150) DEFAULT NULL,
  `location` VARCHAR(150) DEFAULT NULL,
  `start_date` DATE DEFAULT NULL,
  `end_date` DATE DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_programs_slug` (`slug`),
  KEY `idx_programs_category_id` (`category_id`),
  KEY `idx_programs_status` (`status`),
  KEY `idx_programs_featured` (`is_featured`),
  KEY `idx_programs_sort` (`sort_order`),
  CONSTRAINT `fk_programs_category` FOREIGN KEY (`category_id`) REFERENCES `program_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 11: healthcare_services
DROP TABLE IF EXISTS `healthcare_services`;
CREATE TABLE `healthcare_services` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `service_type` ENUM('medical_camp', 'blood_donation', 'eye_care', 'dental_care', 'medical_testing', 'medicine_distribution', 'ayurvedic', 'homeopathic', 'allopathic', 'other') NOT NULL DEFAULT 'medical_camp',
  `short_description` TEXT DEFAULT NULL,
  `full_description` LONGTEXT DEFAULT NULL,
  `icon` VARCHAR(100) DEFAULT 'fa-stethoscope',
  `image` VARCHAR(255) DEFAULT NULL,
  `doctor_required` TINYINT(1) NOT NULL DEFAULT 1,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_healthcare_slug` (`slug`),
  KEY `idx_healthcare_type` (`service_type`),
  KEY `idx_healthcare_status` (`status`),
  KEY `idx_healthcare_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 12: education_programs
DROP TABLE IF EXISTS `education_programs`;
CREATE TABLE `education_programs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `class_from` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `class_to` TINYINT UNSIGNED NOT NULL DEFAULT 12,
  `education_type` ENUM('NCERT', 'study_material', 'book_distribution', 'school_support', 'career_guidance', 'health_awareness', 'personality_development', 'other') NOT NULL DEFAULT 'NCERT',
  `image` VARCHAR(255) DEFAULT NULL,
  `school_based` TINYINT(1) NOT NULL DEFAULT 1,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_education_slug` (`slug`),
  KEY `idx_education_type` (`education_type`),
  KEY `idx_education_status` (`status`),
  KEY `idx_education_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 3. HOSPITALS, DOCTORS, DIAGNOSTICS & SCHOOLS
-- ============================================================================

-- Table 13: hospitals
DROP TABLE IF EXISTS `hospitals`;
CREATE TABLE `hospitals` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `pincode` VARCHAR(20) DEFAULT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `services` TEXT DEFAULT NULL,
  `contact_person` VARCHAR(150) DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_hospitals_slug` (`slug`),
  KEY `idx_hospitals_city` (`city`),
  KEY `idx_hospitals_state` (`state`),
  KEY `idx_hospitals_status` (`status`),
  KEY `idx_hospitals_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 14: doctors
DROP TABLE IF EXISTS `doctors`;
CREATE TABLE `doctors` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `qualification` VARCHAR(200) NOT NULL,
  `specialization` VARCHAR(150) NOT NULL,
  `experience` INT UNSIGNED NOT NULL DEFAULT 0,
  `clinic_name` VARCHAR(200) DEFAULT NULL,
  `hospital_id` BIGINT UNSIGNED DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `bio` LONGTEXT DEFAULT NULL,
  `consultation_details` TEXT DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_doctors_slug` (`slug`),
  KEY `idx_doctors_hospital_id` (`hospital_id`),
  KEY `idx_doctors_specialization` (`specialization`),
  KEY `idx_doctors_city` (`city`),
  KEY `idx_doctors_status` (`status`),
  KEY `idx_doctors_featured` (`is_featured`),
  CONSTRAINT `fk_doctors_hospital` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 15: diagnostic_centres
DROP TABLE IF EXISTS `diagnostic_centres`;
CREATE TABLE `diagnostic_centres` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `services` TEXT DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_diag_slug` (`slug`),
  KEY `idx_diag_city` (`city`),
  KEY `idx_diag_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 16: schools
DROP TABLE IF EXISTS `schools`;
CREATE TABLE `schools` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `pincode` VARCHAR(20) DEFAULT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `principal_name` VARCHAR(150) DEFAULT NULL,
  `established_year` INT UNSIGNED DEFAULT NULL,
  `student_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_schools_slug` (`slug`),
  KEY `idx_schools_city` (`city`),
  KEY `idx_schools_state` (`state`),
  KEY `idx_schools_status` (`status`),
  KEY `idx_schools_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 4. PARTNERS & MOU ARCHITECTURE
-- ============================================================================

-- Table 17: partners
DROP TABLE IF EXISTS `partners`;
CREATE TABLE `partners` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_type` ENUM('school', 'doctor', 'hospital', 'diagnostic', 'corporate', 'CSR', 'NGO', 'community', 'other') NOT NULL DEFAULT 'corporate',
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `contact_person` VARCHAR(150) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_partners_slug` (`slug`),
  KEY `idx_partners_type` (`partner_type`),
  KEY `idx_partners_status` (`status`),
  KEY `idx_partners_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 18: mous
DROP TABLE IF EXISTS `mous`;
CREATE TABLE `mous` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `partner_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `mou_type` ENUM('school', 'doctor', 'hospital', 'diagnostic', 'corporate', 'CSR', 'community', 'other') NOT NULL DEFAULT 'school',
  `mou_number` VARCHAR(100) DEFAULT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `document_path` VARCHAR(255) NOT NULL,
  `is_public` TINYINT(1) NOT NULL DEFAULT 1,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_mous_partner_id` (`partner_id`),
  KEY `idx_mous_type` (`mou_type`),
  KEY `idx_mous_start_date` (`start_date`),
  KEY `idx_mous_public` (`is_public`),
  KEY `idx_mous_status` (`status`),
  CONSTRAINT `fk_mous_partner` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 5. EVENTS, ATTENDEES & M:N CONNECTIONS
-- ============================================================================

-- Table 19: events
DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `short_description` TEXT DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `event_date` DATE NOT NULL,
  `start_time` TIME NOT NULL DEFAULT '09:00:00',
  `end_time` TIME NOT NULL DEFAULT '17:00:00',
  `venue` VARCHAR(255) NOT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `organizer` VARCHAR(150) NOT NULL DEFAULT 'Seva Foundation',
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `registration_required` TINYINT(1) NOT NULL DEFAULT 1,
  `registration_deadline` DATE DEFAULT NULL,
  `max_participants` INT UNSIGNED NOT NULL DEFAULT 200,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_events_slug` (`slug`),
  KEY `idx_events_category_id` (`category_id`),
  KEY `idx_events_date` (`event_date`),
  KEY `idx_events_city` (`city`),
  KEY `idx_events_status` (`status`),
  KEY `idx_events_featured` (`is_featured`),
  CONSTRAINT `fk_events_category` FOREIGN KEY (`category_id`) REFERENCES `program_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 20: event_doctors (M:N)
DROP TABLE IF EXISTS `event_doctors`;
CREATE TABLE `event_doctors` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` BIGINT UNSIGNED NOT NULL,
  `doctor_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_event_doctor_unique` (`event_id`, `doctor_id`),
  KEY `idx_event_doctors_event` (`event_id`),
  KEY `idx_event_doctors_doctor` (`doctor_id`),
  CONSTRAINT `fk_event_doctors_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_event_doctors_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 21: event_partners (M:N)
DROP TABLE IF EXISTS `event_partners`;
CREATE TABLE `event_partners` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` BIGINT UNSIGNED NOT NULL,
  `partner_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_event_partner_unique` (`event_id`, `partner_id`),
  KEY `idx_event_partners_event` (`event_id`),
  KEY `idx_event_partners_partner` (`partner_id`),
  CONSTRAINT `fk_event_partners_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_event_partners_partner` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 22: event_registrations
DROP TABLE IF EXISTS `event_registrations`;
CREATE TABLE `event_registrations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(191) DEFAULT NULL,
  `age` TINYINT UNSIGNED DEFAULT NULL,
  `gender` ENUM('Male', 'Female', 'Other') DEFAULT 'Male',
  `city` VARCHAR(100) NOT NULL,
  `message` TEXT DEFAULT NULL,
  `registration_status` ENUM('pending', 'confirmed', 'cancelled', 'attended') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_registrations_event_id` (`event_id`),
  KEY `idx_registrations_status` (`registration_status`),
  KEY `idx_registrations_phone` (`phone`),
  CONSTRAINT `fk_event_registrations_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 6. DONATIONS, VOLUNTEERS & ENQUIRIES
-- ============================================================================

-- Table 23: donations
DROP TABLE IF EXISTS `donations`;
CREATE TABLE `donations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `donation_id` VARCHAR(100) NOT NULL,
  `donor_name` VARCHAR(150) NOT NULL,
  `donor_email` VARCHAR(191) NOT NULL,
  `donor_phone` VARCHAR(50) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `purpose` VARCHAR(150) NOT NULL DEFAULT 'General Donation',
  `payment_gateway` VARCHAR(50) NOT NULL DEFAULT 'razorpay',
  `payment_order_id` VARCHAR(150) DEFAULT NULL,
  `payment_id` VARCHAR(150) DEFAULT NULL,
  `payment_signature` VARCHAR(255) DEFAULT NULL,
  `payment_status` ENUM('pending', 'success', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
  `receipt_number` VARCHAR(100) DEFAULT NULL,
  `transaction_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_donations_donation_id` (`donation_id`),
  KEY `idx_donations_payment_id` (`payment_id`),
  KEY `idx_donations_order_id` (`payment_order_id`),
  KEY `idx_donations_status` (`payment_status`),
  KEY `idx_donations_tx_date` (`transaction_date`),
  KEY `idx_donations_email` (`donor_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 24: volunteers
DROP TABLE IF EXISTS `volunteers`;
CREATE TABLE `volunteers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_id` VARCHAR(100) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `age` TINYINT UNSIGNED DEFAULT NULL,
  `gender` ENUM('Male', 'Female', 'Other') DEFAULT 'Male',
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `qualification` VARCHAR(150) DEFAULT NULL,
  `occupation` VARCHAR(150) DEFAULT NULL,
  `interests` TEXT DEFAULT NULL,
  `experience` TEXT DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `resume_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending', 'approved', 'rejected', 'inactive') NOT NULL DEFAULT 'pending',
  `admin_notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_volunteers_app_id` (`application_id`),
  KEY `idx_volunteers_email` (`email`),
  KEY `idx_volunteers_phone` (`phone`),
  KEY `idx_volunteers_city` (`city`),
  KEY `idx_volunteers_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 25: volunteer_interests
DROP TABLE IF EXISTS `volunteer_interests`;
CREATE TABLE `volunteer_interests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `volunteer_id` BIGINT UNSIGNED NOT NULL,
  `interest` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_volunteer_interests_vol_id` (`volunteer_id`),
  CONSTRAINT `fk_vol_interests_volunteer` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 26: partnership_enquiries
DROP TABLE IF EXISTS `partnership_enquiries`;
CREATE TABLE `partnership_enquiries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `organization_name` VARCHAR(255) NOT NULL,
  `contact_person` VARCHAR(150) NOT NULL,
  `organization_type` ENUM('school', 'doctor', 'hospital', 'diagnostic', 'corporate', 'CSR', 'community', 'other') NOT NULL DEFAULT 'corporate',
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `partnership_interest` LONGTEXT NOT NULL,
  `message` TEXT DEFAULT NULL,
  `document_path` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('new', 'contacted', 'approved', 'rejected', 'converted') NOT NULL DEFAULT 'new',
  `admin_notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_partnership_enq_status` (`status`),
  KEY `idx_partnership_enq_type` (`organization_type`),
  KEY `idx_partnership_enq_city` (`city`),
  KEY `idx_partnership_enq_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 27: contact_enquiries
DROP TABLE IF EXISTS `contact_enquiries`;
CREATE TABLE `contact_enquiries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(191) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` LONGTEXT NOT NULL,
  `status` ENUM('new', 'read', 'replied', 'closed') NOT NULL DEFAULT 'new',
  `admin_notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_contact_status` (`status`),
  KEY `idx_contact_email` (`email`),
  KEY `idx_contact_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 7. MEDIA GALLERY, VIDEOS, BLOG & TESTIMONIALS
-- ============================================================================

-- Table 28: gallery_categories
DROP TABLE IF EXISTS `gallery_categories`;
CREATE TABLE `gallery_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_gallery_categories_slug` (`slug`),
  KEY `idx_gallery_categories_status` (`status`),
  KEY `idx_gallery_categories_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 29: gallery_images
DROP TABLE IF EXISTS `gallery_images`;
CREATE TABLE `gallery_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `alt_text` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_gallery_images_cat_id` (`category_id`),
  KEY `idx_gallery_images_status` (`status`),
  KEY `idx_gallery_images_sort` (`sort_order`),
  CONSTRAINT `fk_gallery_images_category` FOREIGN KEY (`category_id`) REFERENCES `gallery_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 30: videos
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `video_url` VARCHAR(255) NOT NULL,
  `thumbnail` VARCHAR(255) DEFAULT NULL,
  `category_id` BIGINT UNSIGNED DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_videos_category_id` (`category_id`),
  KEY `idx_videos_status` (`status`),
  KEY `idx_videos_sort` (`sort_order`),
  CONSTRAINT `fk_videos_category` FOREIGN KEY (`category_id`) REFERENCES `gallery_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 31: blog_categories
DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE `blog_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_blog_categories_slug` (`slug`),
  KEY `idx_blog_categories_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 32: blog_posts
DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `author_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `excerpt` TEXT DEFAULT NULL,
  `content` LONGTEXT NOT NULL,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `seo_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `focus_keyword` VARCHAR(150) DEFAULT NULL,
  `canonical_url` VARCHAR(255) DEFAULT NULL,
  `og_image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_blog_posts_slug` (`slug`),
  KEY `idx_blog_posts_category` (`category_id`),
  KEY `idx_blog_posts_author` (`author_id`),
  KEY `idx_blog_posts_status` (`status`),
  KEY `idx_blog_posts_published` (`published_at`),
  CONSTRAINT `fk_blog_posts_category` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_blog_posts_author` FOREIGN KEY (`author_id`) REFERENCES `admins` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 33: blog_tags
DROP TABLE IF EXISTS `blog_tags`;
CREATE TABLE `blog_tags` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_blog_tags_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 34: blog_post_tags (M:N)
DROP TABLE IF EXISTS `blog_post_tags`;
CREATE TABLE `blog_post_tags` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT UNSIGNED NOT NULL,
  `tag_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_blog_post_tag_unique` (`post_id`, `tag_id`),
  KEY `idx_post_tags_post` (`post_id`),
  KEY `idx_post_tags_tag` (`tag_id`),
  CONSTRAINT `fk_post_tags_post` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_post_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `blog_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 35: testimonials
DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `designation` VARCHAR(150) DEFAULT NULL,
  `organization` VARCHAR(200) DEFAULT NULL,
  `testimonial` TEXT NOT NULL,
  `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_testimonials_status` (`status`),
  KEY `idx_testimonials_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 36: impact_statistics
DROP TABLE IF EXISTS `impact_statistics`;
CREATE TABLE `impact_statistics` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `value` VARCHAR(50) NOT NULL,
  `suffix` VARCHAR(20) DEFAULT '+',
  `icon` VARCHAR(100) DEFAULT 'fa-heartbeat',
  `description` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_impact_status` (`status`),
  KEY `idx_impact_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 8. AYURVEDIC STORE & SEO SETTINGS
-- ============================================================================

-- Table 37: product_categories
DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_product_cat_slug` (`slug`),
  KEY `idx_product_cat_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 38: products
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `sku` VARCHAR(100) DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `stock` INT UNSIGNED NOT NULL DEFAULT 0,
  `image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('draft', 'published', 'inactive') NOT NULL DEFAULT 'published',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_products_slug` (`slug`),
  KEY `idx_products_category` (`category_id`),
  KEY `idx_products_status` (`status`),
  KEY `idx_products_featured` (`is_featured`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 39: seo_settings
DROP TABLE IF EXISTS `seo_settings`;
CREATE TABLE `seo_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_type` VARCHAR(100) NOT NULL,
  `page_id` BIGINT UNSIGNED DEFAULT NULL,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `focus_keyword` VARCHAR(150) DEFAULT NULL,
  `canonical_url` VARCHAR(255) DEFAULT NULL,
  `og_title` VARCHAR(255) DEFAULT NULL,
  `og_description` TEXT DEFAULT NULL,
  `og_image` VARCHAR(255) DEFAULT NULL,
  `robots` VARCHAR(100) DEFAULT 'index, follow',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_seo_page_type` (`page_type`),
  KEY `idx_seo_page_id` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
-- ============================================================================
-- NGO Management System - Production Demo Seed Data
-- Database: ngo_management
-- Description: Realistic production-safe demo seed data for all 39 tables
-- ============================================================================

-- USE `u467991428_ngo_management`;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- 1. Admins
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `admins`;
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `profile_image`, `status`, `created_at`) VALUES
(1, 'Super Administrator', 'admin@ngoseva.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'assets/images/team/admin-1.jpg', 'published', NOW()),
(2, 'Dr. Arvind Sharma (Medical Director)', 'arvind.sharma@ngoseva.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'assets/images/team/admin-2.jpg', 'published', NOW()),
(3, 'Pooja Verma (Content Editor)', 'pooja.verma@ngoseva.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'editor', 'assets/images/team/admin-3.jpg', 'published', NOW());

-- ----------------------------------------------------------------------------
-- 2. Admin Sessions
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `admin_sessions`;
INSERT INTO `admin_sessions` (`id`, `admin_id`, `session_token`, `ip_address`, `user_agent`, `expires_at`, `created_at`) VALUES
(1, 1, 'seed_session_token_super_admin_88f9a0c12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', DATE_ADD(NOW(), INTERVAL 7 DAY), NOW());

-- ----------------------------------------------------------------------------
-- 3. Site Settings
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `site_settings`;
INSERT INTO `site_settings` (`id`, `site_name`, `tagline`, `logo`, `favicon`, `email`, `phone`, `whatsapp`, `alternate_phone`, `address`, `city`, `state`, `pincode`, `google_map_url`, `office_hours`, `footer_description`, `copyright_text`, `default_meta_title`, `default_meta_description`, `default_og_image`) VALUES
(1, 'Herbalbox & Seva Arogya Foundation', 'Empowering Lives Through Integrative Healthcare, Quality Education & Rural Welfare', 'assets/images/logo.png', 'assets/images/favicon.png', 'contact@herbalboxfoundation.org', '+91 98765 43210', '+919876543210', '+91 11 2345 6789', 'Plot No. 45, Institutional Area, Sector 62', 'Noida', 'Uttar Pradesh', '201309', 'https://maps.google.com/?q=Noida+Sector+62', 'Mon - Sat: 9:00 AM - 6:30 PM (Emergency 24x7)', 'Herbalbox & Seva Arogya Foundation is a registered non-profit trust dedicated to holistic healthcare, NCERT-aligned digital education, free mobile medical camps, and community development across underprivileged regions.', '© 2026 Herbalbox & Seva Arogya Foundation. All Rights Reserved. Reg. Under 80G & 12A.', 'Herbalbox Foundation | Healthcare, Education & Rural Welfare NGO', 'Empowering communities through free healthcare camps, AYUSH wellness, NCERT school education, and disaster relief.', 'assets/images/og-default.jpg');

-- ----------------------------------------------------------------------------
-- 4. Social Links
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `social_links`;
INSERT INTO `social_links` (`id`, `platform`, `url`, `icon`, `status`, `sort_order`) VALUES
(1, 'facebook', 'https://facebook.com/herbalboxfoundation', 'fab fa-facebook-f', 'published', 1),
(2, 'instagram', 'https://instagram.com/herbalboxfoundation', 'fab fa-instagram', 'published', 2),
(3, 'youtube', 'https://youtube.com/@herbalboxfoundation', 'fab fa-youtube', 'published', 3),
(4, 'twitter', 'https://twitter.com/herbalboxngo', 'fab fa-x-twitter', 'published', 4),
(5, 'whatsapp', 'https://wa.me/919876543210', 'fab fa-whatsapp', 'published', 5),
(6, 'linkedin', 'https://linkedin.com/company/herbalboxfoundation', 'fab fa-linkedin-in', 'published', 6);

-- ----------------------------------------------------------------------------
-- 5. About Us
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `about_us`;
INSERT INTO `about_us` (`id`, `title`, `subtitle`, `story`, `vision`, `mission`, `values`, `history`, `legal_info`, `registration_number`, `pan_number`, `tax_80g_number`, `tax_12a_number`, `fcra_number`, `csr_number`, `featured_image`, `video_url`) VALUES
(1, 'Bridging the Healthcare and Education Divide', 'Serving Over 150,000 Lives Across 180+ Villages and Urban Slums', 
'Founded with the conviction that quality healthcare and modern education are fundamental human rights, Herbalbox & Seva Arogya Foundation operates at the grassroots level. We bring together certified allopathic doctors, traditional AYUSH practitioners, educationists, and corporate partners to deliver sustainable community welfare.',
'To build a healthy, educated, and self-reliant society where every underprivileged child receives holistic education and every rural family has access to free, compassionate healthcare.',
'To conduct 500+ free medical, eye, dental, and AYUSH wellness camps annually; to partner with 100+ schools for smart digital NCERT classrooms; and to ensure zero preventable disease deaths in our operational clusters.',
'Integrity, Compassion, Transparency, Inclusiveness, and Scientific Rigour in Community Service.',
'Established in 2018 as a small volunteer health drive in rural North India, the foundation has expanded into a multi-state humanitarian initiative with dedicated mobile diagnostic vans, 12 partner hospitals, and 45 affiliated schools.',
'Herbalbox Foundation is a registered Public Charitable Trust under the Indian Trusts Act, 1882, holding valid 12A, 80G Tax Exemption, and CSR Form CSR-1 registrations.',
'TRUST/REG/2018/DEL/9482', 'AABTH8891C', 'CIT(E)/DELHI/80G/2021/A/10294', 'CIT(E)/DELHI/12A/2021/A/9841', 'FCRA-231661849', 'CSR00039281',
'assets/images/about/about-main.jpg', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');

-- ----------------------------------------------------------------------------
-- 6. About Documents
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `about_documents`;
INSERT INTO `about_documents` (`id`, `title`, `document_type`, `file_path`, `file_size`, `description`, `status`, `sort_order`) VALUES
(1, '80G Income Tax Exemption Certificate', '80g_certificate', 'uploads/documents/80g_certificate_herbalbox.pdf', '1.2 MB', 'Tax exemption certificate under Section 80G(5)(vi) of the Income Tax Act, 1961.', 'published', 1),
(2, '12A Registration Order', '12a_certificate', 'uploads/documents/12a_certificate_herbalbox.pdf', '980 KB', 'Order of registration under Section 12AA / 12AB of the Income Tax Act.', 'published', 2),
(3, 'CSR-1 Ministry of Corporate Affairs Registration', 'csr_approval', 'uploads/documents/csr1_approval_mca.pdf', '650 KB', 'Registration for undertaking CSR activities issued by Office of the Registrar of Companies.', 'published', 3),
(4, 'Annual Audit Report 2024-2025', 'audit_report', 'uploads/documents/annual_audit_report_2025.pdf', '3.8 MB', 'Comprehensive statutory audited financial statements and balance sheet.', 'published', 4),
(5, 'Annual Humanitarian Impact Report 2025', 'annual_report', 'uploads/documents/annual_impact_report_2025.pdf', '5.4 MB', 'Summary of all medical camps, school adoptions, and patient impact metrics.', 'published', 5),
(6, 'Trust Deed & Registration Certification', 'registration_certificate', 'uploads/documents/trust_deed_certificate.pdf', '2.1 MB', 'Official registered trust deed copy from Registrar of Public Trusts.', 'published', 6);

-- ----------------------------------------------------------------------------
-- 7. Media Files
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `media_files`;
INSERT INTO `media_files` (`id`, `file_name`, `original_name`, `file_path`, `file_type`, `mime_type`, `file_size`, `uploaded_by`) VALUES
(1, 'hero-medical-camp.jpg', 'Medical Camp Village Drive.jpg', 'uploads/media/hero-medical-camp.jpg', 'image', 'image/jpeg', 450200, 1),
(2, 'school-ncert-lab.jpg', 'Smart Classroom NCERT Lab.jpg', 'uploads/media/school-ncert-lab.jpg', 'image', 'image/jpeg', 512000, 1),
(3, 'annual-report-2025.pdf', 'Herbalbox Annual Report 2025.pdf', 'uploads/documents/annual-report-2025.pdf', 'document', 'application/pdf', 3980000, 1);

-- ----------------------------------------------------------------------------
-- 8. Activity Logs
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `activity_logs`;
INSERT INTO `activity_logs` (`id`, `admin_id`, `module`, `action`, `record_id`, `description`, `ip_address`) VALUES
(1, 1, 'system', 'create', 1, 'Initial database setup and baseline configuration deployed.', '127.0.0.1'),
(2, 1, 'events', 'create', 1, 'Created Mega Free Multi-Specialty Health Camp 2026.', '127.0.0.1'),
(3, 2, 'doctors', 'update', 1, 'Updated OPD consultation timings for Dr. Arvind Sharma.', '127.0.0.1');

-- ----------------------------------------------------------------------------
-- 9. Program Categories
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `program_categories` (`id`, `name`, `slug`, `description`, `icon`, `status`, `sort_order`) VALUES
(1, 'Healthcare & Medical Outreach', 'healthcare-medical-outreach', 'Primary, secondary, diagnostic and preventive healthcare outreach for rural and slum communities.', 'fas fa-heartbeat', 'published', 1),
(2, 'Integrative AYUSH Wellness', 'integrative-ayush-wellness', 'Ayurveda, Homeopathy, Yoga, Naturopathy and Meditation for holistic community well-being.', 'fas fa-spa', 'published', 2),
(3, 'Education & Child Development', 'education-child-development', 'NCERT-aligned smart school education, STEM labs, scholarships, and remedial teaching.', 'fas fa-graduation-cap', 'published', 3),
(4, 'Emergency Relief & Welfare', 'emergency-relief-welfare', 'Disaster relief, ration distribution, women empowerment, and winter blanket drives.', 'fas fa-hands-helping', 'published', 4);

-- ----------------------------------------------------------------------------
-- 10. Programs
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `programs`;
INSERT INTO `programs` (`id`, `category_id`, `title`, `slug`, `short_description`, `full_description`, `featured_image`, `target_beneficiaries`, `achieved_beneficiaries`, `status`, `sort_order`) VALUES
(1, 1, 'Mobile Multi-Specialty Health Clinics', 'mobile-multi-specialty-health-clinics', 'Equipped mobile medical vans providing doctor consultations, ECG, blood sugar, and free medicines.', 'Our fleet of specialized mobile clinics visits hard-to-reach rural hamlets every week. Each van carries an MBBS physician, a pharmacist, point-of-care diagnostics, and essential medications to serve patients who live far from primary health centres.', 'assets/images/programs/mobile-clinic.jpg', '50,000+ Villagers / Year', '64,200 Treated', 'published', 1),
(2, 1, 'Netra Jyoti Free Eye Care & Cataract Surgery', 'netra-jyoti-free-eye-care-cataract-surgery', 'Comprehensive vision screening, free prescription spectacles, and subsidized cataract IOL surgeries.', 'Preventing avoidable blindness through computerized eye testing, distribution of free reading glasses, and safe modern Phaco cataract surgeries in partnership with super-specialty eye hospitals.', 'assets/images/programs/eye-care.jpg', '20,000 Screenings', '18,500 Spectacles & Surgeries', 'published', 2),
(3, 2, 'Ayush Arogya & Herbal Medicine Initiative', 'ayush-arogya-herbal-medicine-initiative', 'Free Ayurvedic pulse diagnosis (Nadi Pariksha), classical herbal remedies, and lifestyle coaching.', 'Promoting traditional Indian medicine through expert Ayurvedic physicians, authentic herbal formulation distribution, and community medicinal herb plantation drives.', 'assets/images/programs/ayurveda.jpg', '30,000 Families', '32,100 Beneficiaries', 'published', 3),
(4, 3, 'NCERT Smart School & Digital Gyan Kendra', 'ncert-smart-school-digital-gyan-kendra', 'Equipping rural government and low-income private schools with interactive digital NCERT syllabus kits.', 'Upgrading classrooms with smart TVs, animated bilingual NCERT curriculum software, science experiment kits, and teacher capacity building workshops for Classes 1 to 12.', 'assets/images/programs/smart-classroom.jpg', '100 Schools / 25,000 Students', '48 Schools Transformed', 'published', 4);

-- ----------------------------------------------------------------------------
-- 11. Healthcare Services
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `healthcare_services`;
INSERT INTO `healthcare_services` (`id`, `name`, `slug`, `type`, `description`, `icon`, `image`, `is_free`, `price`, `timing`, `status`, `sort_order`) VALUES
(1, 'General Medicine & OPD Consultations', 'general-medicine-opd', 'allopathy', 'Comprehensive physical examination, chronic disease management (diabetes, hypertension), and clinical referrals.', 'fas fa-stethoscope', 'assets/images/services/general-opd.jpg', 1, 0.00, 'Mon-Sat: 9:00 AM - 1:00 PM', 'published', 1),
(2, 'Ayurvedic Pulse Diagnosis & Treatment', 'ayurvedic-nadi-pariksha', 'ayurveda', 'Personalized Nadi Pariksha by certified BAMS/MD doctors with dietary recommendations and herbal medicines.', 'fas fa-leaf', 'assets/images/services/ayurveda.jpg', 1, 0.00, 'Tue, Thu, Sat: 10:00 AM - 3:00 PM', 'published', 2),
(3, 'Homeopathic Chronic Disease Clinic', 'homeopathic-clinic', 'homeopathy', 'Gentle, individualized constitutional homeopathy for allergic disorders, skin issues, arthritis, and pediatric immunity.', 'fas fa-pills', 'assets/images/services/homeopathy.jpg', 1, 0.00, 'Mon, Wed, Fri: 10:00 AM - 2:00 PM', 'published', 3),
(4, 'Vision Testing & Refraction Clinic', 'vision-testing-refraction', 'eye_care', 'Digital autorefractor examination, visual acuity assessment, and doorstep prescription glass distribution.', 'fas fa-eye', 'assets/images/services/eye-testing.jpg', 1, 0.00, 'Daily: 9:30 AM - 4:30 PM', 'published', 4),
(5, 'Preventive Dental & Oral Hygiene Clinic', 'preventive-dental-clinic', 'dental_care', 'Ultrasonic dental scaling, cavity restorations, fluoride applications, and oral cancer screening.', 'fas fa-tooth', 'assets/images/services/dental.jpg', 1, 0.00, 'Mon-Fri: 10:00 AM - 4:00 PM', 'published', 5),
(6, 'Therapeutic Yoga & Pranayama Sessions', 'therapeutic-yoga-pranayama', 'yoga', 'Daily morning community yoga, breathing exercises, and meditation workshops for stress reduction and heart health.', 'fas fa-child', 'assets/images/services/yoga.jpg', 1, 0.00, 'Daily: 6:00 AM - 7:30 AM', 'published', 6);

-- ----------------------------------------------------------------------------
-- 12. Education Programs
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `education_programs`;
INSERT INTO `education_programs` (`id`, `title`, `slug`, `category`, `class_range`, `curriculum`, `duration`, `description`, `facilities`, `image`, `status`, `sort_order`) VALUES
(1, 'Primary NCERT Foundational Literacy & Numeracy', 'primary-ncert-fln-program', 'primary', 'Class 1 to 5', 'NCERT / State Board', '1 Academic Year (Ongoing)', 'Foundational reading, writing, and arithmetic mastery using playful activity-based kits and audio-visual modules.', 'Interactive tablets, illustrated storybooks, math learning kits, clean drinking water.', 'assets/images/education/primary-fln.jpg', 'published', 1),
(2, 'Middle & Secondary STEM Innovation Lab', 'middle-secondary-stem-lab', 'secondary', 'Class 6 to 10', 'NCERT / CBSE Aligned', 'Continuous Academic Support', 'Hands-on physics, chemistry, biology, and robotics laboratory exposure designed to spark scientific curiosity.', 'Do-It-Yourself science kits, digital microscopes, 3D anatomy charts, computer workstations.', 'assets/images/education/stem-lab.jpg', 'published', 2),
(3, 'Senior Secondary Board & Career Mentorship', 'senior-secondary-career-mentorship', 'higher_secondary', 'Class 11 to 12', 'NCERT (Science / Commerce / Arts)', '2 Academic Years', 'Intensive coaching for Class 12 board examinations alongside career guidance for CUET, NEET, and JEE entrance tests.', 'Subject expert guest lectures, mock test series, digital question banks, career counselling.', 'assets/images/education/career-mentorship.jpg', 'published', 3),
(4, 'Digital Literacy & Computer Skills Center', 'digital-literacy-computer-skills', 'skill_development', 'Open (Ages 12-25)', 'NSDC / Foundation Certified', '3 Months Certified Course', 'Basic computing, MS Office, typing, web research, cybersecurity hygiene, and introductory coding.', 'Dedicated 20-node computer lab, high-speed fiber internet, projector, certified instructor.', 'assets/images/education/computer-lab.jpg', 'published', 4);

-- ----------------------------------------------------------------------------
-- 13. Hospitals
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `hospitals`;
INSERT INTO `hospitals` (`id`, `name`, `type`, `registration_number`, `contact_person`, `email`, `phone`, `emergency_phone`, `address`, `city`, `state`, `pincode`, `google_map_link`, `mou_signed`, `mou_date`, `services_offered`, `status`) VALUES
(1, 'Sanjeevani Super Specialty Hospital & Research Centre', 'partner', 'HOSP/DL/2017/0491', 'Dr. Ramesh Chandra (Medical Supdt)', 'info@sanjeevanihospital.org', '+91 11 4455 6677', '+91 11 4455 6699', 'Sector 12, Dwarka', 'New Delhi', 'Delhi', '110075', 'https://maps.google.com/?q=Dwarka+Sector+12', 1, '2023-04-15', 'General Surgery, Cardiology, Free Emergency ICU Care for Referred NGO Beneficiaries, Dialysis support.', 'published'),
(2, 'Drishti Memorial Charitable Eye Hospital', 'charitable', 'EYE/UP/2019/1182', 'Dr. Sunita Aggarwal', 'drishtieye@gmail.com', '+91 120 2884 991', '+91 98112 34567', 'Civil Lines', 'Meerut', 'Uttar Pradesh', '250001', 'https://maps.google.com/?q=Meerut+Civil+Lines', 1, '2022-09-01', 'Phaco Cataract Surgeries, Cornea Clinics, Glaucoma screening, Diabetic Retinopathy laser treatments.', 'published'),
(3, 'Patanjali & Dhanvantari Ayurvedic Hospital', 'partner', 'AYUSH/HR/2021/5520', 'Vaidya Harishankar Joshi', 'dhanvantari.ayush@gmail.com', '+91 130 2233 445', '+91 94160 12345', 'GT Road, Murthal', 'Sonipat', 'Haryana', '131027', 'https://maps.google.com/?q=Murthal+Sonipat', 1, '2024-01-10', 'Panchakarma therapy, Chronic pain management, Herbal detox, Lifestyle and yoga counseling.', 'published');

-- ----------------------------------------------------------------------------
-- 14. Doctors
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `doctors`;
INSERT INTO `doctors` (`id`, `hospital_id`, `name`, `type`, `specialization`, `qualification`, `registration_number`, `phone`, `email`, `experience_years`, `bio`, `profile_image`, `available_days`, `available_time`, `is_volunteer`, `status`) VALUES
(1, 1, 'Dr. Arvind Sharma', 'allopathy', 'General Physician & Cardiologist', 'MBBS, MD (General Medicine), FCCP', 'MCI-38491-DEL', '+91 98765 01001', 'arvind.sharma@ngoseva.org', 16, 'Senior consultant passionate about grassroots preventive cardiology and rural healthcare access.', 'assets/images/doctors/dr-arvind.jpg', 'Mon, Wed, Fri, Sat', '09:00 AM - 01:00 PM', 1, 'published'),
(2, 2, 'Dr. Sunita Aggarwal', 'eye_specialist', 'Ophthalmologist & Cornea Specialist', 'MBBS, MS (Ophthalmology), FICO', 'UPMC-29401', '+91 98765 01002', 'drsunita.eye@gmail.com', 14, 'Has performed over 8,000 successful cataract and corneal procedures for marginalized rural patients.', 'assets/images/doctors/dr-sunita.jpg', 'Tue, Thu, Sat', '10:00 AM - 04:00 PM', 1, 'published'),
(3, 3, 'Vaidya Harishankar Joshi', 'ayurveda', 'Senior Ayurvedic Physician & Nadi Vaidya', 'BAMS, MD (Ayurveda - Kayachikitsa)', 'CCIM/AYU/8812', '+91 98765 01003', 'vaidya.joshi@gmail.com', 20, 'Renowned expert in classical Ayurvedic formulations, arthritis reversal, and metabolic disorders.', 'assets/images/doctors/vaidya-joshi.jpg', 'Mon, Tue, Thu, Fri', '11:00 AM - 03:30 PM', 1, 'published'),
(4, NULL, 'Dr. Meenakshi Iyer', 'dental', 'Dental Surgeon & Pedodontist', 'BDS, MDS (Pediatric Dentistry)', 'DCI-DEL-1029', '+91 98765 01004', 'dr.meenakshi.dental@gmail.com', 9, 'Dedicated to child dental screening, painless tooth preservation, and preventive fluoridation.', 'assets/images/doctors/dr-meenakshi.jpg', 'Wed, Sat, Sun', '10:00 AM - 02:00 PM', 1, 'published'),
(5, NULL, 'Dr. Rajeshwar Kulkarni', 'homeopathy', 'Senior Classical Homeopath', 'BHMS, MD (Homeopathy)', 'CCH-MAH-5591', '+91 98765 01005', 'dr.kulkarni.homeo@gmail.com', 18, 'Expert in chronic asthma, allergic rhinitis, and autoimmune support through gentle homeopathic remedies.', 'assets/images/doctors/dr-kulkarni.jpg', 'Mon, Wed, Fri', '10:00 AM - 01:30 PM', 1, 'published');

-- ----------------------------------------------------------------------------
-- 15. Diagnostic Centres
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `diagnostic_centres`;
INSERT INTO `diagnostic_centres` (`id`, `name`, `contact_person`, `phone`, `email`, `address`, `city`, `tests_offered`, `discount_percentage`, `mou_signed`, `status`) VALUES
(1, 'Nidan Pathology & Digital Imaging Lab', 'Mr. Vikas Malhotra', '+91 11 2788 1122', 'nidanlabs@gmail.com', 'B-14, Community Centre, Janakpuri', 'New Delhi', 'Complete Blood Count (CBC), Lipid Profile, HbA1c, LFT, KFT, Digital X-Ray, Ultrasound.', 70.00, 1, 'published'),
(2, 'Apex Diagnostics & Preventive Health Care', 'Dr. Preeti Saxena', '+91 120 4556 788', 'apexdiag.noida@gmail.com', 'Sector 18 Market Complex', 'Noida', 'Thyroid Profile, Vitamin D & B12, Urine Routine, ECG, Bone Mineral Density (BMD).', 65.00, 1, 'published');

-- ----------------------------------------------------------------------------
-- 16. Schools
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `schools`;
INSERT INTO `schools` (`id`, `name`, `type`, `affiliation_board`, `udise_code`, `principal_name`, `contact_person`, `phone`, `email`, `address`, `city`, `state`, `pincode`, `total_students`, `classes_from`, `classes_to`, `mou_signed`, `mou_date`, `status`) VALUES
(1, 'Shri Saraswati Vidya Mandir Rural School', 'government_aided', 'UP Board / NCERT', '09280104501', 'Mr. Ramakant Tiwari', 'Mr. Ramakant Tiwari', '+91 94123 45678', 'ssvm.secschool@gmail.com', 'Gram Panchayat Chhajpur', 'Meerut', 'Uttar Pradesh', '250004', 450, 1, 10, 1, '2023-07-01', 'published'),
(2, 'Adarsh Pragati Bal Niketan High School', 'private', 'CBSE / NCERT', '09060502103', 'Mrs. Anita Singhal', 'Mrs. Anita Singhal', '+91 98102 98765', 'adarsh.pragati.school@gmail.com', 'Kasna Road, Greater Noida', 'Gautam Buddha Nagar', 'Uttar Pradesh', '201310', 620, 1, 12, 1, '2024-02-15', 'published');

-- ----------------------------------------------------------------------------
-- 17. Partners
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `partners`;
INSERT INTO `partners` (`id`, `name`, `type`, `logo`, `website`, `contact_person`, `email`, `phone`, `address`, `description`, `mou_signed`, `status`, `sort_order`) VALUES
(1, 'Herbalbox Wellness Pvt Ltd', 'corporate', 'assets/images/partners/herbalbox.png', 'https://herbalbox.co.in', 'Director CSR & Community', 'csr@herbalbox.co.in', '+91 11 4988 7766', 'DLF Cyber City, Tower B, Gurugram', 'Anchor CSR partner funding free herbal medicine distributions and primary health camps.', 1, 'published', 1),
(2, 'Rotary Club Delhi Central Trust', 'ngo', 'assets/images/partners/rotary.png', 'https://rotarydelhicentral.org', 'Rtn. Sanjeev Kapoor', 'contact@rotarydelhicentral.org', '+91 98110 54321', 'Barakhamba Road, Connaught Place, New Delhi', 'Partnering for free blood donation camps and corneal transplant logistics.', 1, 'published', 2),
(3, 'Lions Club Eye Care Foundation', 'ngo', 'assets/images/partners/lions.png', 'https://lionsclubeyecare.org', 'Lion Ashok Gupta', 'info@lionseyecare.org', '+91 11 2677 8899', 'South Extension Part II, New Delhi', 'Providing free lenses, surgical equipment, and subsidized eye screening camps.', 1, 'published', 3),
(4, 'TechEdu Foundation India', 'corporate', 'assets/images/partners/techedu.png', 'https://techedufoundation.org', 'Ms. Neha Nair', 'partnerships@techedu.org', '+91 22 6789 0123', 'Bandra Kurla Complex, Mumbai', 'Donating smart digital interactive screens and NCERT curriculum licenses for rural schools.', 1, 'published', 4);

-- ----------------------------------------------------------------------------
-- 18. MOUs
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `mous`;
INSERT INTO `mous` (`id`, `mou_number`, `title`, `entity_type`, `partner_id`, `hospital_id`, `school_id`, `start_date`, `end_date`, `scope_of_work`, `document_file`, `status`) VALUES
(1, 'MOU-2023-HB-001', 'Strategic CSR Partnership for Mobile Health Clinics', 'corporate', 1, NULL, NULL, '2023-04-01', '2026-03-31', 'Herbalbox provides annual grant funding, quality herbal pharmaceuticals, and logistics for 40 rural health camps.', 'uploads/mous/MOU-2023-HB-001.pdf', 'active'),
(2, 'MOU-2023-HOSP-002', 'Tertiary Referrals and Subsidized Emergency Care Agreement', 'hospital', NULL, 1, NULL, '2023-05-01', '2026-04-30', 'Sanjeevani Hospital agrees to provide free OPD consultations and 50% waiver on in-patient bed charges for NGO referred BPL patients.', 'uploads/mous/MOU-2023-HOSP-002.pdf', 'active'),
(3, 'MOU-2024-SCH-003', 'NCERT Smart Classroom Adoption Agreement', 'school', NULL, NULL, 1, '2024-01-15', '2027-01-14', 'Deploying 4 smart classrooms, computer laboratory setup, and annual teacher training at Shri Saraswati Vidya Mandir.', 'uploads/mous/MOU-2024-SCH-003.pdf', 'active');

-- ----------------------------------------------------------------------------
-- 19. Events
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `events`;
INSERT INTO `events` (`id`, `title`, `slug`, `type`, `description`, `featured_image`, `event_date`, `start_time`, `end_time`, `venue_name`, `venue_address`, `city`, `target_beneficiaries`, `registered_count`, `attended_count`, `units_collected`, `organized_by`, `status`) VALUES
(1, 'Mega Free Multi-Specialty Health & Eye Camp 2026', 'mega-free-health-camp-2026', 'health_camp', 'Comprehensive medical screening, ECG, blood sugar, eye test, dental examination, and 7-day free medicine kit distribution.', 'assets/images/events/mega-camp.jpg', DATE_ADD(CURDATE(), INTERVAL 14 DAY), '08:30:00', '16:00:00', 'Community Hall & Primary Health Ground', 'Near Panchayat Bhawan, Village Dadri', 'Greater Noida', 1200, 340, 0, NULL, 'Herbalbox Foundation & Sanjeevani Hospital', 'upcoming'),
(2, 'Voluntary Blood Donation & Platelet Drive', 'voluntary-blood-donation-drive-spring-2026', 'blood_donation', 'Save a life by donating blood. In association with Rotary Blood Bank. Donors receive refreshments, certificate, and donor card.', 'assets/images/events/blood-camp.jpg', DATE_ADD(CURDATE(), INTERVAL 25 DAY), '09:00:00', '15:00:00', 'Rotary Community Center', 'Sector 15, Vasundhara', 'Ghaziabad', 200, 115, 0, NULL, 'Herbalbox Foundation & Rotary Club', 'upcoming'),
(3, 'Rural School NCERT Digital Classroom Inauguration', 'school-digital-classroom-inauguration', 'education_workshop', 'Inauguration of 4 smart classrooms, distribution of NCERT science kits, and teacher digital pedagogy seminar.', 'assets/images/events/school-launch.jpg', DATE_SUB(CURDATE(), INTERVAL 20 DAY), '10:00:00', '14:00:00', 'Shri Saraswati Vidya Mandir Auditorium', 'Village Chhajpur', 'Meerut', 450, 450, 420, NULL, 'Herbalbox Foundation & TechEdu India', 'completed');

-- ----------------------------------------------------------------------------
-- 20. Event Doctors
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `event_doctors`;
INSERT INTO `event_doctors` (`event_id`, `doctor_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5);

-- ----------------------------------------------------------------------------
-- 21. Event Partners
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `event_partners`;
INSERT INTO `event_partners` (`event_id`, `partner_id`) VALUES
(1, 1),
(1, 3),
(2, 1),
(2, 2),
(3, 4);

-- ----------------------------------------------------------------------------
-- 22. Event Registrations
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `event_registrations`;
INSERT INTO `event_registrations` (`id`, `event_id`, `name`, `email`, `phone`, `age`, `gender`, `blood_group`, `service_required`, `notes`, `status`) VALUES
(1, 1, 'Ramesh Kumar', 'ramesh.k99@gmail.com', '+91 98111 22334', 52, 'male', 'B+', 'Eye testing and general sugar consultation', 'Experiencing blurred vision for 6 months.', 'confirmed'),
(2, 1, 'Sunita Devi', 'sunita.d@gmail.com', '+91 98222 33445', 46, 'female', 'O+', 'Ayurvedic joint pain consultation', 'Chronic knee osteoarthritis.', 'confirmed'),
(3, 2, 'Ankit Srivastava', 'ankit.sri@outlook.com', '+91 98333 44556', 28, 'male', 'O+', 'Voluntary Blood Donation', 'Second time donor.', 'confirmed');

-- ----------------------------------------------------------------------------
-- 23. Donations
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `donations`;
INSERT INTO `donations` (`id`, `donation_number`, `donor_name`, `donor_email`, `donor_phone`, `pan_number`, `donor_address`, `amount`, `currency`, `purpose`, `payment_method`, `transaction_id`, `payment_status`, `receipt_number`, `is_80g_requested`, `certificate_issued`, `created_at`) VALUES
(1, 'DON-2026-0001', 'Vikramaditya Roy', 'vikram.roy@enterprise.com', '+91 98100 11223', 'ABCDE1234F', '14 Park Street, Kolkata, WB - 700016', 25000.00, 'INR', 'healthcare', 'razorpay', 'pay_Ox8a2bK91dZq', 'success', 'REC-2026-0001', 1, 1, NOW()),
(2, 'DON-2026-0002', 'Dr. Shalini Deshmukh', 'shalini.deshmukh@gmail.com', '+91 98200 22334', 'FGHIJ5678K', '402 Sunrise Towers, Bandra West, Mumbai - 400050', 10000.00, 'INR', 'education', 'upi', 'upi_992184910284', 'success', 'REC-2026-0002', 1, 1, NOW()),
(3, 'DON-2026-0003', 'Rajeev Lochan', 'rajeev.lochan@hotmail.com', '+91 98300 33445', 'KLMNO9012P', 'Sector 44, Noida, UP - 201301', 5000.00, 'INR', 'general', 'bank_transfer', 'NEFT-889102391', 'success', 'REC-2026-0003', 1, 0, NOW());

-- ----------------------------------------------------------------------------
-- 24. Volunteers
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `volunteers`;
INSERT INTO `volunteers` (`id`, `name`, `email`, `phone`, `alternate_phone`, `gender`, `dob`, `blood_group`, `profession`, `qualification`, `address`, `city`, `state`, `pincode`, `available_days`, `available_hours_per_week`, `skills`, `why_join`, `photo`, `status`) VALUES
(1, 'Kavita Sundaram', 'kavita.s@gmail.com', '+91 98711 22334', NULL, 'female', '1998-04-12', 'A+', 'Software Engineer', 'B.Tech Computer Science', 'Flat 304, Green Valley Apts, Indirapuram', 'Ghaziabad', 'Uttar Pradesh', '201014', 'Sat, Sun', 8, 'Web Development, Digital Marketing, Graphic Design', 'Want to help rural children learn digital coding and basic STEM concepts.', 'assets/images/volunteers/kavita.jpg', 'approved'),
(2, 'Rohit Mehra', 'rohit.mehra@yahoo.com', '+91 98722 33445', NULL, 'male', '1995-11-20', 'O+', 'Nursing Officer', 'B.Sc Nursing', 'H-12, Sector 22', 'Noida', 'Uttar Pradesh', '201301', 'Sun, Public Holidays', 10, 'First Aid, Triage, Blood Pressure & Blood Sugar Screening', 'Passionate about organizing weekend medical camps for underprivileged communities.', 'assets/images/volunteers/rohit.jpg', 'approved');

-- ----------------------------------------------------------------------------
-- 25. Volunteer Interests
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `volunteer_interests`;
INSERT INTO `volunteer_interests` (`volunteer_id`, `interest_area`) VALUES
(1, 'education'),
(1, 'media_pr'),
(2, 'medical_camps'),
(2, 'blood_donation');

-- ----------------------------------------------------------------------------
-- 26. Partnership Enquiries
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `partnership_enquiries`;
INSERT INTO `partnership_enquiries` (`id`, `organization_name`, `entity_type`, `contact_person`, `designation`, `email`, `phone`, `city`, `state`, `proposal_summary`, `budget_range`, `status`) VALUES
(1, 'Global Health CSR Foundation', 'corporate_csr', 'Mr. Siddharth Menon', 'Head of CSR Initiatives', 'siddharth.m@globalhealth.org', '+91 98450 11223', 'Bengaluru', 'Karnataka', 'Proposal to fund 12 comprehensive eye and cataract screening camps in tribal blocks of Eastern UP.', 'INR 25 - 50 Lakhs', 'new'),
(2, 'Sarvodaya Educational Trust', 'school_college', 'Mrs. Rekha Sharma', 'Trustee Secretary', 'sarvodaya.trust@gmail.com', '+91 98180 99887', 'Jaipur', 'Rajasthan', 'Collaborating for NCERT smart classroom software deployment across 5 rural branches.', 'INR 10 - 25 Lakhs', 'read');

-- ----------------------------------------------------------------------------
-- 27. Contact Enquiries
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `contact_enquiries`;
INSERT INTO `contact_enquiries` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`) VALUES
(1, 'Deepak Verma', 'deepak.v@gmail.com', '+91 98101 23456', 'Request for Free Health Camp in Village Sikandrabad', 'Respected Team, Our village panchayat has 400 elderly patients needing eye and arthritis checkups. Kindly organize a medical camp.', 'new'),
(2, 'Meena Aggarwal', 'meena.aggarwal@gmail.com', '+91 98202 34567', 'Inquiry regarding 80G Tax Exemption Receipt for Online Donation', 'Hello, I made a donation of Rs 5,000 yesterday (Ref: DON-2026-0003). Could you please share the 80G certificate?', 'replied');

-- ----------------------------------------------------------------------------
-- 28. Gallery Categories
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `gallery_categories`;
INSERT INTO `gallery_categories` (`id`, `name`, `slug`, `status`, `sort_order`) VALUES
(1, 'Health & Medical Camps', 'health-medical-camps', 'published', 1),
(2, 'Eye Screening & Surgeries', 'eye-screening-surgeries', 'published', 2),
(3, 'NCERT Smart Schools & Labs', 'ncert-smart-schools-labs', 'published', 3),
(4, 'Blood Donation Drives', 'blood-donation-drives', 'published', 4),
(5, 'AYUSH & Yoga Sessions', 'ayush-yoga-sessions', 'published', 5);

-- ----------------------------------------------------------------------------
-- 29. Gallery Images
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `gallery_images`;
INSERT INTO `gallery_images` (`id`, `category_id`, `title`, `image_path`, `caption`, `is_featured`, `sort_order`, `status`) VALUES
(1, 1, 'Doctors Consulting Elderly Beneficiaries', 'assets/images/gallery/camp-1.jpg', 'Free general medicine consultation and blood sugar screening in Greater Noida village.', 1, 1, 'published'),
(2, 1, 'Free Medicine Distribution Counter', 'assets/images/gallery/camp-2.jpg', 'Dispensing 7-day essential medicines and herbal tonics to families.', 1, 2, 'published'),
(3, 2, 'Digital Eye Refraction in Mobile Van', 'assets/images/gallery/eye-1.jpg', 'Computerized eye checkups for rural senior citizens.', 1, 3, 'published'),
(4, 3, 'Smart Digital Classroom Session', 'assets/images/gallery/school-1.jpg', 'Students enjoying interactive science animations aligned with NCERT syllabus.', 1, 4, 'published'),
(5, 4, 'Volunteers Donating Blood at Camp', 'assets/images/gallery/blood-1.jpg', 'Over 100 units of blood collected safely with Rotary Blood Bank team.', 0, 5, 'published');

-- ----------------------------------------------------------------------------
-- 30. Videos
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `videos`;
INSERT INTO `videos` (`id`, `title`, `youtube_url`, `youtube_video_id`, `thumbnail`, `description`, `is_featured`, `status`, `sort_order`) VALUES
(1, 'Transforming Rural Healthcare: Mega Camp Highlights 2025', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ', 'assets/images/videos/thumb-1.jpg', 'Highlights from our 3-day multi-specialty medical camp serving 1,400+ patients.', 1, 'published', 1),
(2, 'Smart Education in Action: NCERT Labs in Rural UP', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ', 'assets/images/videos/thumb-2.jpg', 'How digital interactive boards are transforming attendance and retention in rural schools.', 1, 'published', 2);

-- ----------------------------------------------------------------------------
-- 31. Blog Categories
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `blog_categories`;
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `status`, `sort_order`) VALUES
(1, 'Healthcare & Medical Guides', 'healthcare-medical-guides', 'published', 1),
(2, 'Ayurveda & AYUSH Wellness', 'ayurveda-ayush-wellness', 'published', 2),
(3, 'Education & Child Psychology', 'education-child-psychology', 'published', 3),
(4, 'Success Stories & Impact', 'success-stories-impact', 'published', 4);

-- ----------------------------------------------------------------------------
-- 32. Blog Posts
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `blog_posts`;
INSERT INTO `blog_posts` (`id`, `category_id`, `author_id`, `title`, `slug`, `summary`, `content`, `featured_image`, `meta_title`, `meta_description`, `view_count`, `is_featured`, `status`, `published_at`) VALUES
(1, 1, 1, 'How Routine Preventive Medical Camps Prevent Chronic Disease Complications', 'preventive-medical-camps-chronic-disease-management', 
'Early detection of hypertension and Type-2 diabetes in rural populations saves lives and reduces long-term healthcare expenditure.',
'<h2>The Silent Epidemic of Non-Communicable Diseases</h2><p>In rural India, non-communicable diseases (NCDs) such as hypertension, type-2 diabetes, and cardiovascular conditions often go undetected until acute complications arise. Our mobile medical outreach initiatives bring vital screening tools right to the village doorstep.</p><h3>Why Point-of-Care Testing Matters</h3><p>By providing immediate HbA1c, blood pressure, and ECG diagnostics during medical camps, our volunteer doctors can initiate therapeutic lifestyle interventions and provide free baseline medications immediately.</p>', 
'assets/images/blog/blog-1.jpg', 'Preventive Health Camps for Chronic Disease Management | Herbalbox', 'Learn how rural preventive health camps detect hypertension and diabetes early to save lives.', 1420, 1, 'published', NOW()),
(2, 2, 2, 'Ayurvedic Principles of Immunity (Ojas) for Changing Seasons', 'ayurvedic-principles-immunity-ojas-seasonal-health',
'Understanding Ritucharya (seasonal regimen) and classical herbal formulations like Ashwagandha, Giloy, and Amla for daily wellness.',
'<h2>Understanding Ojas and Natural Immunity</h2><p>In Ayurveda, true immunity is termed <em>Ojas</em>—the subtle essence of all bodily tissues (Dhatus). When digestion (Agni) is balanced, Ojas is abundant, providing radiant health and resistance against seasonal pathogens.</p><h3>Key Herbs for Daily Vitality</h3><ul><li><strong>Giloy (Guduchi):</strong> The ultimate immunomodulator and fever-dispelling rasayana.</li><li><strong>Amalaki (Indian Gooseberry):</strong> A rich natural antioxidant supporting respiratory strength.</li><li><strong>Ashwagandha:</strong> Adaptogenic root reducing cortisol and rejuvenating the nervous system.</li></ul>',
'assets/images/blog/blog-2.jpg', 'Ayurvedic Immunity & Seasonal Wellness Guide | Herbalbox', 'Discover classical Ayurvedic herbs and lifestyle practices to boost immunity naturally across seasonal transitions.', 980, 1, 'published', NOW()),
(3, 3, 3, 'Bridging the Rural Digital Learning Gap with Interactive NCERT Pedagogy', 'bridging-rural-digital-learning-gap-ncert',
'How animated concept explanations and hands-on STEM experiments boost student retention in Class 1 to 10.',
'<h2>Transforming Rural Classrooms</h2><p>Rote memorization often creates learning barriers in mathematics and natural sciences. When students can visualize gravitational force, cellular biology, and fractions through animated bilingual simulations, comprehension rates increase significantly.</p>',
'assets/images/blog/blog-3.jpg', 'Digital NCERT Classrooms Transforming Rural Education | Herbalbox', 'How smart interactive boards and practical science kits are transforming rural student retention.', 760, 0, 'published', NOW());

-- ----------------------------------------------------------------------------
-- 33. Blog Tags
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `blog_tags`;
INSERT INTO `blog_tags` (`id`, `name`, `slug`) VALUES
(1, 'Healthcare Camps', 'healthcare-camps'),
(2, 'Ayurveda', 'ayurveda'),
(3, 'Preventive Health', 'preventive-health'),
(4, 'NCERT Education', 'ncert-education'),
(5, 'Rural Development', 'rural-development');

-- ----------------------------------------------------------------------------
-- 34. Blog Post Tags
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `blog_post_tags`;
INSERT INTO `blog_post_tags` (`post_id`, `tag_id`) VALUES
(1, 1),
(1, 3),
(2, 2),
(2, 3),
(3, 4),
(3, 5);

-- ----------------------------------------------------------------------------
-- 35. Testimonials
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `testimonials`;
INSERT INTO `testimonials` (`id`, `name`, `designation`, `organization`, `avatar`, `content`, `rating`, `is_featured`, `status`, `sort_order`) VALUES
(1, 'Smt. Shanti Devi (Age 68)', 'Villager & Beneficiary', 'Village Dadri', 'assets/images/testimonials/t1.jpg', 'I had lost vision in my left eye due to severe cataract. The foundation team not only screened me in the village camp but also took me to the hospital and performed surgery completely free of cost. I can see clearly now!', 5, 1, 'published', 1),
(2, 'Rameshwar Dayal', 'Gram Pradhan', 'Chhajpur Panchayat', 'assets/images/testimonials/t2.jpg', 'The digital smart class setup by Herbalbox Foundation in our village school has completely transformed our children. Student attendance is at an all-time high, and even parents are excited.', 5, 1, 'published', 2),
(3, 'Dr. Rajiv Anand', 'Chief Medical Officer', 'District Health Society', 'assets/images/testimonials/t3.jpg', 'The dedication and medical precision with which Herbalbox Foundation executes their multi-specialty health camps is commendable. Their mobile vans reach the most underserved hamlets.', 5, 1, 'published', 3);

-- ----------------------------------------------------------------------------
-- 36. Impact Statistics
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `impact_statistics`;
INSERT INTO `impact_statistics` (`id`, `metric_key`, `metric_name`, `metric_value`, `metric_prefix`, `metric_suffix`, `icon`, `description`, `sort_order`, `status`) VALUES
(1, 'patients_treated', 'Patients Treated', 150000, '', '+', 'fas fa-user-md', 'Underprivileged patients provided free OPD and diagnostic services.', 1, 'published'),
(2, 'camps_conducted', 'Medical Camps Organised', 420, '', '+', 'fas fa-clinic-medical', 'Free healthcare, eye screening, and blood donation drives completed.', 2, 'published'),
(3, 'cataract_surgeries', 'Free Cataract Surgeries', 3850, '', '+', 'fas fa-eye', 'Successful IOL cataract procedures performed for rural senior citizens.', 3, 'published'),
(4, 'students_supported', 'Students Empowered', 24000, '', '+', 'fas fa-user-graduate', 'Children enrolled in NCERT digital smart classes and remedial hubs.', 4, 'published'),
(5, 'schools_transformed', 'Schools Digitised', 48, '', '+', 'fas fa-school', 'Rural and government-aided schools upgraded with smart curriculum kits.', 5, 'published'),
(6, 'blood_units', 'Units of Blood Collected', 5600, '', ' Units', 'fas fa-tint', 'Voluntary blood units donated to certified regional blood banks.', 6, 'published');

-- ----------------------------------------------------------------------------
-- 37. Product Categories
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `product_categories`;
INSERT INTO `product_categories` (`id`, `name`, `slug`, `description`, `status`, `sort_order`) VALUES
(1, 'Herbal & Ayurvedic Formulations', 'herbal-ayurvedic-formulations', 'Natural herbal extracts, immunity tonics, and classical wellness formulations.', 'published', 1),
(2, 'Educational Kits & Books', 'educational-kits-books', 'Interactive NCERT activity workbooks, science experiment kits, and storybooks.', 'published', 2),
(3, 'Healthcare & First Aid Supplies', 'healthcare-first-aid-supplies', 'Home first aid essentials, digital thermometers, and blood pressure monitoring kits.', 'published', 3);

-- ----------------------------------------------------------------------------
-- 38. Products
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `products`;
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `short_description`, `description`, `price`, `discount_price`, `stock_quantity`, `sku`, `featured_image`, `is_featured`, `is_free_distribution`, `status`, `sort_order`) VALUES
(1, 1, 'Herbalbox Pure Organic Giloy & Tulsi Immunity Kadha (200ml)', 'herbalbox-giloy-tulsi-immunity-kadha', 'Concentrated sugar-free Ayurvedic herbal extract for daily immunity and respiratory protection.', '<p>Formulated with wild-harvested Giloy stem, Rama Tulsi, Sonth, and Marich. 100% natural, no synthetic colors or preservatives.</p>', 240.00, 199.00, 500, 'HB-IMM-001', 'assets/images/products/giloy-kadha.jpg', 1, 1, 'published', 1),
(2, 1, 'Dhanvantari Classical Triphala Churna (100g)', 'dhanvantari-triphala-churna', 'Traditional Ayurvedic blend of Amla, Haritaki, and Bibhitaki for natural digestive cleansing.', '<p>Supports colon health, gentle detox, and digestive regularity according to ancient Ayurvedic pharmacopoeia.</p>', 120.00, 95.00, 750, 'HB-DIG-002', 'assets/images/products/triphala.jpg', 1, 1, 'published', 2),
(3, 2, 'Junior STEM NCERT Science Experiment DIY Kit (Classes 6-8)', 'junior-stem-ncert-science-kit', 'Hands-on experiential learning kit containing 25 practical physics and chemistry experiments.', '<p>Includes safe laboratory components, bilingual pictorial instruction guide, and QR codes for video demonstrations.</p>', 750.00, 599.00, 200, 'HB-EDU-003', 'assets/images/products/stem-kit.jpg', 1, 0, 'published', 3);

-- ----------------------------------------------------------------------------
-- 39. SEO Settings
-- ----------------------------------------------------------------------------
TRUNCATE TABLE `seo_settings`;
INSERT INTO `seo_settings` (`id`, `page_slug`, `page_name`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `og_title`, `og_description`, `og_image`, `schema_markup`) VALUES
(1, 'home', 'Home Page', 'Herbalbox & Seva Arogya Foundation | Healthcare & Education NGO', 'Herbalbox Foundation is a registered 80G tax-exempt NGO delivering free healthcare camps, AYUSH wellness, and smart NCERT education.', 'ngo, healthcare ngo, free medical camp, eye camp, blood donation, ayurveda, ncert education, 80G tax exemption', 'https://herbalboxfoundation.org/', 'Herbalbox Foundation - Transforming Healthcare and Education', 'Join us in empowering underserved communities with free medical care and digital classrooms.', 'assets/images/og-home.jpg', '{"@context": "https://schema.org", "@type": "NGO", "name": "Herbalbox Foundation", "url": "https://herbalboxfoundation.org"}'),
(2, 'about', 'About Us', 'About Our Mission, Vision & Legal Accreditations | Herbalbox Foundation', 'Learn about our journey, legal 80G/12A/CSR registrations, leadership team, and board of medical trustees.', 'about ngo, 80g certificate, 12a registration, csr approved ngo india', 'https://herbalboxfoundation.org/about', 'About Herbalbox Foundation', 'Learn how we are serving over 150,000 lives across India.', 'assets/images/og-about.jpg', NULL),
(3, 'programs', 'Our Programs', 'Comprehensive Healthcare, AYUSH & NCERT Education Programs', 'Explore our mobile clinics, cataract surgery drives, AYUSH wellness initiatives, and digital smart schools.', 'ngo programs, mobile health clinic, free cataract surgery, smart school program', 'https://herbalboxfoundation.org/programs', 'Our Flagship Programs | Herbalbox Foundation', 'Discover how our high-impact programs transform grassroots lives.', 'assets/images/og-programs.jpg', NULL),
(4, 'events', 'Medical Camps & Events', 'Upcoming Free Medical Camps, Blood Donation & Education Drives', 'Find details and register for our upcoming free medical camps, eye screening camps, and blood donation drives.', 'free medical camp, blood donation registration, eye checkup camp near me', 'https://herbalboxfoundation.org/events', 'Upcoming Medical Camps & Drives', 'Register for upcoming free medical and blood donation camps.', 'assets/images/og-events.jpg', NULL),
(5, 'donate', 'Donate & Support', 'Donate Online - 80G Tax Exemption | Herbalbox Foundation', 'Your donation saves lives and educates children. Get instant 50% tax exemption certificate under Section 80G.', 'donate online, 80g donation, tax exemption ngo, donate to healthcare, sponsor child education', 'https://herbalboxfoundation.org/donate', 'Donate to Herbalbox Foundation - 80G Tax Exemption', 'Support free medical camps and rural school education. 100% secure online donation.', 'assets/images/og-donate.jpg', NULL),
(6, 'contact', 'Contact Us', 'Contact Herbalbox Foundation | Reach Out for Health Camps & School MOUs', 'Get in touch with our team for organizing medical camps, volunteer opportunities, school digital adoptions, or CSR partnerships.', 'contact ngo, request medical camp, volunteer registration, csr partnership', 'https://herbalboxfoundation.org/contact', 'Contact Herbalbox Foundation', 'We are here to serve. Reach out to collaborate or request a camp in your area.', 'assets/images/og-contact.jpg', NULL);

SET FOREIGN_KEY_CHECKS = 1;

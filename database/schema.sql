-- ==========================================================
-- NGO Website & Admin Panel Database Schema
-- Compatible with MySQL 8.0+ / MariaDB 10.4+ / Hostinger / XAMPP / cPanel
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table: admins
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `username` VARCHAR(60) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('superadmin', 'admin', 'editor') DEFAULT 'admin',
  `avatar` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `last_login` DATETIME DEFAULT NULL,
  `login_attempts` TINYINT UNSIGNED DEFAULT 0,
  `lockout_until` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: site_settings
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` LONGTEXT DEFAULT NULL,
  `setting_group` VARCHAR(50) DEFAULT 'general',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: home_banners
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `home_banners` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `subtitle` TEXT DEFAULT NULL,
  `badge_text` VARCHAR(100) DEFAULT NULL,
  `primary_btn_text` VARCHAR(50) DEFAULT 'Donate Now',
  `primary_btn_link` VARCHAR(255) DEFAULT 'donate.php',
  `secondary_btn_text` VARCHAR(50) DEFAULT 'Explore Programs',
  `secondary_btn_link` VARCHAR(255) DEFAULT 'healthcare.php',
  `image` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: impact_statistics
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `impact_statistics` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(100) NOT NULL,
  `count_number` VARCHAR(50) NOT NULL,
  `suffix` VARCHAR(20) DEFAULT '+',
  `icon` VARCHAR(100) DEFAULT 'fa-heartbeat',
  `description` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: team_members
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `team_members` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `designation` VARCHAR(120) NOT NULL,
  `category` ENUM('founder', 'trustee', 'advisor', 'management', 'volunteer') DEFAULT 'management',
  `photo` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `facebook` VARCHAR(255) DEFAULT NULL,
  `twitter` VARCHAR(255) DEFAULT NULL,
  `linkedin` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: certificates_reports
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificates_reports` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `type` ENUM('certificate', 'annual_report', 'audit_report', 'registration_doc') DEFAULT 'certificate',
  `financial_year` VARCHAR(50) DEFAULT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `cover_image` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: program_categories
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `program_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `icon` VARCHAR(100) DEFAULT 'fa-hand-holding-heart',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: programs
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `programs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED DEFAULT NULL,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(220) NOT NULL UNIQUE,
  `type` ENUM('healthcare', 'education', 'ayush', 'yoga', 'welfare', 'other') NOT NULL DEFAULT 'healthcare',
  `short_description` VARCHAR(350) DEFAULT NULL,
  `full_content` LONGTEXT DEFAULT NULL,
  `features` TEXT DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `banner_image` VARCHAR(255) DEFAULT NULL,
  `icon` VARCHAR(100) DEFAULT 'fa-stethoscope',
  `target_audience` VARCHAR(150) DEFAULT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `sort_order` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`slug`),
  INDEX (`type`),
  FOREIGN KEY (`category_id`) REFERENCES `program_categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: hospitals
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `hospitals` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `type` ENUM('hospital', 'clinic', 'diagnostic_center', 'charity_center') DEFAULT 'hospital',
  `logo` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `contact_person` VARCHAR(120) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `services_offered` TEXT DEFAULT NULL,
  `partnership_details` TEXT DEFAULT NULL,
  `mou_signed_date` DATE DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `is_featured` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: doctors
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `hospital_id` INT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(150) NOT NULL,
  `qualification` VARCHAR(200) NOT NULL,
  `specialization` VARCHAR(150) NOT NULL,
  `treatment_type` ENUM('allopathic', 'ayurvedic', 'homeopathic', 'yoga_wellness', 'general') DEFAULT 'allopathic',
  `experience_years` INT DEFAULT 0,
  `photo` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `clinic_hospital_name` VARCHAR(200) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `available_days` VARCHAR(150) DEFAULT 'Monday - Saturday',
  `services` TEXT DEFAULT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`hospital_id`) REFERENCES `hospitals`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: schools
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `schools` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `logo` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `principal_name` VARCHAR(150) DEFAULT NULL,
  `contact_person` VARCHAR(150) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `pincode` VARCHAR(20) DEFAULT NULL,
  `mou_date` DATE DEFAULT NULL,
  `mou_document` VARCHAR(255) DEFAULT NULL,
  `students_benefited` INT DEFAULT 0,
  `active_programs` TEXT DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: partners
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `partners` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `partner_type` ENUM('school', 'doctor', 'hospital', 'diagnostic', 'csr_corporate', 'community', 'other') NOT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `contact_person` VARCHAR(150) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `mou_signed_date` DATE DEFAULT NULL,
  `summary` TEXT DEFAULT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: mous
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mous` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `partner_name` VARCHAR(200) NOT NULL,
  `partner_type` ENUM('school', 'hospital', 'doctor', 'diagnostic', 'csr', 'ngo', 'other') NOT NULL,
  `partner_id` INT UNSIGNED DEFAULT NULL,
  `signed_date` DATE NOT NULL,
  `valid_upto` DATE DEFAULT NULL,
  `document_file` VARCHAR(255) NOT NULL,
  `key_objectives` TEXT DEFAULT NULL,
  `scope_of_work` TEXT DEFAULT NULL,
  `is_public` TINYINT(1) DEFAULT 1,
  `status` ENUM('active', 'expired', 'terminated') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: events
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `category` ENUM('medical_camp', 'blood_donation', 'eye_camp', 'dental_camp', 'yoga_camp', 'meditation_camp', 'education_camp', 'school_program', 'awareness_drive', 'mou_signing', 'community_welfare') NOT NULL DEFAULT 'medical_camp',
  `short_description` VARCHAR(350) DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `banner_image` VARCHAR(255) DEFAULT NULL,
  `event_date` DATE NOT NULL,
  `start_time` TIME DEFAULT '09:00:00',
  `end_time` TIME DEFAULT '17:00:00',
  `venue` VARCHAR(255) NOT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `organizer_name` VARCHAR(150) DEFAULT 'Seva Foundation NGO',
  `doctor_partner_info` VARCHAR(255) DEFAULT NULL,
  `registration_required` TINYINT(1) DEFAULT 1,
  `registration_deadline` DATE DEFAULT NULL,
  `max_participants` INT DEFAULT 200,
  `registered_count` INT DEFAULT 0,
  `is_featured` TINYINT(1) DEFAULT 0,
  `status` ENUM('upcoming', 'completed', 'cancelled') DEFAULT 'upcoming',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`category`),
  INDEX (`event_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: event_registrations
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_registrations` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT UNSIGNED NOT NULL,
  `registration_number` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `age` INT DEFAULT NULL,
  `gender` ENUM('Male', 'Female', 'Other') DEFAULT 'Male',
  `blood_group` VARCHAR(10) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `special_requirements` TEXT DEFAULT NULL,
  `status` ENUM('confirmed', 'attended', 'cancelled') DEFAULT 'confirmed',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: donations
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `donations` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `donation_code` VARCHAR(50) NOT NULL UNIQUE,
  `donor_name` VARCHAR(150) NOT NULL,
  `donor_email` VARCHAR(150) NOT NULL,
  `donor_phone` VARCHAR(30) NOT NULL,
  `donor_pan` VARCHAR(20) DEFAULT NULL,
  `donor_address` TEXT DEFAULT NULL,
  `donor_city` VARCHAR(100) DEFAULT NULL,
  `donor_state` VARCHAR(100) DEFAULT NULL,
  `donor_country` VARCHAR(60) DEFAULT 'India',
  `amount` DECIMAL(10,2) NOT NULL,
  `purpose` ENUM('general', 'healthcare', 'education', 'medical_camp', 'blood_donation', 'ayush', 'yoga', 'child_education') DEFAULT 'general',
  `payment_method` VARCHAR(50) DEFAULT 'razorpay',
  `razorpay_order_id` VARCHAR(100) DEFAULT NULL,
  `razorpay_payment_id` VARCHAR(100) DEFAULT NULL,
  `razorpay_signature` VARCHAR(255) DEFAULT NULL,
  `payment_status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
  `receipt_number` VARCHAR(60) DEFAULT NULL,
  `is_80g_requested` TINYINT(1) DEFAULT 1,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`payment_status`),
  INDEX (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: volunteers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `volunteers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `volunteer_code` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `dob` DATE DEFAULT NULL,
  `gender` ENUM('Male', 'Female', 'Other') DEFAULT 'Male',
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `pincode` VARCHAR(20) DEFAULT NULL,
  `qualification` VARCHAR(150) DEFAULT NULL,
  `occupation` VARCHAR(150) DEFAULT NULL,
  `area_of_interest` VARCHAR(255) NOT NULL,
  `experience` TEXT DEFAULT NULL,
  `available_days` VARCHAR(150) DEFAULT 'Weekends',
  `available_hours_per_week` INT DEFAULT 5,
  `message` TEXT DEFAULT NULL,
  `resume_file` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending', 'contacted', 'approved', 'rejected') DEFAULT 'pending',
  `admin_notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: gallery_categories
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gallery_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: gallery_images
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gallery_images` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED DEFAULT NULL,
  `title` VARCHAR(200) NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `thumbnail_path` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `event_id` INT UNSIGNED DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `is_featured` TINYINT(1) DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `gallery_categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: videos
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `videos` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `youtube_url` VARCHAR(255) NOT NULL,
  `youtube_id` VARCHAR(50) NOT NULL,
  `category` VARCHAR(100) DEFAULT 'Healthcare',
  `description` TEXT DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: blog_categories
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: blog_posts
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED DEFAULT NULL,
  `author_id` INT UNSIGNED DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `short_description` VARCHAR(350) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `tags` VARCHAR(255) DEFAULT NULL,
  `author_name` VARCHAR(100) DEFAULT 'Editorial Team',
  `views_count` INT UNSIGNED DEFAULT 0,
  `status` ENUM('published', 'draft', 'archived') DEFAULT 'published',
  `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `seo_title` VARCHAR(255) DEFAULT NULL,
  `seo_description` TEXT DEFAULT NULL,
  `seo_keywords` VARCHAR(255) DEFAULT NULL,
  `canonical_url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`slug`),
  INDEX (`status`),
  FOREIGN KEY (`category_id`) REFERENCES `blog_categories`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`author_id`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: testimonials
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `designation` VARCHAR(120) DEFAULT NULL,
  `organization` VARCHAR(150) DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `content` TEXT NOT NULL,
  `rating` TINYINT UNSIGNED DEFAULT 5,
  `category` ENUM('general', 'patient', 'student', 'partner', 'donor', 'doctor') DEFAULT 'general',
  `is_featured` TINYINT(1) DEFAULT 1,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: product_categories
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: products
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(220) NOT NULL UNIQUE,
  `sku` VARCHAR(50) DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `mrp` DECIMAL(10,2) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `short_description` VARCHAR(300) DEFAULT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `benefits` TEXT DEFAULT NULL,
  `ingredients` TEXT DEFAULT NULL,
  `usage_instructions` TEXT DEFAULT NULL,
  `stock_quantity` INT DEFAULT 100,
  `is_featured` TINYINT(1) DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `product_categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: contact_enquiries
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_enquiries` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `subject` VARCHAR(200) DEFAULT NULL,
  `message` LONGTEXT NOT NULL,
  `status` ENUM('unread', 'read', 'replied', 'archived') DEFAULT 'unread',
  `admin_reply` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: partnership_enquiries
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `partnership_enquiries` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `organization_name` VARCHAR(200) NOT NULL,
  `contact_person` VARCHAR(120) NOT NULL,
  `partner_type` ENUM('school', 'doctor', 'hospital', 'diagnostic', 'csr_corporate', 'community', 'other') NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `website` VARCHAR(200) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `partnership_interest` TEXT NOT NULL,
  `proposal_document` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending', 'under_review', 'approved', 'rejected') DEFAULT 'pending',
  `admin_notes` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: activity_logs
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(100) NOT NULL,
  `module` VARCHAR(60) NOT NULL,
  `record_id` INT UNSIGNED DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================================
-- SEED DATA
-- ==========================================================

-- Default Superadmin User (Password: admin123)
-- Uses standard PHP password_hash("$2y$10$...")
INSERT INTO `admins` (`id`, `name`, `email`, `username`, `password`, `role`, `status`) VALUES
(1, 'Super Administrator', 'admin@ngoseva.org', 'admin', '$2y$10$wN3tS4Z9pZ2uF6s9vK.hkuU7mYfH8aG3nJ1uL.O8tP7lV3mQ9eKSm', 'superadmin', 'active')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Default Site Settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('site_name', 'Seva Arogya & Shiksha Foundation', 'general'),
('site_tagline', 'Serving Humanity Through Healthcare, Education & Community Welfare', 'general'),
('ngo_reg_number', 'REG/NGO/2018/88741/DELHI', 'general'),
('pan_number', 'AAATS1234F', 'general'),
('niti_aayog_id', 'DL/2018/0192847', 'general'),
('tax_exemption_80g', '80G-CIT(E)/DEL/2019-20/A/1042', 'general'),
('tax_exemption_12a', '12A-CIT(E)/DEL/2018-19/REG-778', 'general'),
('csr_reg_number', 'CSR00019482', 'general'),
('site_phone', '+91 98765 43210', 'contact'),
('site_alt_phone', '+91 11 2345 6789', 'contact'),
('site_email', 'info@ngoseva.org', 'contact'),
('donation_email', 'donate@ngoseva.org', 'contact'),
('whatsapp_number', '+919876543210', 'contact'),
('whatsapp_message', 'Hello Seva Foundation, I would like to inquire about your healthcare and education programs.', 'contact'),
('site_address', 'Seva Bhavan, Plot 42, Institutional Area, Sector 14, New Delhi - 110001, India', 'contact'),
('office_hours', 'Mon - Sat: 9:00 AM - 6:00 PM (Sunday Closed for Camps)', 'contact'),
('google_map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224345.83923192776!2d77.0688975472578!3d28.52758200617607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5b347eb62d%3A0x52c2b7494e204dce!2sNew%20Delhi%2C%20Delhi!5e0!3m2!1sen!2sin!4v1650000000000!5m2!1sen!2sin', 'contact'),
('facebook_url', 'https://facebook.com/seva-foundation-ngo', 'social'),
('instagram_url', 'https://instagram.com/sevafoundation', 'social'),
('twitter_url', 'https://twitter.com/sevango_india', 'social'),
('youtube_url', 'https://youtube.com/@sevafoundation-india', 'social'),
('linkedin_url', 'https://linkedin.com/company/seva-foundation-india', 'social'),
('razorpay_enabled', '1', 'payment'),
('razorpay_key_id', 'rzp_test_placeholder_key', 'payment'),
('razorpay_key_secret', 'rzp_test_placeholder_secret', 'payment'),
('smtp_host', 'smtp.hostinger.com', 'smtp'),
('smtp_port', '587', 'smtp'),
('smtp_user', 'no-reply@ngoseva.org', 'smtp'),
('smtp_pass', 'email_password_here', 'smtp'),
('smtp_from_email', 'no-reply@ngoseva.org', 'smtp'),
('smtp_from_name', 'Seva Arogya & Shiksha Foundation', 'smtp'),
('about_hero_headline', 'Dedicated to Uplifting Lives Through Accessible Healthcare & Holistic Education', 'about'),
('about_mission', 'To provide free high-quality healthcare, preventive medical camps, NCERT-based education support, AYUSH healing, and youth empowerment to underprivileged communities across India.', 'about'),
('about_vision', 'A compassionate, enlightened, and healthy India where no child is deprived of education and no individual is denied timely medical care due to socioeconomic barriers.', 'about'),
('about_history', 'Established in 2018 by a consortium of philanthropic doctors and educationists, Seva Foundation began as a small mobile clinic. Today, we run 150+ free healthcare camps annually, partner with 45+ premier schools, and empower thousands of families with life-saving treatments and holistic wellness.', 'about'),
('seo_meta_title', 'Seva Foundation | Healthcare, Education, Free Medical Camps & AYUSH Wellness NGO', 'seo'),
('seo_meta_description', 'Seva Arogya & Shiksha Foundation is a registered Indian NGO empowering communities through Free Medical Camps, Blood Donation, Eye/Dental Care, School MOUs, NCERT Learning & AYUSH Treatments.', 'seo'),
('seo_meta_keywords', 'NGO India, Free Medical Camps, Blood Donation NGO, School MOU, NCERT Education Support, AYUSH Ayurvedic Clinic, Eye Care Camp, Donate NGO 80G', 'seo')
ON DUPLICATE KEY UPDATE `setting_key`=`setting_key`;

-- Home Banners
INSERT INTO `home_banners` (`title`, `subtitle`, `badge_text`, `primary_btn_text`, `primary_btn_link`, `secondary_btn_text`, `secondary_btn_link`, `sort_order`, `status`) VALUES
('Serving Humanity Through Healthcare, Education & Welfare', 'Working together to build a healthier, educated, and empowered society with free medical aid, school MOUs, and holistic AYUSH care.', 'Registered National NGO (80G / 12A / CSR-1)', 'Donate Now', 'donate.php', 'Explore Our Programs', 'healthcare.php', 1, 'active'),
('Empowering Underprivileged Children With Quality NCERT Education', 'Bridging the educational divide through book distribution, digital tools, personality mentoring, and school partnerships.', 'Education For All Initiative', 'Support Education', 'education.php', 'Partner With Us', 'partnerships.php', 2, 'active'),
('Free Medical Camps & Blood Donation Drives Across India', 'Bringing certified doctors, diagnostic screenings, free medicine distribution, and emergency blood banks directly to rural & urban doorsteps.', 'Healthcare At Doorsteps', 'Find A Camp', 'medical-camps.php', 'Become A Donor', 'blood-donation.php', 3, 'active');

-- Impact Statistics
INSERT INTO `impact_statistics` (`title`, `count_number`, `suffix`, `icon`, `description`, `sort_order`) VALUES
('Lives Touched & Treated', '125000', '+', 'fa-users', 'Patients provided free diagnosis & medicine', 1),
('Free Medical Camps Held', '480', '+', 'fa-clinic-medical', 'Organized across remote villages & urban slums', 2),
('Partner Schools & MOUs', '65', '+', 'fa-school', 'Active school wellness & education programs', 3),
('Certified Partner Doctors', '180', '+', 'fa-user-md', 'Specialists volunteering in allopathic & AYUSH', 4),
('Units of Blood Collected', '12500', '+', 'fa-tint', 'Life-saving units in partnership with Red Cross', 5),
('Dedicated Volunteers', '3400', '+', 'fa-hands-helping', 'Active changemakers driving community impact', 6);

-- Program Categories
INSERT INTO `program_categories` (`name`, `slug`, `description`, `icon`) VALUES
('Healthcare Services', 'healthcare-services', 'Comprehensive clinical care, free diagnosis, eye & dental checkups', 'fa-heartbeat'),
('Education Initiatives', 'education-initiatives', 'NCERT-aligned education support, school kits & career guidance', 'fa-graduation-cap'),
('AYUSH & Holistic Wellness', 'ayush-wellness', 'Traditional Ayurvedic, Homeopathic, Allopathic, Yoga & Meditation', 'fa-spa'),
('Emergency & Camps', 'emergency-camps', 'Mobile medical camps, blood donation drives & relief aid', 'fa-ambulance');

-- Programs
INSERT INTO `programs` (`category_id`, `title`, `slug`, `type`, `short_description`, `full_content`, `features`, `icon`, `is_featured`, `sort_order`) VALUES
(1, 'Free Mega Medical Camps', 'free-medical-camps', 'healthcare', 'Comprehensive multi-specialty medical checkups, general consultations, diagnostic testing, and free medicine distribution.', 'Our Mega Medical Camps bring qualified allopathic and specialist physicians directly into underserved rural villages and urban slums. Every visitor receives free vital checks, blood sugar, ECG screening, pediatric care, geriatric consultation, and doctor-prescribed essential medications at zero cost.', 'Free General Physician Checkup\nFree Essential Medicines for 15 Days\nPediatric and Geriatric Care\nPreventive Health Education & Counseling', 'fa-stethoscope', 1, 1),
(1, 'Life-Saver Blood Donation Drives', 'blood-donation-camps', 'healthcare', 'Regular community and corporate blood donation camps ensuring well-stocked emergency reserves in government blood banks.', 'Blood is the gift of life. Seva Foundation partners with certified government and Red Cross blood banks to conduct safe, hygienic blood collection drives. Every donor undergoes thorough pre-screening (Hb, BP, vitals) and receives a donor card and certificate of appreciation.', 'Certified Govt Blood Bank Integration\nThorough Donor Health Screening\nEmergency Donor Blood Helpline 24/7\nAwareness Against Blood Donation Myths', 'fa-tint', 1, 2),
(1, 'Comprehensive Eye Care & Cataract Relief', 'eye-care-programs', 'healthcare', 'Vision testing, free refraction, prescription spectacles distribution, and sponsored cataract surgeries.', 'Good vision is essential for dignity and livelihood. In collaboration with renowned eye hospitals, we conduct visual acuity screenings for school students and senior citizens. Patients diagnosed with cataracts receive fully funded stitch-less IOL surgeries at accredited partner hospitals.', 'Refraction & Vision Acuity Checks\nFree Prescription Eyeglasses\nFree Cataract Surgeries with Transport\nGlaucoma & Diabetic Retinopathy Screening', 'fa-eye', 1, 3),
(1, 'Dental Care & Oral Hygiene Camps', 'dental-care-initiatives', 'healthcare', 'Dentist checkups, scaling, fluoride applications, oral cancer screenings, and distribution of dental hygiene kits.', 'Oral hygiene is frequently neglected in rural areas. Our mobile dental vans and clinics provide preventive check-ups, painless tooth extractions, temporary fillings, and educate children on proper brushing techniques to prevent lifelong periodontal diseases.', 'Digital Dental Screening\nPainless Tooth Extractions\nOral Cancer Early Detection\nFree Toothbrush & Paste Kit Distribution', 'fa-tooth', 1, 4),
(2, 'Class 1st to 12th NCERT Learning Support', 'ncert-school-education', 'education', 'Remedial coaching, structured NCERT study materials, digital smart classes, and educational stationery kits.', 'Education breaks the cycle of poverty. We collaborate with government and affordable private schools to deliver supplementary coaching in STEM and English based on the national NCERT curriculum. We provide curriculum textbooks, notebooks, geometry sets, and school bags to every needy student.', 'NCERT Syllabus Aligned Remedial Tuition\nFree Textbooks, Notebooks & Bags\nDigital Smart Classroom Setups\nRegular Assessment & Progress Tracking', 'fa-book-reader', 1, 5),
(2, 'Student Career Guidance & Mentorship', 'career-guidance-mentorship', 'education', 'Aptitude counseling, scholarship assistance, competitive exam prep guidance, and personality development.', 'Guiding high school students (Classes 9th to 12th) toward promising careers. Expert mentors from civil services, engineering, medical, and commerce fields conduct interactive workshops, guiding students through college admissions and government welfare scholarship schemes.', 'Psychometric Aptitude Testing\nScholarship Application Assistance\nMotivational & Personality Workshops\nVocational & Skill Training Pathways', 'fa-user-graduate', 1, 6),
(3, 'Ayurvedic Treatment & Herbal Care', 'ayurvedic-treatment', 'ayush', 'Holistic Ayurvedic consultations, Nadi Pariksha, herbal formulations, lifestyle counseling, and chronic pain management.', 'Ayurveda offers time-tested remedies for lifestyle diseases, arthritis, digestive issues, and respiratory conditions. Our certified BAMS doctors provide authentic pulse diagnostics (Nadi Pariksha) and dispense pure herbal formulations to rejuvenate overall wellness.', 'Certified BAMS Vaidya Consultations\nAuthentic Nadi Pariksha (Pulse Diagnosis)\nHerbal Remedies for Chronic Ailments\nDietary & Dinacharya Counseling', 'fa-leaf', 1, 7),
(3, 'Homeopathic Consultations & Care', 'homeopathic-treatment', 'ayush', 'Gentle, safe, and effective homeopathic treatments for allergies, skin diseases, pediatric immunity, and chronic disorders.', 'Homeopathy provides natural healing with zero side effects. Our experienced BHMS physicians prescribe customized constitutional remedies for asthma, eczema, recurring childhood infections, and stress-related ailments without any financial burden on the patient.', 'Certified BHMS Doctor Consultations\nSafe & Gentle Pediatric Care\nChronic Allergy & Skin Relief\nHolistic Constitutional Prescription', 'fa-pills', 1, 8),
(3, 'Daily Yoga & Mindful Meditation', 'yoga-meditation-programs', 'yoga', 'Daily yoga classes, Pranayama sessions, stress management for students & corporate workers, and community wellness camps.', 'Harmonizing mind, body, and soul. Our certified yoga instructors conduct daily morning yoga camps in schools, community parks, and partner institutions, focusing on asanas, breathing techniques (Pranayama), and Dhyana (Meditation) for inner peace and physical vitality.', 'Certified Yoga Acharya Instructors\nSpecialized Asanas for Flexibility & Health\nPranayama for Respiratory Strength\nSchool Yoga Competitions & Camps', 'fa-spa', 1, 9);

-- Hospitals
INSERT INTO `hospitals` (`name`, `type`, `contact_person`, `phone`, `email`, `website`, `address`, `city`, `state`, `services_offered`, `partnership_details`, `mou_signed_date`, `is_featured`) VALUES
('AIIMS Associated Rural Health Center', 'hospital', 'Dr. Rajesh Sharma', '+91 11 2658 8500', 'outreach@aiimshealth.org', 'https://aiims.edu', 'Ansari Nagar East', 'New Delhi', 'Delhi', 'Emergency Care, General Medicine, Cardiology, Oncology Screening, Blood Bank', 'Institutional MOU for referral surgeries and specialist doctor camps.', '2021-04-10', 1),
('Sanjivani Multispecialty Hospital', 'hospital', 'Dr. Meenakshi Iyer', '+91 98112 34567', 'info@sanjivanihospital.org', 'https://sanjivanihospital.org', 'Sector 22, Near Metro Station', 'Noida', 'Uttar Pradesh', 'Pediatrics, Gynecology, Orthopedics, Intensive Care, Diagnostic Labs', 'Provides free OPD consultations to BPL card holders and subsidised surgical care.', '2022-01-15', 1),
('Drishti Super Specialty Eye Institute', 'clinic', 'Dr. Arvind Saxena', '+91 98220 11223', 'camps@drishtieye.com', 'https://drishtieye.com', 'Ring Road, Lajpat Nagar IV', 'New Delhi', 'Delhi', 'Cataract Surgery, Lasik, Glaucoma, Pediatric Ophthalmology, Spectacle Labs', 'Conducts 50 free cataract operations per month for underprivileged seniors identified at NGO camps.', '2020-08-20', 1),
('MaxPath Diagnostic & Imaging Center', 'diagnostic_center', 'Mr. Vivek Kapoor', '+91 99887 66554', 'care@maxpathlabs.com', 'https://maxpathlabs.com', 'Civil Lines, Opp District Hospital', 'Gurugram', 'Haryana', 'MRI, CT Scan, Ultrasound, Digital X-Ray, Pathology, Lipid Profile, HbA1c', 'Offers 60% subsidized diagnostic testing vouchers for NGO patients.', '2021-11-05', 1);

-- Doctors
INSERT INTO `doctors` (`hospital_id`, `name`, `qualification`, `specialization`, `treatment_type`, `experience_years`, `clinic_hospital_name`, `city`, `state`, `available_days`, `services`, `is_featured`, `status`) VALUES
(1, 'Dr. Anand Verma', 'MBBS, MD (Medicine), FACP', 'Senior Consultant Physician', 'allopathic', 18, 'AIIMS Outreach & Seva Clinic', 'New Delhi', 'Delhi', 'Monday, Wednesday, Friday', 'General Health Checkups, Diabetes Management, Hypertension & Cardiac Screening', 1, 'active'),
(3, 'Dr. Sunita Deshmukh', 'MBBS, MS (Ophthalmology)', 'Chief Eye Surgeon & Cornea Specialist', 'allopathic', 15, 'Drishti Eye Institute', 'New Delhi', 'Delhi', 'Tuesday, Thursday, Saturday', 'Cataract Phaco Surgery, Glaucoma Treatment, Diabetic Eye Care, Pediatric Vision', 1, 'active'),
(2, 'Dr. Vikramaditya Shastri', 'BAMS, MD (Ayurveda - Kayachikitsa)', 'Senior Ayurvedic Physician & Panchakarma', 'ayurvedic', 20, 'Arogya Ayurvedic Wellness Kendra', 'Noida', 'Uttar Pradesh', 'Monday - Saturday', 'Nadi Pariksha, Chronic Joint Pain Relief, Digestive Health, Detox Therapies', 1, 'active'),
(2, 'Dr. Priyanka Chatterjee', 'BHMS, MD (Homeopathy)', 'Consultant Homeopath & Pediatric Immunity', 'homeopathic', 12, 'Healing Touch Homeopathy', 'Gurugram', 'Haryana', 'Monday, Wednesday, Saturday', 'Asthma, Skin Allergies, Child Immunity Booster, Chronic Migraine Care', 1, 'active'),
(2, 'Acharya Devendra Nautiyal', 'M.Sc (Yoga Therapy), Certified YCB Level 3', 'Lead Yoga Therapist & Meditation Master', 'yoga_wellness', 14, 'Seva Yoga Peeth', 'New Delhi', 'Delhi', 'Daily Morning & Evening', 'Therapeutic Yoga, Pranayama for Heart & Lungs, Stress Relief Meditation', 1, 'active');

-- Schools
INSERT INTO `schools` (`name`, `slug`, `principal_name`, `contact_person`, `phone`, `email`, `address`, `city`, `state`, `mou_date`, `students_benefited`, `active_programs`, `description`, `is_featured`, `status`) VALUES
('Sarvodaya Bal Vidyalaya Senior Secondary School', 'sarvodaya-bal-vidyalaya-delhi', 'Shri R. K. Mishra', 'Mr. Amit Chauhan (Vice Principal)', '+91 11 2341 5566', 'sbv.delhi@gov.in', 'Govt Complex, Shakarpur', 'New Delhi', 'Delhi', '2021-07-15', 1850, 'Annual Health Checkups, Eye Screening & Free Glasses, NCERT Study Kits, Yoga Morning Sessions', 'A premier government model school with over 1,800 students. Seva Foundation has completed 4 health camps, distributed 450 free spectacle pairs, and setup a smart learning support room.', 1, 'active'),
('Dr. APJ Abdul Kalam Memorial Public School', 'apj-abdul-kalam-memorial-school', 'Mrs. Sunita Rawat', 'Mrs. Neelam Grover', '+91 98102 99881', 'contact@kalammemorialschool.edu', 'Sec 62, Institutional Area', 'Noida', 'Uttar Pradesh', '2022-03-10', 1200, 'Career Guidance Workshops, Dental Hygiene Drives, Tree Plantation & Eco Clubs, Meditation Classes', 'Partnered under the Holistic Youth Development MOU. We conduct monthly career guidance sessions, NCERT exam doubt-solving batches, and regular dental checkups.', 1, 'active'),
('Navodaya Adarsh Vidyalaya', 'navodaya-adarsh-vidyalaya', 'Dr. Hemant Joshi', 'Mr. Pawan Sen', '+91 98711 22334', 'navodaya.adarsh@gmail.com', 'Sohna Road, Badshahpur', 'Gurugram', 'Haryana', '2023-01-20', 950, 'Free Eye Camps, Health & Nutrition Awareness, Class 10/12 Science Kits, Yoga Training', 'Focusing on rural and semi-urban children. Over 950 students actively benefit from nutritious snacks distribution, health awareness seminars, and NCERT practical workshops.', 1, 'active');

-- Partners
INSERT INTO `partners` (`name`, `partner_type`, `contact_person`, `phone`, `email`, `website`, `city`, `state`, `mou_signed_date`, `summary`, `is_featured`) VALUES
('Rotary Club International (Delhi Central)', 'community', 'Mr. Rajiv Singhal', '+91 98110 55443', 'rotary.delhicentral@org.in', 'https://rotary.org', 'New Delhi', 'Delhi', '2020-06-15', 'Co-sponsoring 25 blood donation camps and distributing 10,000 education kits annually.', 1),
('TechIndia CSR Foundation', 'csr_corporate', 'Ms. Ananya Roy', '+91 11 4050 6070', 'csr@techindia.com', 'https://techindia.com/csr', 'Noida', 'Uttar Pradesh', '2021-09-01', 'Corporate Social Responsibility grant funding our mobile diagnostic van and digital classroom setups.', 1),
('Indian Red Cross Society', 'community', 'Dr. K. S. Tyagi', '+91 11 2371 6441', 'bloodbank@redcrossindia.org', 'https://redcross.org.in', 'New Delhi', 'Delhi', '2019-03-12', 'Official nodal partner for voluntary blood donation collection, cold storage, and emergency blood dispatch.', 1),
('Dhanvantari Ayurvedic Pharmacy', 'other', 'Dr. Rameshwar Dayal', '+91 98990 12345', 'info@dhanvantariherbal.in', 'https://dhanvantariherbal.in', 'Haridwar', 'Uttarakhand', '2022-05-18', 'Provides authentic Ayurvedic medicines, herbal immunity boosters, and Kwath packets for free distribution.', 1);

-- MOUs
INSERT INTO `mous` (`title`, `partner_name`, `partner_type`, `signed_date`, `valid_upto`, `document_file`, `key_objectives`, `scope_of_work`, `is_public`) VALUES
('Comprehensive School Healthcare & Student Wellness MOU', 'Sarvodaya Bal Vidyalaya', 'school', '2021-07-15', '2026-07-14', 'mou-sarvodaya-vidyalaya-2021.pdf', 'Conduct bi-annual health screenings, provide free eye glasses, install water purifiers, and run daily yoga sessions.', 'Seva Foundation deploys doctors and yoga acharyas; school provides auditorium and logistics.', 1),
('Tertiary Medical Care & Subsidized Surgery Partnership', 'AIIMS Associated Rural Health Center', 'hospital', '2021-04-10', '2025-04-09', 'mou-aiims-rural-center-2021.pdf', 'Provide specialized referrals, emergency bed reservation, and zero-cost surgery for underprivileged camp patients.', 'Emergency ambulance assistance and priority OPD slots for NGO-verified families.', 1),
('CSR Grant for Digital Classrooms & Mobile Health Van', 'TechIndia CSR Foundation', 'csr', '2021-09-01', '2025-08-31', 'mou-techindia-csr-2021.pdf', 'Empower 10 government schools with digital learning and run a 7-day-a-week mobile medical dispensary.', 'Quarterly impact auditing, transparent CSR utilization certificates, and direct beneficiary tracking.', 1);

-- Events
INSERT INTO `events` (`title`, `slug`, `category`, `short_description`, `description`, `event_date`, `start_time`, `end_time`, `venue`, `address`, `city`, `state`, `organizer_name`, `doctor_partner_info`, `registration_required`, `registration_deadline`, `max_participants`, `registered_count`, `is_featured`, `status`) VALUES
('Mega Community Health & Eye Screening Camp', 'mega-health-eye-camp-delhi', 'medical_camp', 'Free full-body health checkups, blood sugar test, ECG, eye refraction, and free medicine distribution for all families.', 'Join us for our flagship Mega Medical Camp. Over 15 specialist physicians (Cardiologists, Pediatricians, Gynecologists, and General Physicians) along with optometrists will provide thorough clinical examinations. All prescribed medicines and reading glasses will be provided on the spot at 100% zero cost. Refreshments will be provided for all attendees.', DATE_ADD(CURRENT_DATE, INTERVAL 14 DAY), '09:00:00', '16:00:00', 'Community Center Auditorium', 'Pocket B, Mayur Vihar Phase 2', 'New Delhi', 'Delhi', 'Seva Foundation & AIIMS Doctors Team', 'Dr. Anand Verma & Dr. Sunita Deshmukh', 1, DATE_ADD(CURRENT_DATE, INTERVAL 12 DAY), 500, 142, 1, 'upcoming'),
('Voluntary Blood Donation Drive - Save Lives', 'voluntary-blood-donation-drive', 'blood_donation', 'Donate blood and be a hero! Certified Red Cross blood collection van with free health test and donor refreshment kit.', 'Every single blood donation can save up to 3 lives. In collaboration with the Indian Red Cross Society, we invite healthy citizens between 18 to 60 years to donate blood. Donors receive a comprehensive hemoglobin and blood group report, a donor recognition card, snacks, and a memento certificate.', DATE_ADD(CURRENT_DATE, INTERVAL 21 DAY), '10:00:00', '17:00:00', 'Rotary Community Hall', 'Near City Center Metro, Sec 29', 'Noida', 'Uttar Pradesh', 'Seva Foundation & Indian Red Cross', 'Dr. K. S. Tyagi (Blood Bank In-charge)', 1, DATE_ADD(CURRENT_DATE, INTERVAL 20 DAY), 300, 89, 1, 'upcoming'),
('Free Yoga & Pranayama Wellness Mahotsav', 'yoga-pranayama-wellness-mahotsav', 'yoga_camp', '3-day holistic yoga immersion covering stress reduction, back-pain management, Pranayama, and guided Dhyana.', 'Rejuvenate your body and calm your restless mind. Renowned Yoga Acharyas will guide participants through simple yet profound yogic postures, Anulom Vilom, Kapalbhati, Bhramari, and deep relaxation techniques. Specially beneficial for students, working professionals, and seniors.', DATE_ADD(CURRENT_DATE, INTERVAL 28 DAY), '06:00:00', '08:30:00', 'Nehru Park Central Lawn', 'Chanakyapuri', 'New Delhi', 'Delhi', 'Seva Yoga Peeth', 'Acharya Devendra Nautiyal', 1, DATE_ADD(CURRENT_DATE, INTERVAL 27 DAY), 250, 65, 1, 'upcoming'),
('Class 10th & 12th Board Exam Motivation & Science Kit Camp', 'board-exam-motivation-science-kit-camp', 'education_camp', 'Interactive session on NCERT revision strategies, exam stress busting, career options, and free study kits.', 'A high-impact educational workshop for high school students appearing in upcoming board exams. Senior educators and counselors will share time management secrets, high-yield NCERT study techniques, and provide free textbook guide bundles and geometry kits.', DATE_ADD(CURRENT_DATE, INTERVAL 35 DAY), '10:00:00', '14:00:00', 'Sarvodaya Bal Vidyalaya Auditorium', 'Shakarpur', 'New Delhi', 'Delhi', 'Seva Education Cell', 'Guest Panel of NCERT Authors & Teachers', 1, DATE_ADD(CURRENT_DATE, INTERVAL 34 DAY), 400, 110, 0, 'upcoming');

-- Testimonials
INSERT INTO `testimonials` (`name`, `designation`, `organization`, `content`, `rating`, `category`, `is_featured`) VALUES
('Ramesh Kumar Gupta', 'Beneficiary - Free Cataract Surgery', 'Trilokpuri, Delhi', 'I had lost vision in my right eye due to mature cataract and could not afford private hospital costs. Seva Foundation arranged my checkup, transport, and successful surgery at zero charge. Today I can see clearly again and support my family.', 5, 'patient', 1),
('Pooja Kumari', 'Class 11 Student', 'Sarvodaya Bal Vidyalaya', 'The NCERT study kits and remedial science classes organized by Seva Foundation helped me score 92% in my 10th boards. Their career mentorship gave me confidence to pursue medical entrance.', 5, 'student', 1),
('Dr. Anand Verma', 'Senior Consultant MD', 'AIIMS Associated Health Center', 'Volunteering with Seva Foundation has been one of the most fulfilling experiences of my career. The organization runs ethical, highly disciplined, and transparent healthcare camps that genuinely reach the poorest of the poor.', 5, 'doctor', 1),
('Mrs. Sunita Rawat', 'Principal', 'APJ Abdul Kalam Memorial School', 'Our school MOU with Seva Foundation has transformed our student wellness. From regular dental checks to yoga mornings and career counseling, their team is deeply dedicated and professional.', 5, 'partner', 1);

-- Gallery Categories
INSERT INTO `gallery_categories` (`name`, `slug`, `sort_order`) VALUES
('Medical Camps', 'medical-camps', 1),
('Blood Donation', 'blood-donation', 2),
('Education & Schools', 'education-schools', 3),
('Yoga & AYUSH Wellness', 'yoga-ayush', 4),
('MOU Signings & Events', 'mou-events', 5);

-- Blog Categories
INSERT INTO `blog_categories` (`name`, `slug`, `description`) VALUES
('Healthcare & Prevention', 'healthcare-prevention', 'Articles on preventive medicine, checkups, and healthy lifestyle tips'),
('Education & Youth Empowerment', 'education-youth', 'Insights on NCERT study tips, career guidance, and student wellbeing'),
('AYUSH & Holistic Living', 'ayush-holistic', 'Ayurveda, Homeopathy, Yoga asanas, and natural remedies'),
('Community News & NGO Impact', 'community-news', 'Updates on recent camps, school MOUs, and philanthropic drives');

-- Blog Posts
INSERT INTO `blog_posts` (`category_id`, `author_id`, `title`, `slug`, `short_description`, `content`, `tags`, `author_name`, `views_count`, `status`, `seo_title`, `seo_description`) VALUES
(1, 1, 'Why Preventive Medical Checkups Are Essential for Every Family', 'why-preventive-medical-checkups-essential', 'Early detection of hypertension, diabetes, and vision defects can prevent severe complications and save thousands in hospital bills.', '<p>Most chronic ailments such as hypertension, type-2 diabetes, cardiovascular stress, and glaucoma develop silently without dramatic early symptoms. By the time noticeable distress occurs, significant organ damage may have already transpired.</p><h3>The Power of Timely Screening</h3><p>At Seva Foundation medical camps, routine screening of vitals, blood glucose, and ECG helps identify high-risk individuals before emergencies occur. Lifestyle modifications and early medical intervention remain the most cost-effective healthcare strategy.</p><ul><li>Regular blood pressure monitoring after age 30.</li><li>Annual fasting blood sugar and HbA1c testing.</li><li>Comprehensive eye checkups every 12 months.</li><li>Routine pediatric growth and immunization tracking.</li></ul><p>Our mobile healthcare teams continue to travel across remote corners to ensure no family misses essential preventive diagnosis.</p>', 'healthcare, prevention, medical camps, wellness', 'Dr. Anand Verma', 340, 'published', 'Why Preventive Health Checkups Are Essential | Seva NGO', 'Learn why annual preventive medical checkups and early health screening can save lives and reduce chronic disease complications.'),
(2, 1, 'How NCERT-Focused Conceptual Learning Transforms Student Scores', 'how-ncert-focused-learning-transforms-student-scores', 'Mastering core NCERT concepts rather than rote learning builds the foundation for board exams and competitive entrances.', '<p>The National Council of Educational Research and Training (NCERT) textbooks are crafted by leading education experts to impart crystal-clear fundamentals. Whether a student aims for Class 10/12 board excellence or national competitive exams like NEET and JEE, NCERT is the ultimate holy grail.</p><h3>Key Strategies for NCERT Mastery</h3><ol><li><strong>Read Every Line of Theory:</strong> Important definitions and subtle conceptual nuances are embedded directly inside NCERT prose.</li><li><strong>Solve In-Text Exemplar Problems:</strong> Tackle all illustrative examples and back-of-the-chapter exercises without skipping.</li><li><strong>Create Summary Mind Maps:</strong> Condense large chapters into visual diagrams and formulae sheets for rapid pre-exam revision.</li></ol><p>Through our School MOU programs, Seva Foundation equips underprivileged students with free NCERT textbook sets, study guides, and dedicated mentor support.</p>', 'education, ncert, board exams, career, students', 'Education Research Cell', 415, 'published', 'Mastering NCERT for Board Exams & Competitive Success | Seva NGO', 'Expert guide on utilizing NCERT books for top scores in Class 10 and 12 board examinations.'),
(3, 1, 'Ayurveda & Dinacharya: Simple Daily Habits for Vibrant Longevity', 'ayurveda-dinacharya-habits-vibrant-longevity', 'Ancient Ayurvedic wisdom teaches us that aligning daily routines with nature restores internal dosha balance.', '<p>Dinacharya is the ancient Ayurvedic concept of a structured daily regimen that harmonizes biological rhythms with the diurnal cycles of nature. By establishing conscious habits from sunrise to bedtime, one naturally eliminates toxins (Ama) and reinforces immunity (Ojas).</p><h3>Core Principles of Dinacharya</h3><ul><li><strong>Brahma Muhurta Awakening:</strong> Waking up before sunrise fills the mind with clarity and sattvic energy.</li><li><strong>Ushapan (Morning Warm Water):</strong> Drinking 2 glasses of lukewarm water on an empty stomach stimulates peristalsis and cleanses the digestive tract.</li><li><strong>Pranayama & Asanas:</strong> 20 minutes of gentle yoga postures and conscious breathing harmonizes Vata, Pitta, and Kapha.</li><li><strong>Mindful Seasonal Eating (Ahara):</strong> Consuming freshly cooked, warm meals appropriate for your personal Prakriti.</li></ul><p>Visit our AYUSH wellness centers to consult with certified Ayurvedic Vaidyas and learn customized lifestyle plans tailored for your body constitution.</p>', 'ayurveda, dinacharya, wellness, health, yoga', 'Acharya Devendra Nautiyal', 290, 'published', 'Ayurveda Dinacharya Daily Routine for Longevity | Seva NGO', 'Discover authentic Ayurvedic daily practices (Dinacharya) to balance doshas, boost immunity, and maintain vitality.');

-- Product Categories
INSERT INTO `product_categories` (`name`, `slug`, `description`) VALUES
('Herbal Health Supplements', 'herbal-supplements', 'Pure Ayurvedic herbal powders, Kwath, and immunity tonics'),
('Wellness & Personal Care', 'wellness-personal-care', 'Herbal tooth powders, massage oils, and natural soaps'),
('Yoga & Meditation Accessories', 'yoga-accessories', 'Eco-friendly yoga mats, meditation cushions, and copper bottles');

-- Products
INSERT INTO `products` (`category_id`, `name`, `slug`, `sku`, `price`, `mrp`, `short_description`, `description`, `benefits`, `ingredients`, `usage_instructions`, `stock_quantity`, `is_featured`, `status`) VALUES
(1, 'Ayush Kwath Immune Booster Churna (100g)', 'ayush-kwath-immune-booster-100g', 'AYUSH-KW-100', 150.00, 180.00, 'Ministry of AYUSH recommended herbal formula for respiratory health and viral immunity.', 'Ayush Kwath is an authentic Ayurvedic decoction formulated according to the guidelines of the Ministry of AYUSH. Packed with the synergic power of Tulsi, Dalchini, Sunthi, and Krishna Marich, it acts as a potent shield against seasonal infections, sore throat, and low vitality.', 'Enhances natural immunity\nClears respiratory congestion\nPowerful antioxidant properties\n100% pure vegetarian & herbal', 'Tulsi (Holy Basil), Dalchini (Cinnamon), Sunthi (Dry Ginger), Krishna Marich (Black Pepper)', 'Boil 3g powder in 150ml water for 5 minutes. Filter and consume warm like herbal tea once or twice daily. Add jaggery or lemon as per taste.', 250, 1, 'active'),
(1, 'Pure Triphala Churna for Digestive Balance (200g)', 'pure-triphala-churna-200g', 'TRIPH-200', 130.00, 160.00, 'Traditional blend of Amla, Haritaki, and Bibhitaki for gentle gut cleansing and detox.', 'Triphala is one of the most revered formulations in classical Ayurveda. It gently cleanses the colon, regulates bowel movements, rejuvenates digestion, and supports ocular wellness naturally without forming habit dependence.', 'Supports natural bowel regularity\nDetoxifies gastrointestinal tract\nRich in Vitamin C and natural antioxidants\nPromotes healthy metabolism and skin', 'Equal proportions of certified organic Amla (Emblica officinalis), Haritaki (Terminalia chebula), and Bibhitaki (Terminalia bellirica)', 'Take 1 teaspoon (3-5g) at bedtime with warm water or milk, or as directed by an Ayurvedic physician.', 180, 1, 'active'),
(2, 'Ayurvedic Maha Narayan Pain Relief Oil (100ml)', 'ayurvedic-maha-narayan-oil-100ml', 'MN-OIL-100', 220.00, 260.00, 'Therapeutic herbal oil for soothing joint pain, arthritis, muscle stiffness, and backache.', 'Maha Narayan Taila is an authentic classical medicated oil prepared with 30+ rejuvenating herbs boiled in sesame oil. It penetrates deep into tissues, providing swift relief from joint discomfort, sciatica, cervical spondylitis, and muscular fatigue.', 'Alleviates joint and knee pain\nRelieves muscle stiffness and spasms\nImproves joint flexibility and blood circulation\nIdeal for post-yoga and therapeutic massage', 'Dashamoola herbs, Ashwagandha, Bala, Shatavari, Pure Sesame (Til) Oil, Camphor', 'Warm the oil slightly and massage gently over affected joints or muscles for 10-15 minutes followed by warm fomentation.', 140, 1, 'active');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

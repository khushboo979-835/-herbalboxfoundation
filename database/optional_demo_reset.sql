-- ============================================================================
-- NGO Management System - Safe Demo Reset Script
-- Database: ngo_management
-- Description: Resets all transaction, inquiry, and operational tables to 
--              pristine initial state without dropping schema structure.
-- ============================================================================

USE `ngo_management`;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Inquiries & Registrations
TRUNCATE TABLE `contact_enquiries`;
TRUNCATE TABLE `partnership_enquiries`;
TRUNCATE TABLE `event_registrations`;
TRUNCATE TABLE `volunteer_interests`;
TRUNCATE TABLE `volunteers`;
TRUNCATE TABLE `donations`;

-- 2. Audit & Session Logs
TRUNCATE TABLE `activity_logs`;
TRUNCATE TABLE `admin_sessions`;

-- 3. Junction Tables
TRUNCATE TABLE `event_doctors`;
TRUNCATE TABLE `event_partners`;
TRUNCATE TABLE `blog_post_tags`;

-- 4. Content & Operations (Optional: uncomment to wipe all demo content)
-- TRUNCATE TABLE `events`;
-- TRUNCATE TABLE `blog_posts`;
-- TRUNCATE TABLE `blog_tags`;
-- TRUNCATE TABLE `blog_categories`;
-- TRUNCATE TABLE `gallery_images`;
-- TRUNCATE TABLE `gallery_categories`;
-- TRUNCATE TABLE `videos`;
-- TRUNCATE TABLE `testimonials`;
-- TRUNCATE TABLE `products`;
-- TRUNCATE TABLE `product_categories`;
-- TRUNCATE TABLE `mous`;
-- TRUNCATE TABLE `schools`;
-- TRUNCATE TABLE `diagnostic_centres`;
-- TRUNCATE TABLE `doctors`;
-- TRUNCATE TABLE `hospitals`;
-- TRUNCATE TABLE `partners`;
-- TRUNCATE TABLE `education_programs`;
-- TRUNCATE TABLE `healthcare_services`;
-- TRUNCATE TABLE `programs`;
-- TRUNCATE TABLE `program_categories`;
-- TRUNCATE TABLE `about_documents`;
-- TRUNCATE TABLE `media_files`;

SET FOREIGN_KEY_CHECKS = 1;

-- Confirmation Log
SELECT 'Demo operational and transactional data successfully cleared.' AS `Reset_Status`;

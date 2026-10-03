<?php
/**
 * Master Application Configuration
 * NGO Website & Admin Panel
 */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Start Secure Session if not already active
if (session_status() === PHP_SESSION_NONE) {
    // Session security parameters
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_samesite', 'Lax');
    
    // Check if HTTPS is active
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
    
    session_start();
}

// Include required core files
require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/database.php';
require_once APP_ROOT . '/includes/functions.php';
require_once APP_ROOT . '/includes/csrf.php';

// Detect Base URL dynamically
function getBaseUrl(): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $dir = dirname($scriptName);
    
    // Normalize path separators
    $dir = str_replace('\\', '/', $dir);
    
    // Strip trailing /admin or subfolders if running inside admin
    $dir = preg_replace('/\/admin(\/.*)?$/', '', $dir);
    
    $basePath = rtrim($dir, '/');
    return $protocol . $host . $basePath;
}

define('BASE_URL', getBaseUrl());
define('ADMIN_URL', BASE_URL . '/admin');

// Global cache for site settings with production defaults
function getSiteSettings(): array {
    static $settings = null;
    if ($settings === null) {
        $settings = [
            'site_name' => 'Herbalbox Foundation',
            'site_tagline' => 'Health • Education • Better Tomorrow',
            'tagline' => 'Health • Education • Better Tomorrow',
            'site_phone' => '+91 92340 55507',
            'phone' => '+91 92340 55507',
            'whatsapp_number' => '919234055507',
            'whatsapp' => '+919234055507',
            'site_email' => 'contact@herbalboxfoundation.org',
            'email' => 'contact@herbalboxfoundation.org',
            'site_address' => 'Herbalbox Foundation, Near Birla Open Minds International School, Konhara Road, Hajipur, Vaishali, Bihar - 844101',
            'address' => 'Herbalbox Foundation, Near Birla Open Minds International School, Konhara Road, Hajipur, Vaishali, Bihar - 844101',
            'city' => 'Hajipur',
            'state' => 'Bihar',
            'pincode' => '844101',
            'cin_number' => 'U86901BR2026NPL087665',
            'ngo_reg_number' => 'CIN: U86901BR2026NPL087665',
            'legal_status' => 'Company Limited by Guarantee (Companies Act, 2013)',
            'registered_office' => 'Patna, Bihar, India',
            'incorporation_date' => '29 August 2026',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=61594374894081',
            'instagram_url' => 'https://www.instagram.com/herbalboxfoundation/',
            'youtube_url' => 'https://youtube.com/@herbalboxfoundation',
            'linkedin_url' => 'https://linkedin.com/company/herbalboxfoundation',
            'logo' => 'assets/images/logo.png',
            'favicon' => 'assets/images/favicon.png',
            'bank_name' => 'UNION BANK OF INDIA',
            'bank_account_name' => 'आनन्द जानकी जनकल्याण समिति',
            'bank_account_number' => '195721010000222',
            'bank_ifsc' => 'UBIN0919578',
            'bank_upi_id' => 'anandjankijks@ybl',
            'bank_qr_image' => 'assets/images/qr-code.png',
            'operational_states' => 'New Delhi, Uttar Pradesh, Uttarakhand, Bihar, Jharkhand, Odisha, Madhya Pradesh, Chhattisgarh, West Bengal, Assam',
            'google_map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3596.3776461942125!2d85.2132!3d25.6885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ed586c9945a00b%3A0x6a2c3a52e18d6e99!2sBirla%20Open%20Minds%20International%20School%2C%20Hajipur!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin'
        ];

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT * FROM site_settings LIMIT 1");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                foreach ($row as $k => $v) {
                    if ($v !== null && $v !== '') {
                        $settings[$k] = $v;
                        $settings['site_' . $k] = $v;
                    }
                }
            }
        } catch (Exception $e) {
            // graceful fallback to predefined settings
        }
    }
    return $settings;
}

function getSetting(string $key, string $default = ''): string {
    $settings = getSiteSettings();
    return (string)($settings[$key] ?? $default);
}

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

// Global cache for site settings
function getSiteSettings(): array {
    static $settings = null;
    if ($settings === null) {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            $settings = $rows ?: [];
        } catch (Exception $e) {
            $settings = [];
        }
    }
    return $settings;
}

function getSetting(string $key, string $default = ''): string {
    $settings = getSiteSettings();
    return $settings[$key] ?? $default;
}

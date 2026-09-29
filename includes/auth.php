<?php
/**
 * Admin Authentication & Authorization Manager
 */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    exit('Direct access not permitted');
}

/**
 * Check if an admin is currently authenticated
 */
function isAdminLoggedIn(): bool {
    if (!empty($_SESSION['admin_id']) && !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        // Enforce session expiration after 2 hours of inactivity
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
            adminLogout();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }
    return false;
}

/**
 * Get current logged in admin session record
 */
function getCurrentAdmin(): ?array {
    if (!isAdminLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['admin_id'],
        'name' => $_SESSION['admin_name'] ?? 'Admin',
        'email' => $_SESSION['admin_email'] ?? '',
        'username' => $_SESSION['admin_username'] ?? '',
        'role' => $_SESSION['admin_role'] ?? 'admin',
        'avatar' => $_SESSION['admin_avatar'] ?? null
    ];
}

/**
 * Guard page: Redirect to login if unauthenticated
 */
function requireAdminLogin(): void {
    if (!isAdminLoggedIn()) {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
        header('Location: ' . BASE_URL . '/admin/login.php?redirect=' . urlencode($currentUrl));
        exit;
    }
}

/**
 * Guard page: Require superadmin role
 */
function requireSuperAdmin(): void {
    requireAdminLogin();
    if (($_SESSION['admin_role'] ?? '') !== 'superadmin') {
        http_response_code(403);
        die("Access Denied: You do not have super administrator permissions.");
    }
}

/**
 * Destroy admin session and logout
 */
function adminLogout(): void {
    if (isset($_SESSION['admin_id'])) {
        logActivity('Admin Logout', 'auth', (int)$_SESSION['admin_id'], 'Logged out successfully');
    }
    unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_email'], $_SESSION['admin_username'], $_SESSION['admin_role'], $_SESSION['admin_avatar'], $_SESSION['admin_logged_in'], $_SESSION['last_activity']);
}

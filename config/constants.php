<?php
/**
 * Application Constants
 * NGO Seva Arogya & Shiksha Foundation
 */

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Upload Directories
define('UPLOAD_DIR', APP_ROOT . DIRECTORY_SEPARATOR . 'uploads');
define('UPLOAD_URL', 'uploads/');

// Allowed file upload extensions and mime types
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
define('ALLOWED_IMAGE_MIMES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_DOC_EXTENSIONS', ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']);
define('ALLOWED_DOC_MIMES', [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'image/jpeg',
    'image/png'
]);

define('MAX_UPLOAD_IMAGE_SIZE', 5 * 1024 * 1024); // 5 MB
define('MAX_UPLOAD_DOC_SIZE', 15 * 1024 * 1024); // 15 MB

// Role Definitions
define('ROLE_SUPERADMIN', 'superadmin');
define('ROLE_ADMIN', 'admin');
define('ROLE_EDITOR', 'editor');

// Currency symbol
define('CURRENCY_SYMBOL', '₹');
define('CURRENCY_CODE', 'INR');

// Default Pagination Limits
define('ADMIN_PER_PAGE', 15);
define('FRONTEND_PER_PAGE', 9);

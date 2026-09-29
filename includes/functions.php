<?php
/**
 * Global Helper Functions
 * NGO Seva Foundation
 */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    exit('Direct access not permitted');
}

/**
 * Escape HTML output securely
 */
function e(?string $string): string {
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Sanitize string input
 */
function sanitize(mixed $input): string {
    if (is_array($input)) {
        return '';
    }
    return trim((string)$input);
}

/**
 * Generate clean URL slug
 */
function slugify(string $text): string {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a-' . time() : $text;
}

/**
 * Secure File Uploader
 * 
 * @param array $file $_FILES['input_name']
 * @param string $folder Subdirectory under uploads/
 * @param string $type 'image' or 'document'
 * @return array ['success' => bool, 'filename' => string, 'error' => string]
 */
function uploadFile(array $file, string $folder = 'general', string $type = 'image'): array {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'filename' => '', 'error' => 'Invalid file parameter.'];
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return ['success' => false, 'filename' => '', 'error' => 'No file was uploaded.'];
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return ['success' => false, 'filename' => '', 'error' => 'Uploaded file exceeds the maximum allowed file size.'];
        default:
            return ['success' => false, 'filename' => '', 'error' => 'Unknown upload error occurred.'];
    }

    $allowedExtensions = ($type === 'image') ? ALLOWED_IMAGE_EXTENSIONS : ALLOWED_DOC_EXTENSIONS;
    $allowedMimes = ($type === 'image') ? ALLOWED_IMAGE_MIMES : ALLOWED_DOC_MIMES;
    $maxSize = ($type === 'image') ? MAX_UPLOAD_IMAGE_SIZE : MAX_UPLOAD_DOC_SIZE;

    if ($file['size'] > $maxSize) {
        $mb = round($maxSize / (1024 * 1024));
        return ['success' => false, 'filename' => '', 'error' => "File size cannot exceed {$mb}MB."];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExtensions, true)) {
        return ['success' => false, 'filename' => '', 'error' => 'Invalid file format. Allowed: ' . implode(', ', $allowedExtensions)];
    }

    // Verify MIME type using finfo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, $allowedMimes, true)) {
        return ['success' => false, 'filename' => '', 'error' => 'File MIME verification failed.'];
    }

    // Target upload folder creation
    $targetDir = UPLOAD_DIR . DIRECTORY_SEPARATOR . trim($folder, '/\\');
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Secure unique filename
    $filename = sprintf('%s_%s.%s', bin2hex(random_bytes(8)), time(), $ext);
    $destination = $targetDir . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'filename' => '', 'error' => 'Failed to save uploaded file.'];
    }

    // Return relative path for web usage
    $relativePath = trim($folder, '/') . '/' . $filename;
    return ['success' => true, 'filename' => $relativePath, 'error' => ''];
}

/**
 * Format currency in Indian numbering format
 */
function formatCurrency(float|int|string $amount): string {
    $amount = (float)$amount;
    return CURRENCY_SYMBOL . ' ' . number_format($amount, 2);
}

/**
 * Format date for friendly UI display
 */
function formatDate(?string $date, string $format = 'd M Y'): string {
    if (empty($date) || $date === '0000-00-00') {
        return '-';
    }
    return date($format, strtotime($date));
}

function formatDateTime(?string $dateTime, string $format = 'd M Y, h:i A'): string {
    if (empty($dateTime) || $dateTime === '0000-00-00 00:00:00') {
        return '-';
    }
    return date($format, strtotime($dateTime));
}

/**
 * Truncate long text cleanly with ellipsis
 */
function truncateText(?string $text, int $limit = 120): string {
    $text = strip_tags((string)$text);
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    return mb_substr($text, 0, $limit) . '...';
}

/**
 * Generate unique random reference code (e.g., DON-2026-X8F9, VOL-8839)
 */
function generateReferenceCode(string $prefix = 'SEVA'): string {
    return strtoupper($prefix . '-' . date('Y') . '-' . substr(bin2hex(random_bytes(3)), 0, 6));
}

/**
 * Session Flash Message Handlers
 */
function setFlashMessage(string $type, string $message): void {
    $_SESSION['flash_message'] = [
        'type' => $type, // 'success', 'danger', 'warning', 'info'
        'text' => $message
    ];
}

function hasFlashMessage(): bool {
    return !empty($_SESSION['flash_message']);
}

function getFlashMessage(): ?array {
    if (hasFlashMessage()) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $msg;
    }
    return null;
}

function displayFlashMessage(): string {
    $msg = getFlashMessage();
    if (!$msg) return '';
    $type = htmlspecialchars($msg['type']);
    $text = htmlspecialchars($msg['text']);
    $icon = match($type) {
        'success' => 'fa-check-circle',
        'danger' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        default => 'fa-info-circle'
    };
    return "<div class=\"alert alert-{$type} alert-dismissible fade show shadow-sm\" role=\"alert\">
        <i class=\"fas {$icon} me-2\"></i> {$text}
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
    </div>";
}

/**
 * Log Admin or System Activities
 */
function logActivity(string $action, string $module, ?int $recordId = null, ?string $description = null): void {
    try {
        $pdo = Database::getConnection();
        $adminId = $_SESSION['admin_id'] ?? null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $stmt = $pdo->prepare("INSERT INTO activity_logs (admin_id, action, module, record_id, description, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$adminId, $action, $module, $recordId, $description, $ip]);
    } catch (Exception $e) {
        // Fail silently to avoid breaking core transaction
        error_log("Activity log error: " . $e->getMessage());
    }
}

/**
 * Render Star Rating HTML
 */
function renderRatingStars(int $rating = 5): string {
    $html = '<div class="text-warning rating-stars">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $html .= '<i class="fas fa-star"></i> ';
        } else {
            $html .= '<i class="far fa-star"></i> ';
        }
    }
    $html .= '</div>';
    return $html;
}

/**
 * Get Image URL with fallback placeholder
 */
function getImageUrl(?string $imagePath, string $fallback = 'assets/images/placeholder.jpg'): string {
    if (!empty($imagePath)) {
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }
        $fullPath = APP_ROOT . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $imagePath);
        if (file_exists($fullPath)) {
            return BASE_URL . '/uploads/' . ltrim($imagePath, '/');
        }
    }
    if (str_starts_with($fallback, 'http://') || str_starts_with($fallback, 'https://')) {
        return $fallback;
    }
    return BASE_URL . '/' . ltrim($fallback, '/');
}

<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

if (isAdminLoggedIn()) {
    header('Location: ' . ADMIN_URL . '/dashboard.php');
} else {
    header('Location: ' . ADMIN_URL . '/login.php');
}
exit;

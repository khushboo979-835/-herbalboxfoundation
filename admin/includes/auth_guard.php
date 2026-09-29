<?php
declare(strict_types=1);

if (!defined('APP_ROOT')) {
    require_once __DIR__ . '/../../config/config.php';
}

requireAdminLogin();
$currentAdmin = getCurrentAdmin();

<?php
declare(strict_types=1);

if (!defined('APP_ROOT')) {
    require_once __DIR__ . '/../../config/config.php';
}

require_once __DIR__ . '/auth_guard.php';
$siteName = getSetting('site_name', 'Seva Foundation');
$adminTitle = $adminTitle ?? 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminTitle); ?> | <?= e($siteName); ?> Control Center</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Admin Master CSS -->
    <link href="<?= BASE_URL; ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body class="admin-body">

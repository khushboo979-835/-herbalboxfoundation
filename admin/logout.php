<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

adminLogout();
header('Location: ' . ADMIN_URL . '/login.php');
exit;

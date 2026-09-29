<?php
/**
 * Database Backup & Export Utility
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Handle 1-Click Backup Export
if (isset($_GET['action']) && $_GET['action'] === 'download_sql') {
    $tables = [];
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $sqlDump = "-- ==========================================================\n";
    $sqlDump .= "-- NGO Database Backup Dump\n";
    $sqlDump .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
    $sqlDump .= "-- Host: " . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\n";
    $sqlDump .= "-- ==========================================================\n\n";
    $sqlDump .= "SET FOREIGN_KEY_CHECKS = 0;\nSET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\nSTART TRANSACTION;\n\n";

    foreach ($tables as $table) {
        // Table structure
        $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_ASSOC);
        $sqlDump .= "\n-- Table structure for table `{$table}`\n";
        $sqlDump .= "DROP TABLE IF EXISTS `{$table}`;\n";
        $sqlDump .= $createStmt['Create Table'] . ";\n\n";

        // Table data
        $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            $sqlDump .= "-- Dumping data for table `{$table}`\n";
            foreach ($rows as $r) {
                $keys = array_map(fn($k) => "`$k`", array_keys($r));
                $values = array_map(function($v) use ($pdo) {
                    if ($v === null) return "NULL";
                    return $pdo->quote((string)$v);
                }, array_values($r));

                $sqlDump .= "INSERT INTO `{$table}` (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $values) . ");\n";
            }
            $sqlDump .= "\n";
        }
    }

    $sqlDump .= "SET FOREIGN_KEY_CHECKS = 1;\nCOMMIT;\n";

    logActivity('Generated Database Backup', 'backup', null, 'Downloaded complete SQL dump');

    $filename = 'ngoseva_db_backup_' . date('Y-m-d_H-i-s') . '.sql';
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($sqlDump));
    echo $sqlDump;
    exit;
}

$adminTitle = 'Database Backup Tools';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Database Backup & Maintenance Tools</h4>
                <p class="text-muted small mb-0">Generate complete SQL database dumps to protect donor records, student MOUs, and site configurations.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="card-admin max-w-700">
            <div class="card-header"><i class="fas fa-database text-primary me-2"></i> 1-Click MySQL Database Backup</div>
            <div class="p-4">
                <div class="p-3 bg-light rounded-3 mb-4">
                    <h6 class="fw-bold mb-2"><i class="fas fa-info-circle text-primary me-1"></i> Backup Features:</h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li>Includes full table schemas and relations</li>
                        <li>Captures all donations, 80G receipts, and volunteer records</li>
                        <li>Generates clean UTF-8 MySQL 8.0+ compatible SQL file</li>
                        <li>Can be imported into phpMyAdmin, cPanel, or Hostinger MySQL manager</li>
                    </ul>
                </div>

                <div class="text-center py-3">
                    <a href="<?= ADMIN_URL; ?>/backup/index.php?action=download_sql" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-download me-2"></i> Download Full Database SQL Dump
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

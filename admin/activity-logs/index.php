<?php
/**
 * System Audit Trail & Activity Logs
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Clear old logs (older than 90 days)
if (isset($_GET['cleanup'])) {
    $pdo->query("DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)");
    setFlashMessage('success', 'Cleared audit logs older than 90 days.');
    header('Location: ' . ADMIN_URL . '/activity-logs/index.php');
    exit;
}

$logs = $pdo->query("SELECT al.*, a.name as admin_name, a.username as admin_username FROM activity_logs al LEFT JOIN admins a ON al.admin_id = a.id ORDER BY al.id DESC LIMIT 100")->fetchAll();

$adminTitle = 'System Activity Logs';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">System Activity Logs & Security Audit</h4>
                <p class="text-muted small mb-0">Track administrative logins, updates, deletions, and public form submissions.</p>
            </div>
            <a href="<?= ADMIN_URL; ?>/activity-logs/index.php?cleanup=1" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-broom me-1"></i> Clean Old Logs (>90 Days)
            </a>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="card-admin">
            <div class="table-responsive">
                <table class="table table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Admin / User</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No activity logs recorded yet</td></tr>
                        <?php else: foreach ($logs as $l): ?>
                        <tr>
                            <td><strong class="text-dark"><?= e($l['action']); ?></strong></td>
                            <td><span class="badge bg-light text-primary border"><?= strtoupper(e($l['module'])); ?></span></td>
                            <td><?= e($l['admin_name'] ?: ($l['admin_username'] ?: 'Public Form / System')); ?></td>
                            <td><small class="text-muted"><?= e($l['description'] ?: '-'); ?></small></td>
                            <td><code><?= e($l['ip_address'] ?? '127.0.0.1'); ?></code></td>
                            <td><small class="text-muted"><?= formatDateTime($l['created_at']); ?></small></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

<?php
/**
 * Volunteer Applications Management & Status Workflow
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $data = $pdo->query("SELECT volunteer_code, name, email, phone, gender, city, state, area_of_interest, available_days, status, created_at FROM volunteers ORDER BY id DESC")->fetchAll();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=volunteers_export_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Volunteer Code', 'Name', 'Email', 'Phone', 'Gender', 'City', 'State', 'Interests', 'Availability', 'Status', 'Registered Date']);
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM volunteers WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Volunteer', 'volunteers', $delId);
    setFlashMessage('success', 'Volunteer record deleted.');
    header('Location: ' . ADMIN_URL . '/volunteers/index.php');
    exit;
}

// Update Status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    requireCsrfToken();
    $volId = (int)$_POST['vol_id'];
    $status = sanitize($_POST['status'] ?? 'pending');
    $notes = sanitize($_POST['admin_notes'] ?? '');

    $stmt = $pdo->prepare("UPDATE volunteers SET status = ?, admin_notes = ? WHERE id = ?");
    $stmt->execute([$status, $notes, $volId]);
    logActivity('Updated Volunteer Status', 'volunteers', $volId, "Status changed to {$status}");
    setFlashMessage('success', 'Volunteer status updated.');
    header('Location: ' . ADMIN_URL . '/volunteers/index.php');
    exit;
}

$volunteers = $pdo->query("SELECT * FROM volunteers ORDER BY id DESC")->fetchAll();

$adminTitle = 'Volunteer Network Management';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Volunteers & Grassroots Changemakers</h4>
                <p class="text-muted small mb-0">Manage registered volunteers, review resumes, assign camp tasks, and track onboarding status.</p>
            </div>
            <a href="<?= ADMIN_URL; ?>/volunteers/index.php?export=csv" class="btn btn-sm btn-outline-success">
                <i class="fas fa-file-excel me-1"></i> Export Volunteers (CSV)
            </a>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="card-admin">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>All Volunteers (<?= count($volunteers); ?>)</span>
                <input type="text" id="tableSearchInput" class="form-control form-control-sm w-auto" placeholder="Search volunteer...">
            </div>
            <div class="table-responsive">
                <table class="table table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Volunteer</th>
                            <th>Location</th>
                            <th>Interests</th>
                            <th>Availability</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($volunteers)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No volunteer registrations found</td></tr>
                        <?php else: foreach ($volunteers as $v): ?>
                        <tr>
                            <td><span class="badge bg-dark font-monospace"><?= e($v['volunteer_code']); ?></span></td>
                            <td>
                                <strong class="text-dark d-block"><?= e($v['name']); ?></strong>
                                <small class="text-muted"><?= e($v['phone']); ?> • <?= e($v['email']); ?></small>
                            </td>
                            <td><?= e($v['city']); ?>, <?= e($v['state']); ?></td>
                            <td><span class="badge bg-light text-primary border"><?= e(truncateText($v['area_of_interest'], 30)); ?></span></td>
                            <td><?= e($v['available_days']); ?></td>
                            <td>
                                <span class="badge <?= match($v['status']) {
                                    'approved' => 'bg-success-subtle text-success',
                                    'contacted' => 'bg-info-subtle text-info',
                                    'rejected' => 'bg-danger-subtle text-danger',
                                    default => 'bg-warning-subtle text-warning'
                                }; ?>">
                                    <?= strtoupper($v['status']); ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#volModal<?= $v['id']; ?>"><i class="fas fa-user-edit"></i></button>
                                <?php if (!empty($v['resume_file'])): ?>
                                <a href="<?= e(getImageUrl($v['resume_file'])); ?>" target="_blank" class="btn btn-sm btn-outline-danger" title="Resume"><i class="fas fa-file-pdf"></i></a>
                                <?php endif; ?>
                                <a href="<?= ADMIN_URL; ?>/volunteers/index.php?delete=<?= $v['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>

                        <!-- Volunteer Review Modal -->
                        <div class="modal fade" id="volModal<?= $v['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Volunteer: <?= e($v['name']); ?> (<?= e($v['volunteer_code']); ?>)</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= ADMIN_URL; ?>/volunteers/index.php" method="POST">
                                        <?= getCsrfInput(); ?>
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="vol_id" value="<?= $v['id']; ?>">

                                        <div class="modal-body">
                                            <div class="p-3 bg-light rounded-3 mb-3 small">
                                                <strong>Occupation / Qualification:</strong> <?= e($v['occupation'] ?: 'Not specified'); ?> (<?= e($v['qualification']); ?>)<br>
                                                <strong>Motivation / Background:</strong>
                                                <p class="text-muted mb-0"><?= nl2br(e($v['message'] ?: 'None')); ?></p>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold">Volunteer Workflow Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="pending" <?= $v['status'] === 'pending' ? 'selected' : ''; ?>>Pending Review</option>
                                                        <option value="contacted" <?= $v['status'] === 'contacted' ? 'selected' : ''; ?>>Contacted / Interviewed</option>
                                                        <option value="approved" <?= $v['status'] === 'approved' ? 'selected' : ''; ?>>Approved Changemaker</option>
                                                        <option value="rejected" <?= $v['status'] === 'rejected' ? 'selected' : ''; ?>>Inactive / Declined</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label small fw-bold">Internal Coordinator Notes</label>
                                                    <textarea name="admin_notes" rows="3" class="form-control"><?= e($v['admin_notes'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update Volunteer Status</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

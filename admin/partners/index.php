<?php
/**
 * Partnership Applications Review & Status Workflow
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $data = $pdo->query("SELECT id, organization_name, contact_person, partner_type, phone, email, city, state, status, created_at FROM partnership_enquiries ORDER BY id DESC")->fetchAll();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=partnerships_export_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Organization', 'Contact Person', 'Type', 'Phone', 'Email', 'City', 'State', 'Status', 'Submitted Date']);
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM partnership_enquiries WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Partnership Inquiry', 'partnership_enquiries', $delId);
    setFlashMessage('success', 'Partnership inquiry deleted.');
    header('Location: ' . ADMIN_URL . '/partners/index.php');
    exit;
}

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    requireCsrfToken();
    $id = (int)$_POST['partner_id'];
    $status = sanitize($_POST['status'] ?? 'pending');
    $notes = sanitize($_POST['admin_notes'] ?? '');

    $stmt = $pdo->prepare("UPDATE partnership_enquiries SET status = ?, admin_notes = ? WHERE id = ?");
    $stmt->execute([$status, $notes, $id]);
    logActivity('Updated Partnership Status', 'partnership_enquiries', $id, "Status changed to {$status}");
    setFlashMessage('success', 'Partnership application status updated.');
    header('Location: ' . ADMIN_URL . '/partners/index.php');
    exit;
}

$partners = $pdo->query("SELECT * FROM partnership_enquiries ORDER BY id DESC")->fetchAll();

$adminTitle = 'Partnership Proposals & CSR';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Partnership & CSR Proposals</h4>
                <p class="text-muted small mb-0">Review incoming collaboration requests from schools, hospitals, corporate donors, and doctors.</p>
            </div>
            <a href="<?= ADMIN_URL; ?>/partners/index.php?export=csv" class="btn btn-sm btn-outline-success">
                <i class="fas fa-file-excel me-1"></i> Export to CSV
            </a>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="card-admin">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>All Applications (<?= count($partners); ?>)</span>
                <input type="text" id="tableSearchInput" class="form-control form-control-sm w-auto" placeholder="Search proposals...">
            </div>
            <div class="table-responsive">
                <table class="table table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Organization</th>
                            <th>Contact Person</th>
                            <th>Track</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($partners)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No partnership applications received yet</td></tr>
                        <?php else: foreach ($partners as $p): ?>
                        <tr>
                            <td>
                                <strong class="text-dark d-block"><?= e($p['organization_name']); ?></strong>
                                <?php if (!empty($p['proposal_document'])): ?>
                                <a href="<?= e(getImageUrl($p['proposal_document'])); ?>" target="_blank" class="badge bg-light text-danger border"><i class="fas fa-paperclip me-1"></i> Attached Doc</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= e($p['contact_person']); ?><br>
                                <small class="text-muted"><?= e($p['phone']); ?> | <?= e($p['email']); ?></small>
                            </td>
                            <td><span class="badge bg-light text-primary border"><?= strtoupper(str_replace('_', ' ', $p['partner_type'])); ?></span></td>
                            <td><?= e($p['city']); ?>, <?= e($p['state']); ?></td>
                            <td>
                                <span class="badge <?= match($p['status']) {
                                    'approved' => 'bg-success-subtle text-success',
                                    'rejected' => 'bg-danger-subtle text-danger',
                                    'under_review' => 'bg-info-subtle text-info',
                                    default => 'bg-warning-subtle text-warning'
                                }; ?>">
                                    <?= strtoupper(str_replace('_', ' ', $p['status'])); ?>
                                </span>
                            </td>
                            <td><small class="text-muted"><?= formatDate($p['created_at']); ?></small></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?= $p['id']; ?>"><i class="fas fa-eye"></i></button>
                                <a href="<?= ADMIN_URL; ?>/partners/index.php?delete=<?= $p['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>

                        <!-- Details & Workflow Modal -->
                        <div class="modal fade" id="viewModal<?= $p['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Proposal: <?= e($p['organization_name']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= ADMIN_URL; ?>/partners/index.php" method="POST">
                                        <?= getCsrfInput(); ?>
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="partner_id" value="<?= $p['id']; ?>">

                                        <div class="modal-body">
                                            <div class="p-3 bg-light rounded-3 mb-3">
                                                <strong>Proposed Collaboration Scope:</strong>
                                                <p class="text-muted mb-0"><?= nl2br(e($p['partnership_interest'])); ?></p>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold">Workflow Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="pending" <?= $p['status'] === 'pending' ? 'selected' : ''; ?>>Pending Review</option>
                                                        <option value="under_review" <?= $p['status'] === 'under_review' ? 'selected' : ''; ?>>Under Review</option>
                                                        <option value="approved" <?= $p['status'] === 'approved' ? 'selected' : ''; ?>>Approved / MOU Initiated</option>
                                                        <option value="rejected" <?= $p['status'] === 'rejected' ? 'selected' : ''; ?>>Declined / Closed</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label small fw-bold">Internal NGO Notes</label>
                                                    <textarea name="admin_notes" rows="3" class="form-control"><?= e($p['admin_notes'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update Status & Notes</button>
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

<?php
/**
 * Contact Enquiries & Helpdesk Inbox
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $data = $pdo->query("SELECT id, name, email, phone, subject, message, status, created_at FROM contact_enquiries ORDER BY id DESC")->fetchAll();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=contact_enquiries_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Status', 'Date']);
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM contact_enquiries WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Contact Enquiry', 'contact_enquiries', $delId);
    setFlashMessage('success', 'Message deleted.');
    header('Location: ' . ADMIN_URL . '/enquiries/index.php');
    exit;
}

// Mark Status / Reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_enquiry') {
    requireCsrfToken();
    $id = (int)$_POST['enquiry_id'];
    $status = sanitize($_POST['status'] ?? 'read');
    $reply = sanitize($_POST['admin_reply'] ?? '');

    $stmt = $pdo->prepare("UPDATE contact_enquiries SET status = ?, admin_reply = ? WHERE id = ?");
    $stmt->execute([$status, $reply, $id]);
    logActivity('Updated Contact Status', 'contact_enquiries', $id);
    setFlashMessage('success', 'Message status updated.');
    header('Location: ' . ADMIN_URL . '/enquiries/index.php');
    exit;
}

$enquiries = $pdo->query("SELECT * FROM contact_enquiries ORDER BY id DESC")->fetchAll();

$adminTitle = 'Contact Messages & Enquiries';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Contact Messages & Public Inquiries</h4>
                <p class="text-muted small mb-0">Helpdesk inbox for questions regarding healthcare camps, school programs, and doctor appointments.</p>
            </div>
            <a href="<?= ADMIN_URL; ?>/enquiries/index.php?export=csv" class="btn btn-sm btn-outline-success">
                <i class="fas fa-file-excel me-1"></i> Export Messages (CSV)
            </a>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="card-admin">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Inbox Messages (<?= count($enquiries); ?>)</span>
                <input type="text" id="tableSearchInput" class="form-control form-control-sm w-auto" placeholder="Search message...">
            </div>
            <div class="table-responsive">
                <table class="table table-admin mb-0">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($enquiries)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Inbox is empty</td></tr>
                        <?php else: foreach ($enquiries as $e): ?>
                        <tr>
                            <td>
                                <strong class="text-dark d-block"><?= e($e['name']); ?></strong>
                                <small class="text-muted"><?= e($e['email']); ?> • <?= e($e['phone']); ?></small>
                            </td>
                            <td><span class="badge bg-light text-primary border"><?= e($e['subject']); ?></span></td>
                            <td><small class="text-muted"><?= e(truncateText($e['message'], 60)); ?></small></td>
                            <td>
                                <span class="badge <?= match($e['status']) {
                                    'read' => 'bg-info-subtle text-info',
                                    'replied' => 'bg-success-subtle text-success',
                                    default => 'bg-danger-subtle text-danger'
                                }; ?>">
                                    <?= strtoupper($e['status']); ?>
                                </span>
                            </td>
                            <td><small class="text-muted"><?= formatDate($e['created_at']); ?></small></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#msgModal<?= $e['id']; ?>"><i class="fas fa-envelope-open"></i></button>
                                <a href="<?= ADMIN_URL; ?>/enquiries/index.php?delete=<?= $e['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>

                        <!-- Message View Modal -->
                        <div class="modal fade" id="msgModal<?= $e['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Message from <?= e($e['name']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?= ADMIN_URL; ?>/enquiries/index.php" method="POST">
                                        <?= getCsrfInput(); ?>
                                        <input type="hidden" name="action" value="update_enquiry">
                                        <input type="hidden" name="enquiry_id" value="<?= $e['id']; ?>">

                                        <div class="modal-body">
                                            <div class="p-3 bg-light rounded-3 mb-3">
                                                <strong>Subject:</strong> <?= e($e['subject']); ?><br>
                                                <strong>Contact:</strong> <?= e($e['phone']); ?> | <?= e($e['email']); ?><br><br>
                                                <strong>Message:</strong>
                                                <p class="text-muted mb-0"><?= nl2br(e($e['message'])); ?></p>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="unread" <?= $e['status'] === 'unread' ? 'selected' : ''; ?>>Unread</option>
                                                        <option value="read" <?= $e['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                                                        <option value="replied" <?= $e['status'] === 'replied' ? 'selected' : ''; ?>>Replied / Resolved</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label small fw-bold">Reply Notes / Action Taken</label>
                                                    <textarea name="admin_reply" rows="3" class="form-control" placeholder="Record email or phone response details"><?= e($e['admin_reply'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Status</button>
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

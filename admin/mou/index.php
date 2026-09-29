<?php
/**
 * MOU Document Management CRUD
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM mous WHERE id = ?")->execute([$delId]);
    logActivity('Deleted MOU Document', 'mous', $delId);
    setFlashMessage('success', 'MOU record deleted.');
    header('Location: ' . ADMIN_URL . '/mou/index.php');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $mouId = (int)($_POST['mou_id'] ?? 0);
    $title = sanitize($_POST['title'] ?? '');
    $partnerName = sanitize($_POST['partner_name'] ?? '');
    $partnerType = sanitize($_POST['partner_type'] ?? 'school');
    $signedDate = sanitize($_POST['signed_date'] ?? date('Y-m-d'));
    $validUpto = !empty($_POST['valid_upto']) ? sanitize($_POST['valid_upto']) : null;
    $objectives = sanitize($_POST['key_objectives'] ?? '');
    $scope = sanitize($_POST['scope_of_work'] ?? '');
    $isPublic = isset($_POST['is_public']) ? 1 : 0;
    $status = sanitize($_POST['status'] ?? 'active');

    $docPath = null;
    if (!empty($_FILES['document_file']['name'])) {
        $upload = uploadFile($_FILES['document_file'], 'documents', 'document');
        if ($upload['success']) $docPath = $upload['filename'];
    }

    if ($mouId > 0) {
        if ($docPath) {
            $stmt = $pdo->prepare("UPDATE mous SET title = ?, partner_name = ?, partner_type = ?, signed_date = ?, valid_upto = ?, key_objectives = ?, scope_of_work = ?, is_public = ?, status = ?, document_file = ? WHERE id = ?");
            $stmt->execute([$title, $partnerName, $partnerType, $signedDate, $validUpto, $objectives, $scope, $isPublic, $status, $docPath, $mouId]);
        } else {
            $stmt = $pdo->prepare("UPDATE mous SET title = ?, partner_name = ?, partner_type = ?, signed_date = ?, valid_upto = ?, key_objectives = ?, scope_of_work = ?, is_public = ?, status = ? WHERE id = ?");
            $stmt->execute([$title, $partnerName, $partnerType, $signedDate, $validUpto, $objectives, $scope, $isPublic, $status, $mouId]);
        }
        logActivity('Updated MOU Document', 'mous', $mouId);
        setFlashMessage('success', 'MOU record updated.');
    } else {
        if (!$docPath) {
            setFlashMessage('danger', 'Please upload an MOU document PDF.');
        } else {
            $stmt = $pdo->prepare("INSERT INTO mous (title, partner_name, partner_type, signed_date, valid_upto, key_objectives, scope_of_work, is_public, status, document_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $partnerName, $partnerType, $signedDate, $validUpto, $objectives, $scope, $isPublic, $status, $docPath]);
            logActivity('Created MOU Document', 'mous');
            setFlashMessage('success', 'New MOU uploaded.');
        }
    }

    header('Location: ' . ADMIN_URL . '/mou/index.php');
    exit;
}

$mous = $pdo->query("SELECT * FROM mous ORDER BY id DESC")->fetchAll();
$editMou = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM mous WHERE id = ?");
    $st->execute([$editId]);
    $editMou = $st->fetch();
}

$adminTitle = 'MOU Document Management';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Memorandums of Understanding (MOU)</h4>
                <p class="text-muted small mb-0">Manage legal covenants with partner schools, hospitals, and CSR entities.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editMou ? 'Edit MOU #' . $editMou['id'] : 'Upload New MOU Document'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/mou/index.php" method="POST" enctype="multipart/form-data" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="mou_id" value="<?= (int)($editMou['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">MOU Document Title *</label>
                                <input type="text" name="title" class="form-control" required value="<?= e($editMou['title'] ?? ''); ?>" placeholder="e.g. Student Health Care & Yoga MOU">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Partner Name *</label>
                                    <input type="text" name="partner_name" class="form-control" required value="<?= e($editMou['partner_name'] ?? ''); ?>" placeholder="School or Hospital Name">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Partner Track</label>
                                    <select name="partner_type" class="form-select">
                                        <option value="school" <?= ($editMou['partner_type'] ?? '') === 'school' ? 'selected' : ''; ?>>School</option>
                                        <option value="hospital" <?= ($editMou['partner_type'] ?? '') === 'hospital' ? 'selected' : ''; ?>>Hospital</option>
                                        <option value="csr" <?= ($editMou['partner_type'] ?? '') === 'csr' ? 'selected' : ''; ?>>Corporate CSR</option>
                                        <option value="ngo" <?= ($editMou['partner_type'] ?? '') === 'ngo' ? 'selected' : ''; ?>>NGO / Society</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Signed Date *</label>
                                    <input type="date" name="signed_date" class="form-control" required value="<?= e($editMou['signed_date'] ?? date('Y-m-d')); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Valid Upto</label>
                                    <input type="date" name="valid_upto" class="form-control" value="<?= e($editMou['valid_upto'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Key Objectives</label>
                                <textarea name="key_objectives" rows="2" class="form-control"><?= e($editMou['key_objectives'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Scope of Work</label>
                                <textarea name="scope_of_work" rows="2" class="form-control"><?= e($editMou['scope_of_work'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">MOU PDF File <?= $editMou ? '(Optional if keeping current)' : '*'; ?></label>
                                <input type="file" name="document_file" class="form-control" accept=".pdf">
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_public" id="isPub" <?= !isset($editMou['is_public']) || $editMou['is_public'] == 1 ? 'checked' : ''; ?>>
                                <label class="form-check-label small" for="isPub">Show in Public MOU Archive</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editMou ? 'Update MOU' : 'Save MOU'; ?></button>
                                <?php if ($editMou): ?>
                                <a href="<?= ADMIN_URL; ?>/mou/index.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">All Uploaded MOUs (<?= count($mous); ?>)</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead>
                                <tr>
                                    <th>MOU / Partner</th>
                                    <th>Signed Date</th>
                                    <th>Public</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mous as $m): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block text-dark"><?= e($m['title']); ?></strong>
                                        <small class="text-muted"><?= e($m['partner_name']); ?> (<?= strtoupper($m['partner_type']); ?>)</small>
                                    </td>
                                    <td><?= formatDate($m['signed_date']); ?></td>
                                    <td><?= $m['is_public'] ? '<span class="badge bg-success-subtle text-success">Public</span>' : '<span class="badge bg-secondary-subtle text-secondary">Private</span>'; ?></td>
                                    <td>
                                        <a href="<?= e(getImageUrl($m['document_file'])); ?>" target="_blank" class="btn btn-sm btn-outline-danger me-1"><i class="fas fa-file-pdf"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/mou/index.php?edit=<?= $m['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/mou/index.php?delete=<?= $m['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

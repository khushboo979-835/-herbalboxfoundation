<?php
/**
 * Impact Statistics Counters CRUD Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM impact_statistics WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Impact Stat', 'impact_statistics', $delId);
    setFlashMessage('success', 'Impact statistic counter deleted.');
    header('Location: ' . ADMIN_URL . '/impact/index.php');
    exit;
}

// Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $statId = (int)($_POST['stat_id'] ?? 0);
    $title = sanitize($_POST['title'] ?? '');
    $countNumber = sanitize($_POST['count_number'] ?? '0');
    $suffix = sanitize($_POST['suffix'] ?? '+');
    $icon = sanitize($_POST['icon'] ?? 'fa-heartbeat');
    $desc = sanitize($_POST['description'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 1);
    $status = sanitize($_POST['status'] ?? 'active');

    if ($statId > 0) {
        $stmt = $pdo->prepare("UPDATE impact_statistics SET title = ?, count_number = ?, suffix = ?, icon = ?, description = ?, sort_order = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $countNumber, $suffix, $icon, $desc, $sortOrder, $status, $statId]);
        logActivity('Updated Impact Stat', 'impact_statistics', $statId);
        setFlashMessage('success', 'Impact statistic counter updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO impact_statistics (title, count_number, suffix, icon, description, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $countNumber, $suffix, $icon, $desc, $sortOrder, $status]);
        logActivity('Created Impact Stat', 'impact_statistics');
        setFlashMessage('success', 'New impact counter created.');
    }
    header('Location: ' . ADMIN_URL . '/impact/index.php');
    exit;
}

$stats = $pdo->query("SELECT * FROM impact_statistics ORDER BY sort_order ASC, id ASC")->fetchAll();
$editStat = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM impact_statistics WHERE id = ?");
    $st->execute([$editId]);
    $editStat = $st->fetch();
}

$adminTitle = 'Impact Statistics Counters';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Manage Dynamic Impact Statistics</h4>
                <p class="text-muted small mb-0">Update lives touched, camps conducted, doctor count, and units of blood collected.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editStat ? 'Edit Counter #' . $editStat['id'] : 'Add New Impact Counter'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/impact/index.php" method="POST" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="stat_id" value="<?= (int)($editStat['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Counter Title / Metric *</label>
                                <input type="text" name="title" class="form-control" required value="<?= e($editStat['title'] ?? ''); ?>" placeholder="e.g. Lives Touched & Treated">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-8">
                                    <label class="form-label small fw-bold">Numeric Count *</label>
                                    <input type="text" name="count_number" class="form-control" required value="<?= e($editStat['count_number'] ?? ''); ?>" placeholder="e.g. 125000">
                                </div>
                                <div class="col-4">
                                    <label class="form-label small fw-bold">Suffix</label>
                                    <input type="text" name="suffix" class="form-control" value="<?= e($editStat['suffix'] ?? '+'); ?>" placeholder="+ or %">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Font Awesome Icon Class</label>
                                <input type="text" name="icon" class="form-control" value="<?= e($editStat['icon'] ?? 'fa-heartbeat'); ?>" placeholder="e.g. fa-users, fa-school">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Short Explanation / Tooltip</label>
                                <input type="text" name="description" class="form-control" value="<?= e($editStat['description'] ?? ''); ?>">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="<?= (int)($editStat['sort_order'] ?? 1); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" <?= ($editStat['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?= ($editStat['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editStat ? 'Update Counter' : 'Save Counter'; ?></button>
                                <?php if ($editStat): ?>
                                <a href="<?= ADMIN_URL; ?>/impact/index.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">Existing Counters</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead>
                                <tr>
                                    <th>Sort</th>
                                    <th>Metric</th>
                                    <th>Number</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats as $s): ?>
                                <tr>
                                    <td><span class="badge bg-light text-dark border"><?= (int)$s['sort_order']; ?></span></td>
                                    <td>
                                        <strong class="d-block"><i class="fas <?= e($s['icon']); ?> text-primary me-1"></i> <?= e($s['title']); ?></strong>
                                        <small class="text-muted"><?= e($s['description']); ?></small>
                                    </td>
                                    <td><strong class="text-success"><?= e($s['count_number']) . e($s['suffix']); ?></strong></td>
                                    <td><span class="badge bg-success-subtle text-success"><?= strtoupper($s['status']); ?></span></td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/impact/index.php?edit=<?= $s['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/impact/index.php?delete=<?= $s['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
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

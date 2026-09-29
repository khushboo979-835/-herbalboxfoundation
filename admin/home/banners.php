<?php
/**
 * Home Hero Banners CRUD Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Handle Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM home_banners WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Home Banner', 'home_banners', $delId);
    setFlashMessage('success', 'Banner removed successfully.');
    header('Location: ' . ADMIN_URL . '/home/banners.php');
    exit;
}

// Handle Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $bannerId = (int)($_POST['banner_id'] ?? 0);
    $title = sanitize($_POST['title'] ?? '');
    $subtitle = sanitize($_POST['subtitle'] ?? '');
    $badge = sanitize($_POST['badge_text'] ?? '');
    $primaryText = sanitize($_POST['primary_btn_text'] ?? 'Donate Now');
    $primaryLink = sanitize($_POST['primary_btn_link'] ?? 'donate.php');
    $sortOrder = (int)($_POST['sort_order'] ?? 1);
    $status = sanitize($_POST['status'] ?? 'active');

    if ($bannerId > 0) {
        $stmt = $pdo->prepare("UPDATE home_banners SET title = ?, subtitle = ?, badge_text = ?, primary_btn_text = ?, primary_btn_link = ?, sort_order = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $subtitle, $badge, $primaryText, $primaryLink, $sortOrder, $status, $bannerId]);
        logActivity('Updated Home Banner', 'home_banners', $bannerId);
        setFlashMessage('success', 'Banner updated successfully.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO home_banners (title, subtitle, badge_text, primary_btn_text, primary_btn_link, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $subtitle, $badge, $primaryText, $primaryLink, $sortOrder, $status]);
        logActivity('Created Home Banner', 'home_banners');
        setFlashMessage('success', 'New banner created successfully.');
    }
    header('Location: ' . ADMIN_URL . '/home/banners.php');
    exit;
}

$banners = $pdo->query("SELECT * FROM home_banners ORDER BY sort_order ASC, id ASC")->fetchAll();
$editBanner = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM home_banners WHERE id = ?");
    $st->execute([$editId]);
    $editBanner = $st->fetch();
}

$adminTitle = 'Home Hero Banners';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Manage Homepage Hero Banners</h4>
                <p class="text-muted small mb-0">Add, edit, or reorder headline sliders and call-to-action buttons on the homepage.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <!-- Form -->
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editBanner ? 'Edit Banner #' . $editBanner['id'] : 'Create New Hero Banner'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/home/banners.php" method="POST" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="banner_id" value="<?= (int)($editBanner['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Badge Text (Top Highlight)</label>
                                <input type="text" name="badge_text" class="form-control" value="<?= e($editBanner['badge_text'] ?? 'Registered National NGO'); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Hero Headline Title *</label>
                                <textarea name="title" rows="2" class="form-control" required><?= e($editBanner['title'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Subheading Description</label>
                                <textarea name="subtitle" rows="3" class="form-control"><?= e($editBanner['subtitle'] ?? ''); ?></textarea>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Button Text</label>
                                    <input type="text" name="primary_btn_text" class="form-control" value="<?= e($editBanner['primary_btn_text'] ?? 'Donate Now'); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Button Link URL</label>
                                    <input type="text" name="primary_btn_link" class="form-control" value="<?= e($editBanner['primary_btn_link'] ?? 'donate.php'); ?>">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="<?= (int)($editBanner['sort_order'] ?? 1); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" <?= ($editBanner['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?= ($editBanner['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editBanner ? 'Update Banner' : 'Create Banner'; ?></button>
                                <?php if ($editBanner): ?>
                                <a href="<?= ADMIN_URL; ?>/home/banners.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">Active Banners List</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead>
                                <tr>
                                    <th>Sort</th>
                                    <th>Headline</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $b): ?>
                                <tr>
                                    <td><span class="badge bg-light text-dark border"><?= (int)$b['sort_order']; ?></span></td>
                                    <td>
                                        <strong class="d-block"><?= e($b['title']); ?></strong>
                                        <small class="text-muted"><?= e($b['badge_text']); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge <?= $b['status'] === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'; ?>">
                                            <?= strtoupper($b['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/home/banners.php?edit=<?= $b['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/home/banners.php?delete=<?= $b['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
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

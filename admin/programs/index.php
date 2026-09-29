<?php
/**
 * Programs & Verticals CRUD Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM programs WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Program', 'programs', $delId);
    setFlashMessage('success', 'Program deleted successfully.');
    header('Location: ' . ADMIN_URL . '/programs/index.php');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $progId = (int)($_POST['prog_id'] ?? 0);
    $title = sanitize($_POST['title'] ?? '');
    $slug = slugify($_POST['slug'] ?: $title);
    $type = sanitize($_POST['type'] ?? 'healthcare');
    $shortDesc = sanitize($_POST['short_description'] ?? '');
    $fullContent = sanitize($_POST['full_content'] ?? '');
    $features = sanitize($_POST['features'] ?? '');
    $icon = sanitize($_POST['icon'] ?? 'fa-heartbeat');
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $sortOrder = (int)($_POST['sort_order'] ?? 1);
    $status = sanitize($_POST['status'] ?? 'active');

    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $upload = uploadFile($_FILES['image'], 'programs', 'image');
        if ($upload['success']) $imagePath = $upload['filename'];
    }

    if ($progId > 0) {
        if ($imagePath) {
            $stmt = $pdo->prepare("UPDATE programs SET title = ?, slug = ?, type = ?, short_description = ?, full_content = ?, features = ?, icon = ?, is_featured = ?, sort_order = ?, status = ?, image = ? WHERE id = ?");
            $stmt->execute([$title, $slug, $type, $shortDesc, $fullContent, $features, $icon, $isFeatured, $sortOrder, $status, $imagePath, $progId]);
        } else {
            $stmt = $pdo->prepare("UPDATE programs SET title = ?, slug = ?, type = ?, short_description = ?, full_content = ?, features = ?, icon = ?, is_featured = ?, sort_order = ?, status = ? WHERE id = ?");
            $stmt->execute([$title, $slug, $type, $shortDesc, $fullContent, $features, $icon, $isFeatured, $sortOrder, $status, $progId]);
        }
        logActivity('Updated Program', 'programs', $progId);
        setFlashMessage('success', 'Program updated successfully.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO programs (title, slug, type, short_description, full_content, features, icon, is_featured, sort_order, status, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $type, $shortDesc, $fullContent, $features, $icon, $isFeatured, $sortOrder, $status, $imagePath]);
        logActivity('Created Program', 'programs');
        setFlashMessage('success', 'New program created successfully.');
    }

    header('Location: ' . ADMIN_URL . '/programs/index.php');
    exit;
}

$programs = $pdo->query("SELECT * FROM programs ORDER BY type ASC, sort_order ASC, id ASC")->fetchAll();
$editProg = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM programs WHERE id = ?");
    $st->execute([$editId]);
    $editProg = $st->fetch();
}

$adminTitle = 'Manage Programs & Initiatives';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Programs & Social Verticals</h4>
                <p class="text-muted small mb-0">Control healthcare camps, NCERT school education, AYUSH treatments, and daily yoga initiatives.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editProg ? 'Edit Program #' . $editProg['id'] : 'Add New Social Program'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/programs/index.php" method="POST" enctype="multipart/form-data" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="prog_id" value="<?= (int)($editProg['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Program Title *</label>
                                <input type="text" id="titleInput" name="title" class="form-control" required value="<?= e($editProg['title'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">URL Slug</label>
                                <input type="text" id="slugInput" name="slug" class="form-control" value="<?= e($editProg['slug'] ?? ''); ?>">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Vertical Type</label>
                                    <select name="type" class="form-select">
                                        <option value="healthcare" <?= ($editProg['type'] ?? '') === 'healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                                        <option value="education" <?= ($editProg['type'] ?? '') === 'education' ? 'selected' : ''; ?>>Education (1st - 12th)</option>
                                        <option value="ayush" <?= ($editProg['type'] ?? '') === 'ayush' ? 'selected' : ''; ?>>AYUSH Medical Care</option>
                                        <option value="yoga" <?= ($editProg['type'] ?? '') === 'yoga' ? 'selected' : ''; ?>>Yoga & Meditation</option>
                                        <option value="welfare" <?= ($editProg['type'] ?? '') === 'welfare' ? 'selected' : ''; ?>>Community Welfare</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Icon Class</label>
                                    <input type="text" name="icon" class="form-control" value="<?= e($editProg['icon'] ?? 'fa-stethoscope'); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Short Summary *</label>
                                <textarea name="short_description" rows="2" class="form-control" required><?= e($editProg['short_description'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Detailed Description</label>
                                <textarea name="full_content" rows="4" class="form-control"><?= e($editProg['full_content'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Key Features (One per line)</label>
                                <textarea name="features" rows="3" class="form-control"><?= e($editProg['features'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Cover Photo Upload</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="<?= (int)($editProg['sort_order'] ?? 1); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" <?= ($editProg['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?= ($editProg['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="feat" <?= !empty($editProg['is_featured']) ? 'checked' : ''; ?>>
                                <label class="form-check-label small" for="feat">Show on Homepage Featured Grid</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editProg ? 'Update Program' : 'Save Program'; ?></button>
                                <?php if ($editProg): ?>
                                <a href="<?= ADMIN_URL; ?>/programs/index.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">All Active Programs</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead>
                                <tr>
                                    <th>Vertical</th>
                                    <th>Program Title</th>
                                    <th>Home</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($programs as $p): ?>
                                <tr>
                                    <td><span class="badge bg-light text-primary border"><?= strtoupper($p['type']); ?></span></td>
                                    <td>
                                        <strong class="d-block"><i class="fas <?= e($p['icon']); ?> text-muted me-1"></i> <?= e($p['title']); ?></strong>
                                        <small class="text-muted"><?= e(truncateText($p['short_description'], 60)); ?></small>
                                    </td>
                                    <td><?= !empty($p['is_featured']) ? '<i class="fas fa-check text-success"></i>' : '-'; ?></td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/programs/index.php?edit=<?= $p['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/programs/index.php?delete=<?= $p['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
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

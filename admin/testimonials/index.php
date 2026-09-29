<?php
/**
 * Testimonials & Impact Stories CRUD Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM testimonials WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Testimonial', 'testimonials', $delId);
    setFlashMessage('success', 'Testimonial deleted.');
    header('Location: ' . ADMIN_URL . '/testimonials/index.php');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $testId = (int)($_POST['test_id'] ?? 0);
    $name = sanitize($_POST['name'] ?? '');
    $designation = sanitize($_POST['designation'] ?? '');
    $org = sanitize($_POST['organization'] ?? '');
    $content = sanitize($_POST['content'] ?? '');
    $rating = (int)($_POST['rating'] ?? 5);
    $category = sanitize($_POST['category'] ?? 'patient');
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $status = sanitize($_POST['status'] ?? 'active');

    if ($testId > 0) {
        $stmt = $pdo->prepare("UPDATE testimonials SET name = ?, designation = ?, organization = ?, content = ?, rating = ?, category = ?, is_featured = ?, status = ? WHERE id = ?");
        $stmt->execute([$name, $designation, $org, $content, $rating, $category, $isFeatured, $status, $testId]);
        logActivity('Updated Testimonial', 'testimonials', $testId);
        setFlashMessage('success', 'Testimonial updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO testimonials (name, designation, organization, content, rating, category, is_featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $designation, $org, $content, $rating, $category, $isFeatured, $status]);
        logActivity('Created Testimonial', 'testimonials');
        setFlashMessage('success', 'New testimonial added.');
    }

    header('Location: ' . ADMIN_URL . '/testimonials/index.php');
    exit;
}

$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll();
$editTest = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
    $st->execute([$editId]);
    $editTest = $st->fetch();
}

$adminTitle = 'Testimonials & Beneficiary Stories';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Beneficiary & Partner Testimonials</h4>
                <p class="text-muted small mb-0">Manage feedback from camp patients, school principals, partner doctors, and donors.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editTest ? 'Edit Testimonial #' . $editTest['id'] : 'Add Testimonial'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/testimonials/index.php" method="POST" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="test_id" value="<?= (int)($editTest['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Person Name *</label>
                                <input type="text" name="name" class="form-control" required value="<?= e($editTest['name'] ?? ''); ?>" placeholder="e.g. Ramesh Kumar">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Designation</label>
                                    <input type="text" name="designation" class="form-control" value="<?= e($editTest['designation'] ?? 'Beneficiary'); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Organization / City</label>
                                    <input type="text" name="organization" class="form-control" value="<?= e($editTest['organization'] ?? 'New Delhi'); ?>">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="patient" <?= ($editTest['category'] ?? '') === 'patient' ? 'selected' : ''; ?>>Patient / Beneficiary</option>
                                        <option value="student" <?= ($editTest['category'] ?? '') === 'student' ? 'selected' : ''; ?>>Student</option>
                                        <option value="doctor" <?= ($editTest['category'] ?? '') === 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                                        <option value="partner" <?= ($editTest['category'] ?? '') === 'partner' ? 'selected' : ''; ?>>Partner School / Hospital</option>
                                        <option value="donor" <?= ($editTest['category'] ?? '') === 'donor' ? 'selected' : ''; ?>>Donor</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Star Rating (1-5)</label>
                                    <select name="rating" class="form-select">
                                        <option value="5" <?= ($editTest['rating'] ?? 5) == 5 ? 'selected' : ''; ?>>5 Stars</option>
                                        <option value="4" <?= ($editTest['rating'] ?? 5) == 4 ? 'selected' : ''; ?>>4 Stars</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Testimonial Quote *</label>
                                <textarea name="content" rows="4" class="form-control" required placeholder="Beneficiary statement or feedback"><?= e($editTest['content'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="tFeat" <?= !empty($editTest['is_featured']) ? 'checked' : ''; ?>>
                                <label class="form-check-label small" for="tFeat">Show on Homepage</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editTest ? 'Update Testimonial' : 'Save Testimonial'; ?></button>
                                <?php if ($editTest): ?>
                                <a href="<?= ADMIN_URL; ?>/testimonials/index.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">All Testimonials (<?= count($testimonials); ?>)</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead><tr><th>Name</th><th>Role</th><th>Rating</th><th>Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($testimonials as $t): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block"><?= e($t['name']); ?></strong>
                                        <small class="text-muted"><?= e(truncateText($t['content'], 60)); ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= e($t['designation']); ?></span></td>
                                    <td><?= renderRatingStars((int)$t['rating']); ?></td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/testimonials/index.php?edit=<?= $t['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/testimonials/index.php?delete=<?= $t['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
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

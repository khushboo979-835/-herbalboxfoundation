<?php
/**
 * School Partnerships & MOU CRUD Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM schools WHERE id = ?")->execute([$delId]);
    logActivity('Deleted School Partner', 'schools', $delId);
    setFlashMessage('success', 'School partner deleted.');
    header('Location: ' . ADMIN_URL . '/schools/index.php');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $schoolId = (int)($_POST['school_id'] ?? 0);
    $name = sanitize($_POST['name'] ?? '');
    $slug = slugify($_POST['slug'] ?: $name);
    $principal = sanitize($_POST['principal_name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $city = sanitize($_POST['city'] ?? 'New Delhi');
    $state = sanitize($_POST['state'] ?? 'Delhi');
    $mouDate = !empty($_POST['mou_date']) ? sanitize($_POST['mou_date']) : null;
    $students = (int)($_POST['students_benefited'] ?? 0);
    $programs = sanitize($_POST['active_programs'] ?? '');
    $desc = sanitize($_POST['description'] ?? '');
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $status = sanitize($_POST['status'] ?? 'active');

    $logoPath = null;
    if (!empty($_FILES['logo']['name'])) {
        $upload = uploadFile($_FILES['logo'], 'schools', 'image');
        if ($upload['success']) $logoPath = $upload['filename'];
    }

    $docPath = null;
    if (!empty($_FILES['mou_document']['name'])) {
        $uploadDoc = uploadFile($_FILES['mou_document'], 'documents', 'document');
        if ($uploadDoc['success']) $docPath = $uploadDoc['filename'];
    }

    if ($schoolId > 0) {
        $sql = "UPDATE schools SET name = ?, slug = ?, principal_name = ?, phone = ?, email = ?, address = ?, city = ?, state = ?, mou_date = ?, students_benefited = ?, active_programs = ?, description = ?, is_featured = ?, status = ?";
        $params = [$name, $slug, $principal, $phone, $email, $address, $city, $state, $mouDate, $students, $programs, $desc, $isFeatured, $status];

        if ($logoPath) {
            $sql .= ", logo = ?";
            $params[] = $logoPath;
        }
        if ($docPath) {
            $sql .= ", mou_document = ?";
            $params[] = $docPath;
        }
        $sql .= " WHERE id = ?";
        $params[] = $schoolId;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        logActivity('Updated School Partner', 'schools', $schoolId);
        setFlashMessage('success', 'School partner updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO schools (name, slug, principal_name, phone, email, address, city, state, mou_date, students_benefited, active_programs, description, is_featured, status, logo, mou_document) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $principal, $phone, $email, $address, $city, $state, $mouDate, $students, $programs, $desc, $isFeatured, $status, $logoPath, $docPath]);
        logActivity('Created School Partner', 'schools');
        setFlashMessage('success', 'New school partner added.');
    }

    header('Location: ' . ADMIN_URL . '/schools/index.php');
    exit;
}

$schools = $pdo->query("SELECT * FROM schools ORDER BY id DESC")->fetchAll();
$editSchool = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM schools WHERE id = ?");
    $st->execute([$editId]);
    $editSchool = $st->fetch();
}

$adminTitle = 'School Partners & MOUs';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Manage School Partners & Signed MOUs</h4>
                <p class="text-muted small mb-0">Record school partnerships, student beneficiaries, PDF MOU agreements, and active health/NCERT programs.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editSchool ? 'Edit School #' . $editSchool['id'] : 'Enroll Partner School'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/schools/index.php" method="POST" enctype="multipart/form-data" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="school_id" value="<?= (int)($editSchool['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">School Full Name *</label>
                                <input type="text" id="titleInput" name="name" class="form-control" required value="<?= e($editSchool['name'] ?? ''); ?>" placeholder="e.g. Sarvodaya Bal Vidyalaya">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">URL Slug</label>
                                <input type="text" id="slugInput" name="slug" class="form-control" value="<?= e($editSchool['slug'] ?? ''); ?>">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Principal / Contact</label>
                                    <input type="text" name="principal_name" class="form-control" value="<?= e($editSchool['principal_name'] ?? ''); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Phone *</label>
                                    <input type="text" name="phone" class="form-control" required value="<?= e($editSchool['phone'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">City *</label>
                                    <input type="text" name="city" class="form-control" required value="<?= e($editSchool['city'] ?? 'New Delhi'); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">State</label>
                                    <input type="text" name="state" class="form-control" value="<?= e($editSchool['state'] ?? 'Delhi'); ?>">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">MOU Signed Date</label>
                                    <input type="date" name="mou_date" class="form-control" value="<?= e($editSchool['mou_date'] ?? ''); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Benefited Students</label>
                                    <input type="number" name="students_benefited" class="form-control" value="<?= (int)($editSchool['students_benefited'] ?? 1000); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Active Programs (Comma separated)</label>
                                <textarea name="active_programs" rows="2" class="form-control"><?= e($editSchool['active_programs'] ?? 'Annual Health Checks, Eye Screening, NCERT Study Kits'); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">School Description</label>
                                <textarea name="description" rows="3" class="form-control"><?= e($editSchool['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">School Logo</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">MOU Document (PDF)</label>
                                    <input type="file" name="mou_document" class="form-control" accept=".pdf">
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="sFeat" <?= !empty($editSchool['is_featured']) ? 'checked' : ''; ?>>
                                <label class="form-check-label small" for="sFeat">Feature on Homepage</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editSchool ? 'Update School' : 'Save School Partner'; ?></button>
                                <?php if ($editSchool): ?>
                                <a href="<?= ADMIN_URL; ?>/schools/index.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">Partner Schools (<?= count($schools); ?>)</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead>
                                <tr>
                                    <th>School Name</th>
                                    <th>City</th>
                                    <th>Students</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($schools as $s): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block text-dark"><?= e($s['name']); ?></strong>
                                        <small class="text-muted">MOU: <?= formatDate($s['mou_date']); ?></small>
                                    </td>
                                    <td><?= e($s['city']); ?></td>
                                    <td><span class="badge bg-success-subtle text-success"><?= number_format((int)$s['students_benefited']); ?></span></td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/schools/index.php?edit=<?= $s['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/schools/index.php?delete=<?= $s['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
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

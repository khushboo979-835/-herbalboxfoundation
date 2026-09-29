<?php
/**
 * Hospitals & Diagnostic Centres CRUD Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM hospitals WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Hospital', 'hospitals', $delId);
    setFlashMessage('success', 'Hospital removed from directory.');
    header('Location: ' . ADMIN_URL . '/hospitals/index.php');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $hospId = (int)($_POST['hosp_id'] ?? 0);
    $name = sanitize($_POST['name'] ?? '');
    $type = sanitize($_POST['type'] ?? 'hospital');
    $contactPerson = sanitize($_POST['contact_person'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $website = sanitize($_POST['website'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $state = sanitize($_POST['state'] ?? 'Delhi');
    $services = sanitize($_POST['services_offered'] ?? '');
    $details = sanitize($_POST['partnership_details'] ?? '');
    $mouDate = !empty($_POST['mou_signed_date']) ? sanitize($_POST['mou_signed_date']) : null;
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $status = sanitize($_POST['status'] ?? 'active');

    if ($hospId > 0) {
        $stmt = $pdo->prepare("UPDATE hospitals SET name = ?, type = ?, contact_person = ?, phone = ?, email = ?, website = ?, address = ?, city = ?, state = ?, services_offered = ?, partnership_details = ?, mou_signed_date = ?, is_featured = ?, status = ? WHERE id = ?");
        $stmt->execute([$name, $type, $contactPerson, $phone, $email, $website, $address, $city, $state, $services, $details, $mouDate, $isFeatured, $status, $hospId]);
        logActivity('Updated Hospital', 'hospitals', $hospId);
        setFlashMessage('success', 'Hospital record updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO hospitals (name, type, contact_person, phone, email, website, address, city, state, services_offered, partnership_details, mou_signed_date, is_featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $type, $contactPerson, $phone, $email, $website, $address, $city, $state, $services, $details, $mouDate, $isFeatured, $status]);
        logActivity('Added Hospital', 'hospitals');
        setFlashMessage('success', 'New hospital added.');
    }

    header('Location: ' . ADMIN_URL . '/hospitals/index.php');
    exit;
}

$hospitals = $pdo->query("SELECT * FROM hospitals ORDER BY id DESC")->fetchAll();
$editHosp = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM hospitals WHERE id = ?");
    $st->execute([$editId]);
    $editHosp = $st->fetch();
}

$adminTitle = 'Hospitals & Diagnostic Centers';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Partner Hospitals & Diagnostic Labs</h4>
                <p class="text-muted small mb-0">Manage hospital referral networks, subsidized diagnostics, and pathology partners.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editHosp ? 'Edit Hospital #' . $editHosp['id'] : 'Enroll Partner Hospital / Lab'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/hospitals/index.php" method="POST" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="hosp_id" value="<?= (int)($editHosp['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Institution Name *</label>
                                <input type="text" name="name" class="form-control" required value="<?= e($editHosp['name'] ?? ''); ?>" placeholder="e.g. Sanjivani Hospital">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Type</label>
                                    <select name="type" class="form-select">
                                        <option value="hospital" <?= ($editHosp['type'] ?? '') === 'hospital' ? 'selected' : ''; ?>>Hospital</option>
                                        <option value="clinic" <?= ($editHosp['type'] ?? '') === 'clinic' ? 'selected' : ''; ?>>Specialty Clinic</option>
                                        <option value="diagnostic_center" <?= ($editHosp['type'] ?? '') === 'diagnostic_center' ? 'selected' : ''; ?>>Diagnostic / Lab</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control" value="<?= e($editHosp['contact_person'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Phone *</label>
                                    <input type="text" name="phone" class="form-control" required value="<?= e($editHosp['phone'] ?? ''); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= e($editHosp['email'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">City *</label>
                                    <input type="text" name="city" class="form-control" required value="<?= e($editHosp['city'] ?? 'New Delhi'); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">State</label>
                                    <input type="text" name="state" class="form-control" value="<?= e($editHosp['state'] ?? 'Delhi'); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Services Offered</label>
                                <textarea name="services_offered" rows="2" class="form-control"><?= e($editHosp['services_offered'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">MOU Signed Date</label>
                                <input type="date" name="mou_signed_date" class="form-control" value="<?= e($editHosp['mou_signed_date'] ?? ''); ?>">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editHosp ? 'Update Hospital' : 'Save Hospital'; ?></button>
                                <?php if ($editHosp): ?>
                                <a href="<?= ADMIN_URL; ?>/hospitals/index.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">Hospital Network (<?= count($hospitals); ?>)</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead>
                                <tr>
                                    <th>Institution</th>
                                    <th>Type</th>
                                    <th>City</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hospitals as $h): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block text-dark"><?= e($h['name']); ?></strong>
                                        <small class="text-muted"><?= e($h['phone']); ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= strtoupper($h['type']); ?></span></td>
                                    <td><?= e($h['city']); ?></td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/hospitals/index.php?edit=<?= $h['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/hospitals/index.php?delete=<?= $h['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
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

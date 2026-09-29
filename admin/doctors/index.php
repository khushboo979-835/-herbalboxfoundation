<?php
/**
 * Doctors Network CRUD Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM doctors WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Doctor', 'doctors', $delId);
    setFlashMessage('success', 'Doctor removed from directory.');
    header('Location: ' . ADMIN_URL . '/doctors/index.php');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $docId = (int)($_POST['doc_id'] ?? 0);
    $hospitalId = !empty($_POST['hospital_id']) ? (int)$_POST['hospital_id'] : null;
    $name = sanitize($_POST['name'] ?? '');
    $qualification = sanitize($_POST['qualification'] ?? '');
    $specialization = sanitize($_POST['specialization'] ?? '');
    $treatmentType = sanitize($_POST['treatment_type'] ?? 'allopathic');
    $experienceYears = (int)($_POST['experience_years'] ?? 0);
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $clinicHospital = sanitize($_POST['clinic_hospital_name'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $state = sanitize($_POST['state'] ?? 'Delhi');
    $availableDays = sanitize($_POST['available_days'] ?? 'Monday - Saturday');
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $status = sanitize($_POST['status'] ?? 'active');

    $photoPath = null;
    if (!empty($_FILES['photo']['name'])) {
        $upload = uploadFile($_FILES['photo'], 'doctors', 'image');
        if ($upload['success']) $photoPath = $upload['filename'];
    }

    if ($docId > 0) {
        if ($photoPath) {
            $stmt = $pdo->prepare("UPDATE doctors SET hospital_id = ?, name = ?, qualification = ?, specialization = ?, treatment_type = ?, experience_years = ?, phone = ?, email = ?, clinic_hospital_name = ?, city = ?, state = ?, available_days = ?, is_featured = ?, status = ?, photo = ? WHERE id = ?");
            $stmt->execute([$hospitalId, $name, $qualification, $specialization, $treatmentType, $experienceYears, $phone, $email, $clinicHospital, $city, $state, $availableDays, $isFeatured, $status, $photoPath, $docId]);
        } else {
            $stmt = $pdo->prepare("UPDATE doctors SET hospital_id = ?, name = ?, qualification = ?, specialization = ?, treatment_type = ?, experience_years = ?, phone = ?, email = ?, clinic_hospital_name = ?, city = ?, state = ?, available_days = ?, is_featured = ?, status = ? WHERE id = ?");
            $stmt->execute([$hospitalId, $name, $qualification, $specialization, $treatmentType, $experienceYears, $phone, $email, $clinicHospital, $city, $state, $availableDays, $isFeatured, $status, $docId]);
        }
        logActivity('Updated Doctor Profile', 'doctors', $docId);
        setFlashMessage('success', 'Doctor profile updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO doctors (hospital_id, name, qualification, specialization, treatment_type, experience_years, phone, email, clinic_hospital_name, city, state, available_days, is_featured, status, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$hospitalId, $name, $qualification, $specialization, $treatmentType, $experienceYears, $phone, $email, $clinicHospital, $city, $state, $availableDays, $isFeatured, $status, $photoPath]);
        logActivity('Added Doctor Profile', 'doctors');
        setFlashMessage('success', 'New doctor added to network.');
    }

    header('Location: ' . ADMIN_URL . '/doctors/index.php');
    exit;
}

$hospitals = $pdo->query("SELECT id, name FROM hospitals WHERE status = 'active' ORDER BY name ASC")->fetchAll();
$doctors = $pdo->query("SELECT d.*, h.name as hospital_name FROM doctors d LEFT JOIN hospitals h ON d.hospital_id = h.id ORDER BY d.id DESC")->fetchAll();

$editDoc = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM doctors WHERE id = ?");
    $st->execute([$editId]);
    $editDoc = $st->fetch();
}

$adminTitle = 'Manage Doctor Partners';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Partner Doctors & Medical Specialists</h4>
                <p class="text-muted small mb-0">Manage allopathic, ayurvedic, homeopathic and yoga wellness consultants volunteering across camps.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editDoc ? 'Edit Doctor #' . $editDoc['id'] : 'Enroll Partner Doctor'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/doctors/index.php" method="POST" enctype="multipart/form-data" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="doc_id" value="<?= (int)($editDoc['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Doctor Full Name *</label>
                                <input type="text" name="name" class="form-control" required value="<?= e($editDoc['name'] ?? ''); ?>" placeholder="e.g. Dr. Anand Verma">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Qualification *</label>
                                    <input type="text" name="qualification" class="form-control" required value="<?= e($editDoc['qualification'] ?? ''); ?>" placeholder="e.g. MBBS, MD">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Specialization *</label>
                                    <input type="text" name="specialization" class="form-control" required value="<?= e($editDoc['specialization'] ?? ''); ?>" placeholder="e.g. Ophthalmologist">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">System of Medicine</label>
                                    <select name="treatment_type" class="form-select">
                                        <option value="allopathic" <?= ($editDoc['treatment_type'] ?? '') === 'allopathic' ? 'selected' : ''; ?>>Allopathic</option>
                                        <option value="ayurvedic" <?= ($editDoc['treatment_type'] ?? '') === 'ayurvedic' ? 'selected' : ''; ?>>Ayurveda</option>
                                        <option value="homeopathic" <?= ($editDoc['treatment_type'] ?? '') === 'homeopathic' ? 'selected' : ''; ?>>Homeopathy</option>
                                        <option value="yoga_wellness" <?= ($editDoc['treatment_type'] ?? '') === 'yoga_wellness' ? 'selected' : ''; ?>>Yoga & Wellness</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Experience (Yrs)</label>
                                    <input type="number" name="experience_years" class="form-control" value="<?= (int)($editDoc['experience_years'] ?? 5); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Affiliated Hospital / Lab</label>
                                <select name="hospital_id" class="form-select">
                                    <option value="">-- None / Independent Clinic --</option>
                                    <?php foreach ($hospitals as $h): ?>
                                    <option value="<?= $h['id']; ?>" <?= ($editDoc['hospital_id'] ?? 0) == $h['id'] ? 'selected' : ''; ?>><?= e($h['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Clinic / Practice Name</label>
                                <input type="text" name="clinic_hospital_name" class="form-control" value="<?= e($editDoc['clinic_hospital_name'] ?? ''); ?>" placeholder="Clinic Name">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">City *</label>
                                    <input type="text" name="city" class="form-control" required value="<?= e($editDoc['city'] ?? 'New Delhi'); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">State</label>
                                    <input type="text" name="state" class="form-control" value="<?= e($editDoc['state'] ?? 'Delhi'); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Available Camp Days</label>
                                <input type="text" name="available_days" class="form-control" value="<?= e($editDoc['available_days'] ?? 'Monday - Saturday'); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Doctor Photo Upload</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" <?= ($editDoc['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?= ($editDoc['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-6 d-flex align-items-center pt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_featured" id="docFeat" <?= !empty($editDoc['is_featured']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label small" for="docFeat">Featured Doctor</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editDoc ? 'Update Doctor' : 'Save Doctor'; ?></button>
                                <?php if ($editDoc): ?>
                                <a href="<?= ADMIN_URL; ?>/doctors/index.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">Doctor Directory (<?= count($doctors); ?>)</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead>
                                <tr>
                                    <th>Doctor</th>
                                    <th>Specialty</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($doctors as $d): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block text-dark"><?= e($d['name']); ?></strong>
                                        <small class="text-muted"><?= e($d['qualification']); ?> • <?= e($d['city']); ?></small>
                                    </td>
                                    <td><?= e($d['specialization']); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= strtoupper($d['treatment_type']); ?></span></td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/doctors/index.php?edit=<?= $d['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/doctors/index.php?delete=<?= $d['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
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

<?php
/**
 * About Us, Team Members & Annual Reports Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Handle Team Member Delete
if (isset($_GET['del_team'])) {
    $delId = (int)$_GET['del_team'];
    $pdo->prepare("DELETE FROM team_members WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Team Member', 'team_members', $delId);
    setFlashMessage('success', 'Team member removed.');
    header('Location: ' . ADMIN_URL . '/about/index.php');
    exit;
}

// Handle Report Delete
if (isset($_GET['del_report'])) {
    $delId = (int)$_GET['del_report'];
    $pdo->prepare("DELETE FROM certificates_reports WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Report/Certificate', 'certificates_reports', $delId);
    setFlashMessage('success', 'Report/Certificate deleted.');
    header('Location: ' . ADMIN_URL . '/about/index.php');
    exit;
}

// Handle Content or New Team Member Add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $action = $_POST['action'] ?? '';

    if ($action === 'save_about_text') {
        $aboutText = $_POST['settings'] ?? [];
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_group) VALUES (?, ?, 'about') ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        foreach ($aboutText as $k => $v) {
            $stmt->execute([sanitize($k), sanitize($v)]);
        }
        logActivity('Updated About Content', 'about_us');
        setFlashMessage('success', 'About Us text content updated.');
    } elseif ($action === 'add_team_member') {
        $name = sanitize($_POST['name'] ?? '');
        $designation = sanitize($_POST['designation'] ?? '');
        $category = sanitize($_POST['category'] ?? 'management');
        $bio = sanitize($_POST['bio'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 1);

        $photoPath = null;
        if (!empty($_FILES['photo']['name'])) {
            $upload = uploadFile($_FILES['photo'], 'team', 'image');
            if ($upload['success']) $photoPath = $upload['filename'];
        }

        $ins = $pdo->prepare("INSERT INTO team_members (name, designation, category, photo, bio, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
        $ins->execute([$name, $designation, $category, $photoPath, $bio, $sortOrder]);
        logActivity('Added Team Member', 'team_members');
        setFlashMessage('success', 'New team member added.');
    } elseif ($action === 'add_report') {
        $title = sanitize($_POST['report_title'] ?? '');
        $type = sanitize($_POST['report_type'] ?? 'certificate');
        $fy = sanitize($_POST['financial_year'] ?? '2025-26');

        $filePath = '';
        if (!empty($_FILES['report_file']['name'])) {
            $upload = uploadFile($_FILES['report_file'], 'documents', 'document');
            if ($upload['success']) $filePath = $upload['filename'];
        }

        if ($filePath) {
            $ins = $pdo->prepare("INSERT INTO certificates_reports (title, type, financial_year, file_path, status) VALUES (?, ?, ?, ?, 'active')");
            $ins->execute([$title, $type, $fy, $filePath]);
            logActivity('Added Audit Report/Certificate', 'certificates_reports');
            setFlashMessage('success', 'Document uploaded successfully.');
        } else {
            setFlashMessage('danger', 'Please upload a valid PDF document.');
        }
    }

    header('Location: ' . ADMIN_URL . '/about/index.php');
    exit;
}

$teamMembers = $pdo->query("SELECT * FROM team_members ORDER BY sort_order ASC, id ASC")->fetchAll();
$reports = $pdo->query("SELECT * FROM certificates_reports ORDER BY id DESC")->fetchAll();

$adminTitle = 'About Us, Team & Accreditations';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">About Us, Leadership & Reports</h4>
                <p class="text-muted small mb-0">Update mission statement, board of trustees, legal certificates, and audited annual reports.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <!-- 1. Text Content -->
        <div class="card-admin mb-4">
            <div class="card-header"><i class="fas fa-edit text-primary me-2"></i> About Us Text & Mission/Vision</div>
            <div class="p-4">
                <form action="<?= ADMIN_URL; ?>/about/index.php" method="POST" class="form-custom">
                    <?= getCsrfInput(); ?>
                    <input type="hidden" name="action" value="save_about_text">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Hero Headline</label>
                        <input type="text" name="settings[about_hero_headline]" class="form-control" value="<?= e(getSetting('about_hero_headline')); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Mission Statement *</label>
                        <textarea name="settings[about_mission]" rows="2" class="form-control" required><?= e(getSetting('about_mission')); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Vision Statement *</label>
                        <textarea name="settings[about_vision]" rows="2" class="form-control" required><?= e(getSetting('about_vision')); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Founding History & Story *</label>
                        <textarea name="settings[about_history]" rows="4" class="form-control" required><?= e(getSetting('about_history')); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Update About Text</button>
                </form>
            </div>
        </div>

        <!-- 2. Team Members & Reports Split -->
        <div class="row g-4">
            <!-- Team Members -->
            <div class="col-lg-6">
                <div class="card-admin">
                    <div class="card-header"><i class="fas fa-users text-success me-2"></i> Board of Trustees & Leadership</div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/about/index.php" method="POST" enctype="multipart/form-data" class="form-custom mb-4 pb-4 border-bottom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="action" value="add_team_member">

                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Name *</label>
                                    <input type="text" name="name" class="form-control" required placeholder="e.g. Dr. Rajesh Sharma">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Designation *</label>
                                    <input type="text" name="designation" class="form-control" required placeholder="e.g. Founder & President">
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="founder">Founder</option>
                                        <option value="trustee">Trustee</option>
                                        <option value="advisor">Advisor</option>
                                        <option value="management">Management</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Photo Upload</label>
                                    <input type="file" name="photo" class="form-control" accept="image/*">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Bio</label>
                                <textarea name="bio" rows="2" class="form-control" placeholder="Short biographical background"></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success w-100"><i class="fas fa-plus me-1"></i> Add Team Member</button>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-admin mb-0">
                                <thead><tr><th>Name</th><th>Role</th><th>Action</th></tr></thead>
                                <tbody>
                                    <?php foreach ($teamMembers as $tm): ?>
                                    <tr>
                                        <td><strong><?= e($tm['name']); ?></strong></td>
                                        <td><span class="badge bg-light text-dark border"><?= e($tm['designation']); ?></span></td>
                                        <td><a href="<?= ADMIN_URL; ?>/about/index.php?del_team=<?= $tm['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports & Certificates -->
            <div class="col-lg-6">
                <div class="card-admin">
                    <div class="card-header"><i class="fas fa-file-pdf text-danger me-2"></i> Annual Reports & Certificates</div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/about/index.php" method="POST" enctype="multipart/form-data" class="form-custom mb-4 pb-4 border-bottom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="action" value="add_report">

                            <div class="mb-2">
                                <label class="form-label small fw-bold">Document Title *</label>
                                <input type="text" name="report_title" class="form-control" required placeholder="e.g. Annual Audit Report FY 2024-25">
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Type</label>
                                    <select name="report_type" class="form-select">
                                        <option value="annual_report">Annual Report</option>
                                        <option value="audit_report">Audit Report</option>
                                        <option value="certificate">Legal Certificate</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Financial Year</label>
                                    <input type="text" name="financial_year" class="form-control" value="2024-25">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Upload PDF Document *</label>
                                <input type="file" name="report_file" class="form-control" accept=".pdf" required>
                            </div>
                            <button type="submit" class="btn btn-sm btn-danger w-100"><i class="fas fa-upload me-1"></i> Upload Document</button>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-admin mb-0">
                                <thead><tr><th>Title</th><th>Year</th><th>Action</th></tr></thead>
                                <tbody>
                                    <?php foreach ($reports as $r): ?>
                                    <tr>
                                        <td><strong><?= e($r['title']); ?></strong></td>
                                        <td><?= e($r['financial_year']); ?></td>
                                        <td><a href="<?= ADMIN_URL; ?>/about/index.php?del_report=<?= $r['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

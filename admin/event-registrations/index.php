<?php
/**
 * Camp & Event Registrations Viewer + CSV Export
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

$filterEventId = (int)($_GET['event_id'] ?? 0);

// CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $sql = "SELECT er.id, er.registration_number, er.name, er.phone, er.email, er.age, er.gender, er.blood_group, er.city, e.title as event_title, er.created_at FROM event_registrations er LEFT JOIN events e ON er.event_id = e.id";
    if ($filterEventId > 0) {
        $sql .= " WHERE er.event_id = " . $filterEventId;
    }
    $sql .= " ORDER BY er.id DESC";

    $data = $pdo->query($sql)->fetchAll();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=event_registrations_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Reg No', 'Name', 'Phone', 'Email', 'Age', 'Gender', 'Blood Group', 'City', 'Event Title', 'Registration Date']);
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Fetch events list for dropdown filter
$eventsList = $pdo->query("SELECT id, title FROM events ORDER BY event_date DESC")->fetchAll();

// Fetch registrations
$sql = "SELECT er.*, e.title as event_title, e.event_date FROM event_registrations er LEFT JOIN events e ON er.event_id = e.id WHERE 1=1";
$params = [];
if ($filterEventId > 0) {
    $sql .= " AND er.event_id = ?";
    $params[] = $filterEventId;
}
$sql .= " ORDER BY er.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registrations = $stmt->fetchAll();

$adminTitle = 'Camp & Event Registrations';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Camp Registrations & Attendee Passes</h4>
                <p class="text-muted small mb-0">View registered patients and attendees for medical, eye, dental, and blood camps.</p>
            </div>
            <a href="<?= ADMIN_URL; ?>/event-registrations/index.php?export=csv<?= $filterEventId ? '&event_id=' . $filterEventId : ''; ?>" class="btn btn-sm btn-outline-success">
                <i class="fas fa-file-excel me-1"></i> Export Attendee List (CSV)
            </a>
        </div>

        <?= displayFlashMessage(); ?>

        <!-- Filter Bar -->
        <div class="card-admin mb-4">
            <div class="p-3 bg-light">
                <form action="<?= ADMIN_URL; ?>/event-registrations/index.php" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <select name="event_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- All Events / Health Camps --</option>
                            <?php foreach ($eventsList as $ev): ?>
                            <option value="<?= $ev['id']; ?>" <?= $filterEventId == $ev['id'] ? 'selected' : ''; ?>><?= e($ev['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 text-end">
                        <input type="text" id="tableSearchInput" class="form-control form-control-sm" placeholder="Live search attendee...">
                    </div>
                </form>
            </div>
        </div>

        <div class="card-admin">
            <div class="table-responsive">
                <table class="table table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Pass Code</th>
                            <th>Attendee Name</th>
                            <th>Phone</th>
                            <th>Age / Sex</th>
                            <th>Camp / Event</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($registrations)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No attendees registered yet</td></tr>
                        <?php else: foreach ($registrations as $r): ?>
                        <tr>
                            <td><span class="badge bg-dark font-monospace"><?= e($r['registration_number']); ?></span></td>
                            <td>
                                <strong class="text-dark d-block"><?= e($r['name']); ?></strong>
                                <small class="text-muted"><?= e($r['email'] ?? 'No email'); ?></small>
                            </td>
                            <td><?= e($r['phone']); ?></td>
                            <td><?= (int)$r['age']; ?> Yrs • <?= e($r['gender']); ?></td>
                            <td>
                                <strong class="small text-primary d-block"><?= e($r['event_title']); ?></strong>
                                <small class="text-muted"><?= formatDate($r['event_date']); ?></small>
                            </td>
                            <td><span class="badge bg-success-subtle text-success"><?= strtoupper($r['status']); ?></span></td>
                            <td><small class="text-muted"><?= formatDate($r['created_at']); ?></small></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

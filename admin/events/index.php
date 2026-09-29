<?php
/**
 * Events & Mega Health Camps CRUD Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM events WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Event', 'events', $delId);
    setFlashMessage('success', 'Event deleted successfully.');
    header('Location: ' . ADMIN_URL . '/events/index.php');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $eventId = (int)($_POST['event_id'] ?? 0);
    $title = sanitize($_POST['title'] ?? '');
    $slug = slugify($_POST['slug'] ?: $title);
    $category = sanitize($_POST['category'] ?? 'medical_camp');
    $shortDesc = sanitize($_POST['short_description'] ?? '');
    $desc = sanitize($_POST['description'] ?? '');
    $eventDate = sanitize($_POST['event_date'] ?? date('Y-m-d'));
    $startTime = sanitize($_POST['start_time'] ?? '09:00:00');
    $endTime = sanitize($_POST['end_time'] ?? '17:00:00');
    $venue = sanitize($_POST['venue'] ?? '');
    $city = sanitize($_POST['city'] ?? 'New Delhi');
    $state = sanitize($_POST['state'] ?? 'Delhi');
    $doctorInfo = sanitize($_POST['doctor_partner_info'] ?? '');
    $maxParticipants = (int)($_POST['max_participants'] ?? 200);
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $status = sanitize($_POST['status'] ?? 'upcoming');

    $imgPath = null;
    if (!empty($_FILES['featured_image']['name'])) {
        $upload = uploadFile($_FILES['featured_image'], 'events', 'image');
        if ($upload['success']) $imgPath = $upload['filename'];
    }

    if ($eventId > 0) {
        if ($imgPath) {
            $stmt = $pdo->prepare("UPDATE events SET title = ?, slug = ?, category = ?, short_description = ?, description = ?, event_date = ?, start_time = ?, end_time = ?, venue = ?, city = ?, state = ?, doctor_partner_info = ?, max_participants = ?, is_featured = ?, status = ?, featured_image = ? WHERE id = ?");
            $stmt->execute([$title, $slug, $category, $shortDesc, $desc, $eventDate, $startTime, $endTime, $venue, $city, $state, $doctorInfo, $maxParticipants, $isFeatured, $status, $imgPath, $eventId]);
        } else {
            $stmt = $pdo->prepare("UPDATE events SET title = ?, slug = ?, category = ?, short_description = ?, description = ?, event_date = ?, start_time = ?, end_time = ?, venue = ?, city = ?, state = ?, doctor_partner_info = ?, max_participants = ?, is_featured = ?, status = ? WHERE id = ?");
            $stmt->execute([$title, $slug, $category, $shortDesc, $desc, $eventDate, $startTime, $endTime, $venue, $city, $state, $doctorInfo, $maxParticipants, $isFeatured, $status, $eventId]);
        }
        logActivity('Updated Event', 'events', $eventId);
        setFlashMessage('success', 'Event updated successfully.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO events (title, slug, category, short_description, description, event_date, start_time, end_time, venue, city, state, doctor_partner_info, max_participants, is_featured, status, featured_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $category, $shortDesc, $desc, $eventDate, $startTime, $endTime, $venue, $city, $state, $doctorInfo, $maxParticipants, $isFeatured, $status, $imgPath]);
        logActivity('Created Event', 'events');
        setFlashMessage('success', 'New event created.');
    }

    header('Location: ' . ADMIN_URL . '/events/index.php');
    exit;
}

$events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();
$editEvt = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $st->execute([$editId]);
    $editEvt = $st->fetch();
}

$adminTitle = 'Events & Mega Health Camps';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Manage Events & Health Camps</h4>
                <p class="text-muted small mb-0">Schedule free mega checkups, blood donation drives, school seminars, and yoga camps.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editEvt ? 'Edit Event #' . $editEvt['id'] : 'Schedule New Event / Camp'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/events/index.php" method="POST" enctype="multipart/form-data" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="event_id" value="<?= (int)($editEvt['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Event Title *</label>
                                <input type="text" id="titleInput" name="title" class="form-control" required value="<?= e($editEvt['title'] ?? ''); ?>" placeholder="e.g. Mega Eye & Health Camp">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">URL Slug</label>
                                <input type="text" id="slugInput" name="slug" class="form-control" value="<?= e($editEvt['slug'] ?? ''); ?>">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="medical_camp" <?= ($editEvt['category'] ?? '') === 'medical_camp' ? 'selected' : ''; ?>>Medical Camp</option>
                                        <option value="blood_donation" <?= ($editEvt['category'] ?? '') === 'blood_donation' ? 'selected' : ''; ?>>Blood Donation Drive</option>
                                        <option value="eye_camp" <?= ($editEvt['category'] ?? '') === 'eye_camp' ? 'selected' : ''; ?>>Eye & Cataract Camp</option>
                                        <option value="dental_camp" <?= ($editEvt['category'] ?? '') === 'dental_camp' ? 'selected' : ''; ?>>Dental Camp</option>
                                        <option value="yoga_camp" <?= ($editEvt['category'] ?? '') === 'yoga_camp' ? 'selected' : ''; ?>>Yoga & Meditation</option>
                                        <option value="education_camp" <?= ($editEvt['category'] ?? '') === 'education_camp' ? 'selected' : ''; ?>>School Education Workshop</option>
                                        <option value="mou_signing" <?= ($editEvt['category'] ?? '') === 'mou_signing' ? 'selected' : ''; ?>>MOU Signing</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Event Date *</label>
                                    <input type="date" name="event_date" class="form-control" required value="<?= e($editEvt['event_date'] ?? date('Y-m-d')); ?>">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Start Time</label>
                                    <input type="time" name="start_time" class="form-control" value="<?= e($editEvt['start_time'] ?? '09:00'); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">End Time</label>
                                    <input type="time" name="end_time" class="form-control" value="<?= e($editEvt['end_time'] ?? '17:00'); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Venue Name & Address *</label>
                                <input type="text" name="venue" class="form-control" required value="<?= e($editEvt['venue'] ?? ''); ?>" placeholder="Auditorium / Park / School Complex">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">City *</label>
                                    <input type="text" name="city" class="form-control" required value="<?= e($editEvt['city'] ?? 'New Delhi'); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">State</label>
                                    <input type="text" name="state" class="form-control" value="<?= e($editEvt['state'] ?? 'Delhi'); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Doctors / Partners Present</label>
                                <input type="text" name="doctor_partner_info" class="form-control" value="<?= e($editEvt['doctor_partner_info'] ?? ''); ?>" placeholder="Dr. Anand Verma & Specialists Team">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Short Summary *</label>
                                <textarea name="short_description" rows="2" class="form-control" required><?= e($editEvt['short_description'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Full Event Description</label>
                                <textarea name="description" rows="3" class="form-control"><?= e($editEvt['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Cover Banner Image</label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="upcoming" <?= ($editEvt['status'] ?? '') === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                                        <option value="completed" <?= ($editEvt['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?= ($editEvt['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Max Attendees</label>
                                    <input type="number" name="max_participants" class="form-control" value="<?= (int)($editEvt['max_participants'] ?? 300); ?>">
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editEvt ? 'Update Event' : 'Schedule Event'; ?></button>
                                <?php if ($editEvt): ?>
                                <a href="<?= ADMIN_URL; ?>/events/index.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">All Scheduled Events (<?= count($events); ?>)</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Date</th>
                                    <th>Registrations</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($events as $ev): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block text-dark"><?= e($ev['title']); ?></strong>
                                        <small class="text-muted"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($ev['venue']); ?>, <?= e($ev['city']); ?></small>
                                    </td>
                                    <td>
                                        <span class="d-block fw-bold"><?= formatDate($ev['event_date']); ?></span>
                                        <span class="badge <?= $ev['status'] === 'upcoming' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'; ?>"><?= strtoupper($ev['status']); ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/event-registrations/index.php?event_id=<?= $ev['id']; ?>" class="badge bg-primary text-white text-decoration-none p-2">
                                            <i class="fas fa-users me-1"></i> <?= (int)$ev['registered_count']; ?> Registered
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/events/index.php?edit=<?= $ev['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/events/index.php?delete=<?= $ev['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
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

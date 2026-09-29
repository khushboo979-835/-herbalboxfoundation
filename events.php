<?php
/**
 * Events & Camps Master Directory
 * Seva Foundation Outreach
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

// Filters
$catFilter = sanitize($_GET['category'] ?? '');
$statusFilter = sanitize($_GET['status'] ?? 'upcoming');
$citySearch = sanitize($_GET['city'] ?? '');

$sql = "SELECT * FROM events WHERE 1=1";
$params = [];

if (!empty($catFilter)) {
    $sql .= " AND category = ?";
    $params[] = $catFilter;
}
if ($statusFilter === 'upcoming') {
    $sql .= " AND (status = 'upcoming' OR event_date >= CURRENT_DATE)";
} elseif ($statusFilter === 'past') {
    $sql .= " AND (status = 'completed' OR event_date < CURRENT_DATE)";
}
if (!empty($citySearch)) {
    $sql .= " AND (city LIKE ? OR venue LIKE ? OR title LIKE ?)";
    $term = "%{$citySearch}%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

$sql .= " ORDER BY event_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll();

$pageTitle = 'Events & Camps - Mega Health Checkups, Blood Drives & Yoga Mahotsavs';
$pageDesc = 'Discover upcoming and past community welfare events, free eye camps, blood donation drives, school seminars, and meditation camps.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1"><i class="fas fa-calendar-alt me-1"></i> Community Schedule</span>
        <h1 class="display-5 fw-bold text-white mb-3">Events, Health Camps & Youth Workshops</h1>
        <p class="lead text-white-50 max-w-700">Join our upcoming grassroots events or review the impact of our successfully concluded medical and educational drives.</p>
    </div>
</div>

<!-- Filter Bar -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <form action="<?= BASE_URL; ?>/events.php" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <input type="text" name="city" class="form-control" placeholder="Search event by name, city or venue..." value="<?= e($citySearch); ?>">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">-- All Categories --</option>
                    <option value="medical_camp" <?= $catFilter === 'medical_camp' ? 'selected' : ''; ?>>Free Medical Camp</option>
                    <option value="blood_donation" <?= $catFilter === 'blood_donation' ? 'selected' : ''; ?>>Blood Donation Drive</option>
                    <option value="eye_camp" <?= $catFilter === 'eye_camp' ? 'selected' : ''; ?>>Eye Care & Cataract Camp</option>
                    <option value="dental_camp" <?= $catFilter === 'dental_camp' ? 'selected' : ''; ?>>Dental Hygiene Camp</option>
                    <option value="yoga_camp" <?= $catFilter === 'yoga_camp' ? 'selected' : ''; ?>>Yoga & Meditation Camp</option>
                    <option value="education_camp" <?= $catFilter === 'education_camp' ? 'selected' : ''; ?>>School Education Workshop</option>
                    <option value="mou_signing" <?= $catFilter === 'mou_signing' ? 'selected' : ''; ?>>MOU Signing Ceremony</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="upcoming" <?= $statusFilter === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                    <option value="past" <?= $statusFilter === 'past' ? 'selected' : ''; ?>>Past Events</option>
                    <option value="all" <?= $statusFilter === 'all' ? 'selected' : ''; ?>>All Events</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-ngo-primary w-100">Filter Events</button>
                <a href="<?= BASE_URL; ?>/events.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</section>

<!-- Events Grid -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <?php if (empty($events)): ?>
        <div class="p-5 bg-white rounded-4 border text-center">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h5>No events found matching your criteria</h5>
            <p class="text-muted">Please adjust your search terms or filter selections.</p>
            <a href="<?= BASE_URL; ?>/events.php" class="btn btn-outline-primary">View All Upcoming Events</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($events as $evt): ?>
            <div class="col-lg-4 col-md-6">
                <div class="event-card">
                    <div class="position-relative">
                        <img src="<?= e(getImageUrl($evt['featured_image'], 'https://images.unsplash.com/photo-1516549655169-df83a0774514?w=600&q=80')); ?>" alt="<?= e($evt['title']); ?>" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                        <div class="event-date-badge">
                            <div class="day"><?= date('d', strtotime($evt['event_date'])); ?></div>
                            <div class="month"><?= date('M', strtotime($evt['event_date'])); ?></div>
                        </div>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="badge bg-primary-subtle text-primary align-self-start mb-2"><?= strtoupper(str_replace('_', ' ', $evt['category'])); ?></span>
                        <h5 class="fw-bold mb-2"><a href="<?= BASE_URL; ?>/event-details.php?id=<?= $evt['id']; ?>" class="text-dark text-decoration-none"><?= e($evt['title']); ?></a></h5>
                        <p class="small text-muted mb-2"><i class="fas fa-clock text-primary me-1"></i> <?= date('h:i A', strtotime($evt['start_time'])); ?> - <?= date('h:i A', strtotime($evt['end_time'])); ?></p>
                        <p class="small text-muted mb-3"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($evt['venue']); ?>, <?= e($evt['city']); ?></p>
                        <p class="small text-muted mb-4 flex-grow-1"><?= e($evt['short_description']); ?></p>
                        
                        <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                            <small class="text-muted"><i class="fas fa-users text-primary me-1"></i> <?= (int)$evt['registered_count']; ?> Registered</small>
                            <a href="<?= BASE_URL; ?>/event-details.php?id=<?= $evt['id']; ?>" class="btn btn-sm btn-ngo-primary">
                                <?= $evt['status'] === 'completed' ? 'Event Details' : 'Free Registration'; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

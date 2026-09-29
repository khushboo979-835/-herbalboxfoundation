<?php
/**
 * Yoga & Meditation Wellness Programs
 * Seva Yoga Peeth
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

// Fetch Yoga events
$stmt = $pdo->prepare("SELECT * FROM events WHERE category IN ('yoga_camp', 'meditation_camp') AND status = 'upcoming' ORDER BY event_date ASC");
$stmt->execute();
$yogaEvents = $stmt->fetchAll();

$pageTitle = 'Daily Yoga & Meditation Programs - Community Wellness & School Camps';
$pageDesc = 'Rejuvenate mind, body, and soul with daily morning yoga, Pranayama, guided meditation, and school wellness camps conducted by certified Yoga Acharyas.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #065f46 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1">Holistic Wellness</span>
        <h1 class="display-5 fw-bold text-white mb-3">Yoga & Mindful Meditation: Inner Peace & Physical Vitality</h1>
        <p class="lead text-white-50 max-w-700">Daily morning yoga sessions, therapeutic asanas, stress management for school students, and community meditation camps conducted at zero charge.</p>
        <div class="d-flex gap-3 mt-4">
            <a href="#sessions" class="btn btn-warning text-dark fw-bold"><i class="fas fa-calendar-alt me-1"></i> View Upcoming Yoga Sessions</a>
            <a href="<?= BASE_URL; ?>/contact.php?subject=School%20Yoga%20Workshop" class="btn btn-outline-light"><i class="fas fa-school me-1"></i> Request School Yoga Camp</a>
        </div>
    </div>
</div>

<!-- Core Yoga Modules -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Yogic Science</span>
            <h2 class="section-title">Our Yoga & Meditation Programs</h2>
            <p class="section-subtitle">Structured courses designed by certified Ministry of AYUSH Level-3 Yoga Instructors.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-spa"></i></div>
                    <h4 class="fw-bold mb-3">Morning Community Yoga</h4>
                    <p class="text-muted mb-3">Daily 6:00 AM park sessions featuring Surya Namaskar, gentle joint movements (Sukshma Vyayama), and dynamic flexibility asanas for seniors and adults.</p>
                    <span class="badge bg-light text-primary border">Daily 6:00 AM - 7:15 AM</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon text-success"><i class="fas fa-wind text-success"></i></div>
                    <h4 class="fw-bold mb-3">Pranayama & Breathwork</h4>
                    <h5 class="fs-6 text-muted mb-2">Immunity & Respiratory Health</h5>
                    <p class="text-muted mb-3">In-depth practice of Anulom Vilom, Kapalbhati, Bhastrika, and Bhramari for strengthening lung capacity, lowering stress hormones, and mental focus.</p>
                    <span class="badge bg-light text-success border">Daily 7:15 AM - 7:45 AM</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon text-warning"><i class="fas fa-brain text-warning"></i></div>
                    <h4 class="fw-bold mb-3">Dhyana (Guided Meditation)</h4>
                    <h5 class="fs-6 text-muted mb-2">Stress & Anxiety Release</h5>
                    <p class="text-muted mb-3">Mindfulness and Yoga Nidra sessions designed to calm sympathetic nervous excitement, improve deep sleep, and alleviate anxiety.</p>
                    <span class="badge bg-light text-warning border">Evening 6:30 PM - 7:30 PM</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon text-info"><i class="fas fa-school text-info"></i></div>
                    <h4 class="fw-bold mb-3">School Yoga & Memory</h4>
                    <p class="text-muted mb-3">Integrated into partner school timetables to enhance student focus, memory retention, spinal posture, and emotional resilience before board exams.</p>
                    <span class="badge bg-light text-info border">Under School MOUs</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon text-danger"><i class="fas fa-heartbeat text-danger"></i></div>
                    <h4 class="fw-bold mb-3">Therapeutic Yoga</h4>
                    <p class="text-muted mb-3">Customized physical therapy for chronic back pain, cervical spondylosis, hypertension, obesity, and knee joint stiffness with prop assistance.</p>
                    <span class="badge bg-light text-danger border">Doctor Advised Care</span>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon text-secondary"><i class="fas fa-award text-secondary"></i></div>
                    <h4 class="fw-bold mb-3">International Yoga Day</h4>
                    <p class="text-muted mb-3">Annual June 21 Mega Yoga Mahotsav bringing together 3,000+ citizens, students, and doctors in public stadiums to celebrate global wellness.</p>
                    <span class="badge bg-light text-secondary border">Annual Mahotsav</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Yoga Sessions List -->
<section class="py-5 bg-light" id="sessions">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Join an Upcoming Batch</span>
            <h2 class="section-title">Upcoming Yoga & Meditation Camps</h2>
            <p class="section-subtitle">Free entry. Pre-registration is mandatory to reserve yoga mats and complimentary herbal tea.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php if (empty($yogaEvents)): ?>
            <div class="col-12 text-center py-4">
                <div class="p-5 bg-white rounded-4 border">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5>No specific upcoming camps right now</h5>
                    <p class="text-muted">Our regular daily morning park sessions run Monday to Saturday. Check back soon for upcoming weekend workshops.</p>
                    <a href="<?= BASE_URL; ?>/contact.php" class="btn btn-ngo-primary">Inquire About Daily Batches</a>
                </div>
            </div>
            <?php else: foreach ($yogaEvents as $evt): ?>
            <div class="col-lg-4 col-md-6">
                <div class="event-card">
                    <div class="position-relative">
                        <img src="<?= e(getImageUrl($evt['featured_image'], 'https://images.unsplash.com/photo-1545205597-3d9d02c29597?w=600&q=80')); ?>" alt="<?= e($evt['title']); ?>" class="img-fluid w-100" style="height: 180px; object-fit: cover;">
                        <div class="event-date-badge">
                            <div class="day"><?= date('d', strtotime($evt['event_date'])); ?></div>
                            <div class="month"><?= date('M', strtotime($evt['event_date'])); ?></div>
                        </div>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <h5 class="fw-bold mb-2"><?= e($evt['title']); ?></h5>
                        <p class="small text-muted mb-2"><i class="fas fa-clock text-primary me-1"></i> <?= date('h:i A', strtotime($evt['start_time'])); ?> - <?= date('h:i A', strtotime($evt['end_time'])); ?></p>
                        <p class="small text-muted mb-3"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($evt['venue']); ?>, <?= e($evt['city']); ?></p>
                        <p class="small text-muted mb-4 flex-grow-1"><?= e($evt['short_description']); ?></p>
                        <a href="<?= BASE_URL; ?>/event-details.php?id=<?= $evt['id']; ?>" class="btn btn-sm btn-ngo-primary w-100 mt-auto">Register for Free</a>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

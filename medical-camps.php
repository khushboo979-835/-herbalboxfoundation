<?php
/**
 * Free Medical Camps Directory & Details
 * Seva Arogya Foundation
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

// Fetch upcoming & past medical camps
$upcomingCamps = $pdo->query("SELECT * FROM events WHERE category IN ('medical_camp', 'eye_camp', 'dental_camp') AND status = 'upcoming' ORDER BY event_date ASC")->fetchAll();
$pastCamps = $pdo->query("SELECT * FROM events WHERE category IN ('medical_camp', 'eye_camp', 'dental_camp') AND status = 'completed' ORDER BY event_date DESC LIMIT 6")->fetchAll();

$pageTitle = 'Free Medical Camps - Eye, Dental, General Health & Medicine Distribution';
$pageDesc = 'Discover upcoming free mega health checkup camps, eye refraction clinics, cataract surgeries, and dental camps organized across India.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #0f766e 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1">Direct Community Healthcare</span>
        <h1 class="display-5 fw-bold text-white mb-3">Free Medical Checkup & Diagnostic Camps</h1>
        <p class="lead text-white-50 max-w-700">Providing free physician consultations, blood sugar tests, ECG, dental checkups, prescription eyeglasses, and 15-day medicine supplies to all families in need.</p>
        <div class="d-flex gap-3 mt-4">
            <a href="#upcoming" class="btn btn-warning text-dark fw-bold"><i class="fas fa-calendar-alt me-1"></i> View Upcoming Camps</a>
            <a href="<?= BASE_URL; ?>/partnerships.php?type=hospital" class="btn btn-outline-light"><i class="fas fa-handshake me-1"></i> Partner as a Doctor/Hospital</a>
        </div>
    </div>
</div>

<!-- Camp Services Included -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Zero-Cost Services</span>
            <h2 class="section-title">What is Provided at Every Camp?</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-user-md fa-2x text-primary mb-3"></i>
                    <h5 class="fw-bold fs-6 mb-2">Specialist Consultation</h5>
                    <p class="small text-muted mb-0">Consultations with MD Physicians, Pediatricians, and Gynecologists.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-eye fa-2x text-success mb-3"></i>
                    <h5 class="fw-bold fs-6 mb-2">Eye & Vision Refraction</h5>
                    <p class="small text-muted mb-0">Visual acuity tests and free prescription reading glasses provided on the spot.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-pills fa-2x text-warning mb-3"></i>
                    <h5 class="fw-bold fs-6 mb-2">Free Medicine Supply</h5>
                    <p class="small text-muted mb-0">15 to 30 days of prescribed generic antibiotics, vitamins, and BP/sugar meds.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-heartbeat fa-2x text-danger mb-3"></i>
                    <h5 class="fw-bold fs-6 mb-2">Vitals & Sugar Checks</h5>
                    <p class="small text-muted mb-0">Random blood glucose, blood pressure, BMI, and spot ECG screening.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Camps Section -->
<section class="py-5 bg-light" id="upcoming">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Camp Schedule</span>
            <h2 class="section-title">Upcoming Medical Camps</h2>
            <p class="section-subtitle">Pre-register yourself and your family members online for priority doctor consultation.</p>
        </div>

        <div class="row g-4">
            <?php if (empty($upcomingCamps)): ?>
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded-4 border">
                    <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                    <h5>New Camp Schedule Under Planning</h5>
                    <p class="text-muted">Our medical team is scheduling upcoming mega camps. Contact our helpline for immediate consultation assistance.</p>
                    <a href="<?= BASE_URL; ?>/contact.php" class="btn btn-ngo-primary">Contact Healthcare Helpdesk</a>
                </div>
            </div>
            <?php else: foreach ($upcomingCamps as $camp): ?>
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary text-white"><?= strtoupper(str_replace('_', ' ', $camp['category'])); ?></span>
                        <div class="text-end">
                            <h4 class="text-primary fw-bold mb-0"><?= date('d M, Y', strtotime($camp['event_date'])); ?></h4>
                            <small class="text-muted"><?= date('h:i A', strtotime($camp['start_time'])); ?> - <?= date('h:i A', strtotime($camp['end_time'])); ?></small>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-2"><?= e($camp['title']); ?></h4>
                    <p class="text-muted mb-3"><i class="fas fa-map-marker-alt text-danger me-2"></i> <strong>Venue:</strong> <?= e($camp['venue']); ?>, <?= e($camp['city']); ?>, <?= e($camp['state']); ?></p>
                    <p class="text-muted small mb-4 flex-grow-1"><?= e($camp['description'] ?: $camp['short_description']); ?></p>
                    
                    <div class="p-3 bg-light rounded-3 mb-4 small">
                        <strong><i class="fas fa-user-md text-primary me-1"></i> Attending Doctors:</strong> <?= e($camp['doctor_partner_info'] ?? 'Seva Specialist Panel'); ?><br>
                        <strong><i class="fas fa-users text-primary me-1"></i> Capacity:</strong> Up to <?= number_format((int)$camp['max_participants']); ?> Patients
                    </div>

                    <div class="mt-auto d-flex gap-2">
                        <a href="<?= BASE_URL; ?>/event-details.php?id=<?= $camp['id']; ?>" class="btn btn-ngo-primary w-100"><i class="fas fa-user-plus me-1"></i> Register Free Online</a>
                        <a href="https://maps.google.com/?q=<?= urlencode($camp['venue'] . ' ' . $camp['city']); ?>" target="_blank" class="btn btn-outline-secondary" title="View Map"><i class="fas fa-directions"></i></a>
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

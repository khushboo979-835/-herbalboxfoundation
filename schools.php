<?php
/**
 * School Partnerships & MOU Directory
 * Seva Shiksha School Network
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

// Fetch all active school partners
$schoolsStmt = $pdo->query("SELECT * FROM schools WHERE status = 'active' ORDER BY is_featured DESC, students_benefited DESC, id ASC");
$schools = $schoolsStmt->fetchAll();

$pageTitle = 'School Partnerships & MOUs - Student Health & NCERT Support';
$pageDesc = 'Explore our network of 65+ partner government and private schools benefiting from student health screenings, eye checkups, NCERT study kits, and yoga.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1"><i class="fas fa-school me-1"></i> School Outreach</span>
        <h1 class="display-5 fw-bold text-white mb-3">Our School Partners & Signed MOUs</h1>
        <p class="lead text-white-50 max-w-700">Fostering healthy learning environments through regular student health checkups, vision tests, free NCERT learning kits, and school yoga programs.</p>
        <div class="d-flex gap-3 mt-4">
            <a href="<?= BASE_URL; ?>/partnerships.php?type=school" class="btn btn-warning text-dark fw-bold"><i class="fas fa-file-signature me-1"></i> Sign an MOU with Your School</a>
            <a href="<?= BASE_URL; ?>/mou.php" class="btn btn-outline-light"><i class="fas fa-file-contract me-1"></i> Public MOU Archive</a>
        </div>
    </div>
</div>

<!-- What School MOUs Cover -->
<section class="py-5 bg-white border-bottom">
    <div class="container py-3">
        <div class="section-header">
            <span class="section-tag">Partnership Framework</span>
            <h2 class="section-title">What is Included in Our School MOU?</h2>
            <p class="section-subtitle">We sign collaborative Memorandums of Understanding (MOUs) at zero cost to the school.</p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-light rounded-4 border h-100">
                    <i class="fas fa-stethoscope fa-2x text-primary mb-3"></i>
                    <h6 class="fw-bold mb-2">Bi-Annual Health Checks</h6>
                    <p class="small text-muted mb-0">Full physical checkups, height, weight, BMI, dental checkup & general vitals.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-light rounded-4 border h-100">
                    <i class="fas fa-eye fa-2x text-success mb-3"></i>
                    <h6 class="fw-bold mb-2">Student Eye Refraction</h6>
                    <p class="small text-muted mb-0">Detecting refractory errors early with free prescription eyeglasses provided.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-light rounded-4 border h-100">
                    <i class="fas fa-book-reader fa-2x text-warning mb-3"></i>
                    <h6 class="fw-bold mb-2">NCERT Study Kits</h6>
                    <p class="small text-muted mb-0">Free textbook guides, notebooks, geometry boxes, and bags for needy students.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-light rounded-4 border h-100">
                    <i class="fas fa-spa fa-2x text-info mb-3"></i>
                    <h6 class="fw-bold mb-2">Yoga & Stress Relief</h6>
                    <p class="small text-muted mb-0">Morning yoga classes and exam anxiety management workshops before boards.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Schools Directory Grid -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="row g-4">
            <?php foreach ($schools as $s): ?>
            <div class="col-lg-4 col-md-6">
                <div class="p-4 bg-white rounded-4 shadow-sm border h-100 d-flex flex-column">
                    <div class="school-logo-box text-center mb-3">
                        <img src="<?= e(getImageUrl($s['logo'], 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=200&q=80')); ?>" alt="<?= e($s['name']); ?>">
                    </div>
                    <h5 class="fw-bold fs-6 mb-2 text-dark"><?= e($s['name']); ?></h5>
                    <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($s['address']); ?>, <?= e($s['city']); ?>, <?= e($s['state']); ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                        <small class="text-muted"><i class="fas fa-calendar-check text-primary me-1"></i> MOU: <strong><?= formatDate($s['mou_date']); ?></strong></small>
                        <span class="badge bg-success"><i class="fas fa-users me-1"></i> <?= number_format((int)$s['students_benefited']); ?>+ Students</span>
                    </div>

                    <p class="small text-muted mb-3"><?= e(truncateText($s['description'], 110)); ?></p>

                    <div class="p-2 bg-light rounded small mb-4 flex-grow-1">
                        <strong>Active Interventions:</strong><br>
                        <span class="text-muted"><?= e($s['active_programs']); ?></span>
                    </div>

                    <div class="mt-auto d-flex gap-2">
                        <?php if (!empty($s['mou_document'])): ?>
                        <a href="<?= e(getImageUrl($s['mou_document'])); ?>" target="_blank" class="btn btn-sm btn-outline-danger w-100">
                            <i class="fas fa-file-pdf me-1"></i> View MOU Document
                        </a>
                        <?php else: ?>
                        <a href="<?= BASE_URL; ?>/contact.php?school=<?= urlencode($s['name']); ?>" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-envelope me-1"></i> Inquire Program
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

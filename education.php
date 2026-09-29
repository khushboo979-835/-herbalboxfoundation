<?php
/**
 * Education & NCERT Learning Support Page
 * Seva Shiksha Initiative
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

// Fetch education programs
$stmt = $pdo->prepare("SELECT * FROM programs WHERE type = 'education' AND status = 'active' ORDER BY sort_order ASC, id ASC");
$stmt->execute();
$educationPrograms = $stmt->fetchAll();

// Fetch school partners
$schoolsStmt = $pdo->query("SELECT * FROM schools WHERE status = 'active' ORDER BY students_benefited DESC LIMIT 6");
$schools = $schoolsStmt->fetchAll();

$pageTitle = 'Education Programs (Class 1st to 12th) - NCERT Learning & School Kits';
$pageDesc = 'Empowering school students with NCERT curriculum support, study materials, smart classrooms, career counseling, and health awareness.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-info-subtle text-info mb-2 px-3 py-1">Education For All</span>
        <h1 class="display-5 fw-bold text-white mb-3">Empowering Young Minds: Class 1st to 12th NCERT Learning</h1>
        <p class="lead text-white-50 max-w-700">Equipping underprivileged students with standardized NCERT study kits, digital smart learning, career mentorship, and holistic health awareness.</p>
        <div class="d-flex gap-3 mt-4">
            <a href="<?= BASE_URL; ?>/schools.php" class="btn btn-info fw-semibold text-dark"><i class="fas fa-school me-1"></i> Partner Schools Directory</a>
            <a href="<?= BASE_URL; ?>/partnerships.php?type=school" class="btn btn-outline-light"><i class="fas fa-file-signature me-1"></i> Sign School MOU</a>
        </div>
    </div>
</div>

<!-- Education Programs Grid -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Academic Interventions</span>
            <h2 class="section-title">Our Education Verticals</h2>
            <p class="section-subtitle">Structured interventions bridging the learning gap for students in government and affordable schools.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($educationPrograms as $prog): ?>
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border h-100 d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 bg-info-subtle text-primary rounded-3 fs-3">
                            <i class="fas <?= e($prog['icon'] ?? 'fa-graduation-cap'); ?>"></i>
                        </div>
                        <div>
                            <h3 class="fs-4 fw-bold mb-1"><?= e($prog['title']); ?></h3>
                            <span class="badge bg-light text-primary border">Target: Classes 1st - 12th</span>
                        </div>
                    </div>
                    <p class="text-muted mb-4"><?= e($prog['full_content'] ?: $prog['short_description']); ?></p>

                    <?php if (!empty($prog['features'])): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold fs-6 text-dark mb-2"><i class="fas fa-star text-warning me-1"></i> Program Pillars:</h6>
                        <ul class="list-unstyled mb-0">
                            <?php foreach (explode("\n", $prog['features']) as $feat): if (trim($feat)): ?>
                            <li class="small text-muted mb-1"><i class="fas fa-check-circle text-success me-2"></i> <?= e(trim($feat)); ?></li>
                            <?php endif; endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="small text-muted"><i class="fas fa-book-open text-info me-1"></i> NCERT Aligned</span>
                        <a href="<?= BASE_URL; ?>/donate.php?purpose=education" class="btn btn-sm btn-warning text-dark fw-bold"><i class="fas fa-gift me-1"></i> Sponsor a Student Kit</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 12-Year School Learning Lifecycle -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Curriculum Progression</span>
            <h2 class="section-title">Comprehensive 1st to 12th Roadmap</h2>
            <p class="section-subtitle">Tailored support suited for each developmental and academic phase.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 border rounded-4 bg-light h-100">
                    <span class="badge bg-primary mb-3">Primary Stage (Class 1 - 5)</span>
                    <h5 class="fw-bold mb-2">Foundational Literacy & Numeracy</h5>
                    <p class="small text-muted mb-3">Focus on early reading comprehension, basic mathematics, phonics, health & hygiene habits, and free school bags with essential stationery.</p>
                    <ul class="small text-muted ps-3 mb-0">
                        <li>Storybook & Picture Book Banks</li>
                        <li>Hygiene & Handwashing Workshops</li>
                        <li>Basic Mathematics Kits</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded-4 bg-light h-100">
                    <span class="badge bg-success mb-3">Middle Stage (Class 6 - 8)</span>
                    <h5 class="fw-bold mb-2">NCERT Science & Math Mastery</h5>
                    <p class="small text-muted mb-3">Hands-on science experiments, conceptual geometry kits, remedial doubt clearing, and introduction to daily yoga and digital literacy.</p>
                    <ul class="small text-muted ps-3 mb-0">
                        <li>STEM Practical Experiment Boxes</li>
                        <li>Remedial Weekend Classes</li>
                        <li>Student Eye & Dental Screenings</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded-4 bg-light h-100">
                    <span class="badge bg-warning text-dark mb-3">Secondary & Senior (Class 9 - 12)</span>
                    <h5 class="fw-bold mb-2">Board Exams & Career Launchpad</h5>
                    <p class="small text-muted mb-3">Intensive board examination guidance, NCERT exemplar problem-solving, psychometric career mapping, and government scholarship applications.</p>
                    <ul class="small text-muted ps-3 mb-0">
                        <li>Exam Stress Management & Meditation</li>
                        <li>Career Mentorship by Professionals</li>
                        <li>College Entrance & Scholarship Guidance</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- School Impact Showcase -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <span class="section-tag">School Collaborations</span>
                <h3 class="fw-bold mb-0">Featured Partner Schools</h3>
            </div>
            <a href="<?= BASE_URL; ?>/schools.php" class="btn btn-outline-primary btn-sm">Explore All Partner Schools <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($schools as $s): ?>
            <div class="col-lg-4 col-md-6">
                <div class="partner-card">
                    <div class="school-logo-box">
                        <img src="<?= e(getImageUrl($s['logo'], 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=200&q=80')); ?>" alt="<?= e($s['name']); ?>">
                    </div>
                    <h5 class="fw-bold fs-6 mb-1"><?= e($s['name']); ?></h5>
                    <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt text-primary me-1"></i> <?= e($s['city']); ?>, <?= e($s['state']); ?></p>
                    <div class="p-2 bg-light rounded text-center small mb-2">
                        <strong class="text-primary"><?= number_format((int)$s['students_benefited']); ?>+</strong> Students Benefited
                    </div>
                    <p class="small text-muted mb-0"><?= e(truncateText($s['active_programs'], 70)); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

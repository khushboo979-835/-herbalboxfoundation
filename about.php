<?php
/**
 * About Us Page
 * NGO Seva Foundation
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

// Fetch Team Members
$teamStmt = $pdo->query("SELECT * FROM team_members WHERE status = 'active' ORDER BY sort_order ASC, id ASC");
$teamMembers = $teamStmt->fetchAll();

// Fetch Certificates & Reports
$reportsStmt = $pdo->query("SELECT * FROM certificates_reports WHERE status = 'active' ORDER BY id DESC");
$reports = $reportsStmt->fetchAll();

$pageTitle = 'About Us - History, Mission, Vision & Legal Accreditations';
$pageDesc = 'Discover Seva Foundation\'s journey, founding trustees, NITI Aayog registration, 12A/80G tax exemptions, and annual audit reports.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Page Banner Header -->
<div class="bg-dark text-white py-5 position-relative hero-gradient-dark">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill fw-bold"><i class="fas fa-certificate me-1"></i> CIN: U86901BR2026NPL087665</span>
        <h1 class="display-5 fw-bold text-white mb-3">Herbalbox Foundation</h1>
        <p class="lead text-white-50 max-w-700">Working Together for a Better Tomorrow through Community Healthcare, NCERT Education, and Sustainable Social Empowerment.</p>
    </div>
</div>

<!-- History & Core Philosophy -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="section-tag">About Our Organization</span>
                <h2 class="section-title mb-4">Dedicated to Sustainable Social Development & Community Welfare</h2>
                <p class="text-muted mb-3">
                    <strong>Herbalbox Foundation</strong> is a professionally established organization incorporated under the <strong>Companies Act, 2013</strong>, with its registered office in <strong>Patna, Bihar</strong>.
                </p>
                <p class="text-muted mb-3">
                    We are committed to creating meaningful social impact through community welfare, awareness, social development, empowerment and sustainable initiatives. Our mission is to work with communities and contribute towards building a more inclusive, responsible and empowered society.
                </p>
                <p class="text-muted mb-4">
                    With an operational center in <strong>Hajipur (Near Birla Open Minds International School, Konhara Road)</strong>, we organize regular free health camps, eye & dental checkups, NCERT smart digital education drives, and holistic AYUSH wellness programs.
                </p>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <h5 class="fw-bold text-success mb-1"><i class="fas fa-calendar-check me-2"></i>29 August 2026</h5>
                            <small class="text-muted fw-semibold">Incorporation Date</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <h5 class="fw-bold text-primary mb-1"><i class="fas fa-landmark me-2"></i>Patna, Bihar</h5>
                            <small class="text-muted fw-semibold">Registered Office</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="p-4 bg-white rounded-4 shadow-lg border hover-lift">
                    <img src="<?= BASE_URL; ?>/assets/images/logo.png" alt="Herbalbox Foundation" class="img-fluid rounded-4 mb-3" style="max-height: 300px; width: auto; object-fit: contain;">
                    <h4 class="fw-bold mb-1">HERBALBOX FOUNDATION</h4>
                    <p class="text-success fw-bold text-uppercase small mb-2">Health • Education • Better Tomorrow</p>
                    <p class="small text-muted mb-0">CIN: <strong>U86901BR2026NPL087665</strong> | Company Limited by Guarantee</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission, Vision & Core Values -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="section-header text-center mb-5">
            <span class="section-tag">Our Guiding Light</span>
            <h2 class="section-title">Mission, Vision & Principles</h2>
            <p class="section-subtitle">Guiding our humanitarian action across healthcare, education, and rural development.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-box h-100 p-4 bg-white rounded-4 shadow-sm hover-lift border">
                    <div class="feature-icon mb-3 text-primary fs-3"><i class="fas fa-bullseye"></i></div>
                    <h4 class="fw-bold mb-3">Our Mission</h4>
                    <p class="text-muted mb-0">To work with communities and contribute towards building a more inclusive, responsible and empowered society through accessible healthcare, quality education, and sustainable initiatives.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box h-100 p-4 bg-white rounded-4 shadow-sm hover-lift border">
                    <div class="feature-icon mb-3 text-success fs-3"><i class="fas fa-eye"></i></div>
                    <h4 class="fw-bold mb-3">Our Vision</h4>
                    <p class="text-muted mb-0">To serve society with integrity, compassion and responsibility while creating opportunities for positive and sustainable change.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box h-100 p-4 bg-white rounded-4 shadow-sm hover-lift border">
                    <div class="feature-icon mb-3 text-warning fs-3"><i class="fas fa-shield-heart"></i></div>
                    <h4 class="fw-bold mb-3">Core Values</h4>
                    <ul class="text-muted small ps-3 mb-0">
                        <li class="mb-2"><strong>Integrity & Compassion:</strong> Respect and care for every beneficiary.</li>
                        <li class="mb-2"><strong>Responsibility:</strong> Transparent, accountable community service.</li>
                        <li class="mb-2"><strong>Inclusiveness:</strong> Equal access to health and education for all.</li>
                        <li class="mb-0"><strong>Holistic Wellness:</strong> Modern healthcare paired with AYUSH care.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statutory Registrations & Legal Information -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="section-header text-center mb-5">
            <span class="section-tag">Governance & Compliance</span>
            <h2 class="section-title">Corporate & Statutory Registrations</h2>
            <p class="section-subtitle">Incorporated under the Companies Act, 2013 (Ministry of Corporate Affairs, Govt of India).</p>
        </div>

        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100 hover-lift">
                    <i class="fas fa-id-card fa-2x text-primary mb-3"></i>
                    <h6 class="fw-bold mb-1">Corporate CIN</h6>
                    <span class="badge bg-dark px-3 py-2 fs-6">U86901BR2026NPL087665</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100 hover-lift">
                    <i class="fas fa-building-columns fa-2x text-success mb-3"></i>
                    <h6 class="fw-bold mb-1">Legal Status</h6>
                    <span class="badge bg-success px-3 py-2">Company Limited by Guarantee</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100 hover-lift">
                    <i class="fas fa-map-location-dot fa-2x text-info mb-3"></i>
                    <h6 class="fw-bold mb-1">Registered Office</h6>
                    <span class="badge bg-info text-dark px-3 py-2">Patna, Bihar, India</span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100 hover-lift">
                    <i class="fas fa-calendar-check fa-2x text-warning mb-3"></i>
                    <h6 class="fw-bold mb-1">Incorporated On</h6>
                    <span class="badge bg-warning text-dark px-3 py-2">29 August 2026</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership & Management Team -->
<?php if (!empty($teamMembers)): ?>
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Meet the Changemakers</span>
            <h2 class="section-title">Board of Trustees & Leadership</h2>
            <p class="section-subtitle">A team of dedicated physicians, senior academicians, and social welfare leaders.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($teamMembers as $member): ?>
            <div class="col-lg-3 col-md-6">
                <div class="partner-card">
                    <img src="<?= e(getImageUrl($member['photo'], 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&q=80')); ?>" alt="<?= e($member['name']); ?>" class="doctor-photo">
                    <h5 class="fw-bold mb-1 fs-6"><?= e($member['name']); ?></h5>
                    <p class="small text-primary fw-semibold mb-2"><?= e($member['designation']); ?></p>
                    <p class="small text-muted mb-3"><?= e(truncateText($member['bio'], 100)); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Annual Reports & Audit Certificates -->
<?php if (!empty($reports)): ?>
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Financial Transparency</span>
            <h2 class="section-title">Annual Reports & Audited Financials</h2>
            <p class="section-subtitle">Download our transparent annual audits and utilization statements.</p>
        </div>

        <div class="row g-3">
            <?php foreach ($reports as $report): ?>
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-file-pdf fa-2x text-danger"></i>
                        <div>
                            <h6 class="mb-0 fw-bold"><?= e($report['title']); ?></h6>
                            <small class="text-muted">FY: <?= e($report['financial_year'] ?? 'Latest'); ?> | <?= e(ucfirst($report['type'])); ?></small>
                        </div>
                    </div>
                    <a href="<?= e(getImageUrl($report['file_path'])); ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-download me-1"></i> PDF
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

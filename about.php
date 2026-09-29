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
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #042f2e 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-1">About Our Foundation</span>
        <h1 class="display-5 fw-bold text-white mb-3">Serving Humanity with Dignity, Science & Compassion</h1>
        <p class="lead text-white-50 max-w-700">Dedicated to eliminating healthcare poverty and educational backwardness across India through grassroots action and institutional partnerships.</p>
    </div>
</div>

<!-- History & Core Philosophy -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="section-tag">Our Founding Story</span>
                <h2 class="section-title mb-4">Transforming Grassroots Lives Since 2018</h2>
                <p class="text-muted mb-3">
                    <?= nl2br(e(getSetting('about_history', 'Established in 2018 by a consortium of philanthropic doctors and educationists, Seva Foundation began as a small mobile clinic. Today, we run 150+ free healthcare camps annually, partner with 45+ premier schools, and empower thousands of families with life-saving treatments and holistic wellness.'))); ?>
                </p>
                <p class="text-muted mb-4">
                    Every program we launch adheres to stringent ethical medical standards, scientific NCERT pedagogies, and full regulatory transparency under Indian charity laws.
                </p>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3">
                            <h4 class="fw-bold text-primary mb-1">100%</h4>
                            <small class="text-muted fw-semibold">Free Medical Checkups & Medicines</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3">
                            <h4 class="fw-bold text-success mb-1">65+</h4>
                            <small class="text-muted fw-semibold">Signed School & Hospital MOUs</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=800&q=80" alt="About Seva Foundation" class="img-fluid rounded-4 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Mission, Vision & Core Values -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Our Guiding Light</span>
            <h2 class="section-title">Mission, Vision & Core Values</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-bullseye"></i></div>
                    <h4 class="fw-bold mb-3">Our Mission</h4>
                    <p class="text-muted mb-0"><?= e(getSetting('about_mission', 'To provide free high-quality healthcare, preventive medical camps, NCERT-based education support, AYUSH healing, and youth empowerment to underprivileged communities across India.')); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon text-success"><i class="fas fa-eye text-success"></i></div>
                    <h4 class="fw-bold mb-3">Our Vision</h4>
                    <p class="text-muted mb-0"><?= e(getSetting('about_vision', 'A compassionate, enlightened, and healthy India where no child is deprived of education and no individual is denied timely medical care due to socioeconomic barriers.')); ?></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-box">
                    <div class="feature-icon text-warning"><i class="fas fa-shield-heart text-warning"></i></div>
                    <h4 class="fw-bold mb-3">Core Values</h4>
                    <ul class="text-muted small ps-3 mb-0">
                        <li class="mb-1"><strong>Empathy & Dignity:</strong> Respect for all beneficiaries</li>
                        <li class="mb-1"><strong>Total Transparency:</strong> Audited 80G financials</li>
                        <li class="mb-1"><strong>Clinical Integrity:</strong> Certified doctor consultations</li>
                        <li class="mb-1"><strong>Holistic Care:</strong> Modern + AYUSH integration</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statutory Registrations & Legal Information -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Governance & Compliance</span>
            <h2 class="section-title">Statutory Legal Registrations</h2>
            <p class="section-subtitle">We are fully registered with statutory bodies and maintain absolute regulatory compliance.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-id-card fa-2x text-primary mb-3"></i>
                    <h6 class="fw-bold mb-1">Society / Trust Reg</h6>
                    <span class="badge bg-dark"><?= e(getSetting('ngo_reg_number', 'REG/NGO/2018/88741/DELHI')); ?></span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-hand-holding-usd fa-2x text-success mb-3"></i>
                    <h6 class="fw-bold mb-1">80G Tax Exemption</h6>
                    <span class="badge bg-success"><?= e(getSetting('tax_exemption_80g', '80G-CIT(E)/DEL/2019-20')); ?></span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-file-invoice fa-2x text-info mb-3"></i>
                    <h6 class="fw-bold mb-1">12A Income Tax</h6>
                    <span class="badge bg-info text-dark"><?= e(getSetting('tax_exemption_12a', '12A-CIT(E)/DEL/2018-19')); ?></span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 border rounded-4 text-center bg-light h-100">
                    <i class="fas fa-building-columns fa-2x text-warning mb-3"></i>
                    <h6 class="fw-bold mb-1">NITI Aayog Darpan</h6>
                    <span class="badge bg-warning text-dark"><?= e(getSetting('niti_aayog_id', 'DL/2018/0192847')); ?></span>
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

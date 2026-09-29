<?php
/**
 * Healthcare Programs & Medical Services Page
 * Seva Arogya Foundation
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$healthcarePrograms = [];

try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $stmt = $pdo->query("SELECT * FROM healthcare_services WHERE status = 'published' ORDER BY sort_order ASC, id ASC");
        $healthcarePrograms = $stmt ? $stmt->fetchAll() : [];
    }
} catch (Throwable $e) {
    error_log("Healthcare Query Notice: " . $e->getMessage());
}

$pageTitle = 'Healthcare Services, Free Medical Camps & Diagnostic Assistance | Herbalbox Foundation';
$pageDesc = 'Comprehensive primary healthcare, blood donation camps, free eye surgery, dental screenings, and essential medicine distribution across India.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-success-subtle text-success mb-2 px-3 py-1">Grassroots Healthcare</span>
        <h1 class="display-5 fw-bold text-white mb-3">Accessible, Ethical & High-Quality Healthcare for All</h1>
        <p class="lead text-white-50 max-w-700">From free rural mega medical camps to specialized cataract surgeries and diagnostics, we bridge the gap between quality medicine and underprivileged families.</p>
        <div class="d-flex gap-3 mt-4">
            <a href="<?= BASE_URL; ?>/medical-camps.php" class="btn btn-success fw-semibold"><i class="fas fa-calendar-check me-1"></i> View Camp Schedule</a>
            <a href="<?= BASE_URL; ?>/blood-donation.php" class="btn btn-danger fw-semibold"><i class="fas fa-tint me-1"></i> Blood Donation Drives</a>
        </div>
    </div>
</div>

<!-- Healthcare Services Grid -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Clinical Initiatives</span>
            <h2 class="section-title">Our Healthcare Verticals</h2>
            <p class="section-subtitle">Delivered by qualified medical practitioners and certified partner hospitals.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($healthcarePrograms as $prog): ?>
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border h-100 d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 bg-primary-subtle text-primary rounded-3 fs-3">
                            <i class="fas <?= e($prog['icon'] ?? 'fa-stethoscope'); ?>"></i>
                        </div>
                        <div>
                            <h3 class="fs-4 fw-bold mb-1"><?= e($prog['title']); ?></h3>
                            <span class="badge bg-light text-muted border"><?= e(strtoupper($prog['type'])); ?></span>
                        </div>
                    </div>
                    <p class="text-muted mb-4"><?= e($prog['full_content'] ?: $prog['short_description']); ?></p>
                    
                    <?php if (!empty($prog['features'])): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold fs-6 text-dark mb-2"><i class="fas fa-check-double text-success me-1"></i> Key Highlights:</h6>
                        <ul class="list-unstyled mb-0">
                            <?php foreach (explode("\n", $prog['features']) as $feat): if (trim($feat)): ?>
                            <li class="small text-muted mb-1"><i class="fas fa-check text-primary me-2"></i> <?= e(trim($feat)); ?></li>
                            <?php endif; endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="small text-muted"><i class="fas fa-user-shield text-success me-1"></i> 100% Free for BPL Families</span>
                        <a href="<?= BASE_URL; ?>/contact.php?subject=<?= urlencode('Inquiry for ' . $prog['title']); ?>" class="btn btn-sm btn-outline-primary">Request Medical Camp</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Diagnostic Testing & Advanced Assistance -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="section-tag">Partner Diagnostic Network</span>
                <h2 class="section-title mb-4">Subsidized & Free Diagnostic Services</h2>
                <p class="text-muted mb-4">Early and accurate diagnosis saves lives. Through our institutional MOUs with certified pathology laboratories and radiology centers, we provide low-cost and fully sponsored diagnostic screenings.</p>
                
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <i class="fas fa-vial text-primary mb-2"></i>
                            <h6 class="fw-bold mb-1">Pathology Tests</h6>
                            <small class="text-muted">CBC, Lipid Profile, LFT, KFT, HbA1c & Blood Sugar</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <i class="fas fa-x-ray text-primary mb-2"></i>
                            <h6 class="fw-bold mb-1">Radiology & Imaging</h6>
                            <small class="text-muted">Digital X-Ray, Ultrasound, ECG & Referral CT/MRI</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <i class="fas fa-eye text-primary mb-2"></i>
                            <h6 class="fw-bold mb-1">Ophthalmic Tests</h6>
                            <small class="text-muted">Visual Acuity, Auto-Refraction, Glaucoma & Retina</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <i class="fas fa-pills text-primary mb-2"></i>
                            <h6 class="fw-bold mb-1">Medicine Bank</h6>
                            <small class="text-muted">Essential antibiotics, analgesics & daily supplements</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-light rounded-4 border">
                    <h4 class="fw-bold mb-3">Request a Medical Camp in Your Area</h4>
                    <p class="small text-muted mb-4">Are you a school principal, village head (Sarpanch), RWA secretary, or CSR leader? Partner with us to host a free medical checkup camp.</p>
                    <form action="<?= BASE_URL; ?>/partnerships.php" method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Select Organization Type</label>
                            <select name="type" class="form-select">
                                <option value="school">School / College</option>
                                <option value="community">Village Panchayat / RWA</option>
                                <option value="csr_corporate">Corporate CSR Team</option>
                                <option value="hospital">Hospital / Diagnostic Lab</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-ngo-primary w-100">Apply to Organize Camp</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Medical Disclaimer -->
<div class="container pb-5">
    <div class="alert alert-secondary text-center small mb-0">
        <i class="fas fa-info-circle me-1"></i> <strong>Medical Disclaimer:</strong> Seva Foundation does not provide self-treatment advice. All treatments, medications, and surgical referrals are strictly conducted by licensed medical practitioners from our partner institutions.
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

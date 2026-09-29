<?php
/**
 * AYUSH & Multi-System Medical Care
 * Ayurveda, Homeopathy, Allopathy & Holistic Healing
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

// Fetch AYUSH doctors
$doctorsStmt = $pdo->query("SELECT * FROM doctors WHERE status = 'active' AND treatment_type IN ('ayurvedic', 'homeopathic', 'allopathic') ORDER BY is_featured DESC, id ASC");
$doctors = $doctorsStmt->fetchAll();

// Fetch AYUSH programs
$programsStmt = $pdo->query("SELECT * FROM programs WHERE type = 'ayush' AND status = 'active' ORDER BY sort_order ASC");
$ayushPrograms = $programsStmt->fetchAll();

$pageTitle = 'AYUSH & Medical Care - Ayurveda, Homeopathy & Allopathic Treatment';
$pageDesc = 'Discover integrated medical treatments combining authentic Ayurveda, gentle Homeopathy, and evidence-based Allopathy for holistic community health.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #134e4a 0%, #064e3b 100%);">
    <div class="container py-4">
        <span class="badge bg-success text-white mb-2 px-3 py-1">Holistic Healthcare</span>
        <h1 class="display-5 fw-bold text-white mb-3">Integrative Healing: Ayurveda, Homeopathy & Allopathy</h1>
        <p class="lead text-white-50 max-w-700">Harmonizing ancient Indian medical wisdom with modern clinical diagnosis to offer personalized, affordable, and side-effect-free healing.</p>
        <div class="d-flex gap-3 mt-4">
            <a href="<?= BASE_URL; ?>/doctors.php" class="btn btn-warning text-dark fw-bold"><i class="fas fa-user-md me-1"></i> Consult AYUSH Vaidyas & Doctors</a>
            <a href="<?= BASE_URL; ?>/products.php" class="btn btn-outline-light"><i class="fas fa-pills me-1"></i> Ayurvedic Herbal Store</a>
        </div>
    </div>
</div>

<!-- Three Systems of Care Tabs/Cards -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="section-header">
            <span class="section-tag">Tri-Pillar Healthcare</span>
            <h2 class="section-title">The Three Complementary Systems of Healing</h2>
            <p class="section-subtitle">We believe in a patient-first integrative approach where the most suitable system of medicine is utilized for lasting relief.</p>
        </div>

        <div class="row g-4">
            <!-- 1. Ayurveda -->
            <div class="col-lg-4">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border border-success h-100 d-flex flex-column">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle p-3 mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-leaf fa-2x"></i>
                    </div>
                    <h3 class="fs-4 fw-bold mb-2 text-success">Ayurvedic Healing</h3>
                    <p class="text-muted small mb-3">Time-tested 5,000-year-old traditional Indian medical science emphasizing dosha balance (Vata, Pitta, Kapha) and root-cause eradication.</p>
                    
                    <h6 class="fw-bold fs-6 mb-2">Key Specialties:</h6>
                    <ul class="small text-muted ps-3 mb-4 flex-grow-1">
                        <li>Authentic Nadi Pariksha (Pulse Diagnosis)</li>
                        <li>Chronic Arthritis & Joint Pain Management</li>
                        <li>Gastrointestinal & Liver Detox Therapies</li>
                        <li>Herbal Immunity Formulations (Kwath)</li>
                    </ul>
                    <a href="<?= BASE_URL; ?>/doctors.php?type=ayurvedic" class="btn btn-outline-success w-100">Meet Ayurvedic Vaidyas</a>
                </div>
            </div>

            <!-- 2. Homeopathy -->
            <div class="col-lg-4">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border border-primary h-100 d-flex flex-column">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle p-3 mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-pills fa-2x"></i>
                    </div>
                    <h3 class="fs-4 fw-bold mb-2 text-primary">Homeopathic Care</h3>
                    <p class="text-muted small mb-3">Gentle, constitutional, and safe therapeutics suitable for children, pregnant mothers, and elderly patients without adverse reactions.</p>
                    
                    <h6 class="fw-bold fs-6 mb-2">Key Specialties:</h6>
                    <ul class="small text-muted ps-3 mb-4 flex-grow-1">
                        <li>Pediatric Immunity & Recurring Cough/Cold</li>
                        <li>Skin Ailments (Eczema, Psoriasis, Allergies)</li>
                        <li>Respiratory Disorders & Bronchial Asthma</li>
                        <li>Stress, Insomnia & Chronic Migraine</li>
                    </ul>
                    <a href="<?= BASE_URL; ?>/doctors.php?type=homeopathic" class="btn btn-outline-primary w-100">Meet Homeopaths</a>
                </div>
            </div>

            <!-- 3. Allopathy -->
            <div class="col-lg-4">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border border-info h-100 d-flex flex-column">
                    <div class="d-inline-flex align-items-center justify-content-center bg-info text-white rounded-circle p-3 mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-stethoscope fa-2x"></i>
                    </div>
                    <h3 class="fs-4 fw-bold mb-2 text-info">Allopathic Medicine</h3>
                    <p class="text-muted small mb-3">Modern evidence-based clinical diagnostics, emergency response, surgical referrals, and advanced diagnostic testing partnerships.</p>
                    
                    <h6 class="fw-bold fs-6 mb-2">Key Specialties:</h6>
                    <ul class="small text-muted ps-3 mb-4 flex-grow-1">
                        <li>General Physical Examination & Vitals</li>
                        <li>Cardiovascular & Hypertension Screening</li>
                        <li>Diabetes Management & Insulin Counseling</li>
                        <li>Hospital Referral for Surgeries & Critical Care</li>
                    </ul>
                    <a href="<?= BASE_URL; ?>/doctors.php?type=allopathic" class="btn btn-outline-info w-100">Meet Allopathic Doctors</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AYUSH Doctor Directory Preview -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <span class="section-tag">Practitioner Panel</span>
                <h3 class="fw-bold mb-0">Our Certified AYUSH & Medical Practitioners</h3>
            </div>
            <a href="<?= BASE_URL; ?>/doctors.php" class="btn btn-outline-primary btn-sm">Full Doctor Directory <i class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($doctors as $doc): ?>
            <div class="col-lg-3 col-md-6">
                <div class="partner-card">
                    <img src="<?= e(getImageUrl($doc['photo'], 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=300&q=80')); ?>" alt="<?= e($doc['name']); ?>" class="doctor-photo">
                    <h5 class="fw-bold fs-6 mb-1"><?= e($doc['name']); ?></h5>
                    <p class="small text-primary fw-semibold mb-1"><?= e($doc['specialization']); ?></p>
                    <p class="small text-muted mb-2"><?= e($doc['qualification']); ?></p>
                    <span class="badge bg-secondary-subtle text-secondary mb-3"><?= strtoupper($doc['treatment_type']); ?></span>
                    <p class="small text-muted mb-3"><i class="fas fa-clock text-primary me-1"></i> <?= e($doc['available_days']); ?></p>
                    <a href="<?= BASE_URL; ?>/contact.php?doctor=<?= urlencode($doc['name']); ?>" class="btn btn-sm btn-ngo-outline w-100">Book Free OPD Slot</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

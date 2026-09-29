<?php
/**
 * Partner Doctor Network Directory
 * Seva Health Panel
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$doctors = [];

try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $treatmentFilter = sanitize($_GET['type'] ?? '');
        $specializationSearch = sanitize($_GET['search'] ?? '');

        $sql = "SELECT d.*, h.name as hospital_name FROM doctors d LEFT JOIN hospitals h ON d.hospital_id = h.id WHERE d.status = 'published'";
        $params = [];

        if (!empty($treatmentFilter)) {
            $sql .= " AND (d.specialization LIKE ? OR d.bio LIKE ?)";
            $params[] = "%{$treatmentFilter}%";
            $params[] = "%{$treatmentFilter}%";
        }

        if (!empty($specializationSearch)) {
            $sql .= " AND (d.name LIKE ? OR d.specialization LIKE ? OR d.city LIKE ?)";
            $term = "%{$specializationSearch}%";
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY d.is_featured DESC, d.id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $doctors = $stmt->fetchAll();
    }
} catch (Throwable $e) {
    error_log("Doctors Query Notice: " . $e->getMessage());
}

$pageTitle = 'Partner Doctors Directory - Specialists & AYUSH Vaidyas';
$pageDesc = 'Meet our network of qualified physicians, eye surgeons, pediatricians, Ayurvedic vaidyas, and homeopaths volunteering across Seva Foundation camps.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #0d9488 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-1">Medical Network</span>
        <h1 class="display-5 fw-bold text-white mb-3">Distinguished Partner Doctors & Specialists</h1>
        <p class="lead text-white-50 max-w-700">Dedicated medical practitioners offering their clinical expertise for free consultations, surgical referrals, and community healthcare camps.</p>
        <div class="d-flex gap-3 mt-4">
            <a href="<?= BASE_URL; ?>/partnerships.php?type=doctor" class="btn btn-warning text-dark fw-bold"><i class="fas fa-hand-holding-medical me-1"></i> Join as a Volunteer Doctor</a>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <form action="<?= BASE_URL; ?>/doctors.php" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search doctor by name, specialty or city..." value="<?= e($specializationSearch); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="type" class="form-select">
                    <option value="">-- All Medical Systems --</option>
                    <option value="allopathic" <?= $treatmentFilter === 'allopathic' ? 'selected' : ''; ?>>Allopathic Medicine</option>
                    <option value="ayurvedic" <?= $treatmentFilter === 'ayurvedic' ? 'selected' : ''; ?>>Ayurveda (AYUSH)</option>
                    <option value="homeopathic" <?= $treatmentFilter === 'homeopathic' ? 'selected' : ''; ?>>Homeopathy (AYUSH)</option>
                    <option value="yoga_wellness" <?= $treatmentFilter === 'yoga_wellness' ? 'selected' : ''; ?>>Yoga & Wellness</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-ngo-primary w-100">Filter Doctors</button>
                <a href="<?= BASE_URL; ?>/doctors.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</section>

<!-- Doctor Grid -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <?php if (empty($doctors)): ?>
        <div class="text-center py-5 bg-white rounded-4 border">
            <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
            <h5>No doctors found matching your criteria</h5>
            <p class="text-muted">Try clearing your filters or search for another specialty.</p>
            <a href="<?= BASE_URL; ?>/doctors.php" class="btn btn-outline-primary">View All Doctors</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($doctors as $doc): ?>
            <div class="col-lg-3 col-md-6">
                <div class="partner-card">
                    <img src="<?= e(getImageUrl($doc['photo'], 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=300&q=80')); ?>" alt="<?= e($doc['name']); ?>" class="doctor-photo">
                    <h5 class="fw-bold fs-6 mb-1"><?= e($doc['name']); ?></h5>
                    <p class="small text-primary fw-semibold mb-1"><?= e($doc['specialization']); ?></p>
                    <p class="small text-muted mb-2"><?= e($doc['qualification']); ?> (<?= (int)$doc['experience_years']; ?>+ Yrs Exp)</p>
                    
                    <div class="badge bg-secondary-subtle text-secondary mb-3">
                        <?= strtoupper(str_replace('_', ' ', $doc['treatment_type'])); ?>
                    </div>

                    <div class="p-2 bg-light rounded text-start small mb-3">
                        <i class="fas fa-hospital text-primary me-1"></i> <?= e($doc['clinic_hospital_name'] ?? $doc['hospital_name'] ?? 'Affiliated Partner Clinic'); ?><br>
                        <i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($doc['city']); ?>, <?= e($doc['state']); ?><br>
                        <i class="fas fa-calendar-check text-success me-1"></i> <?= e($doc['available_days']); ?>
                    </div>

                    <a href="<?= BASE_URL; ?>/contact.php?doctor=<?= urlencode($doc['name']); ?>" class="btn btn-sm btn-ngo-outline w-100">
                        <i class="fas fa-envelope me-1"></i> Consultation Inquiry
                    </a>
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

<?php
/**
 * Partner Hospitals & Diagnostic Centres Directory
 * Seva Clinical Partner Network
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

// Fetch hospitals and diagnostic centers
$typeFilter = sanitize($_GET['type'] ?? '');
$sql = "SELECT * FROM hospitals WHERE status = 'active'";
$params = [];

if (!empty($typeFilter)) {
    $sql .= " AND type = ?";
    $params[] = $typeFilter;
}
$sql .= " ORDER BY is_featured DESC, id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$hospitals = $stmt->fetchAll();

$pageTitle = 'Hospital & Diagnostic Partners - Subsidized Care & Surgery Network';
$pageDesc = 'Explore our institutional hospital network providing subsidized surgeries, emergency ICU reservations, and discounted diagnostic pathology/radiology tests.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #0369a1 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-info-subtle text-info mb-2 px-3 py-1">Healthcare Infrastructure</span>
        <h1 class="display-5 fw-bold text-white mb-3">Partner Hospitals & Diagnostic Centres</h1>
        <p class="lead text-white-50 max-w-700">Connecting underprivileged patients with accredited tertiary care hospitals, surgical units, CT/MRI diagnostic labs, and 24/7 blood banks.</p>
        <div class="d-flex gap-3 mt-4">
            <a href="<?= BASE_URL; ?>/partnerships.php?type=hospital" class="btn btn-warning text-dark fw-bold"><i class="fas fa-handshake me-1"></i> Partner with Us (MOU)</a>
        </div>
    </div>
</div>

<!-- Type Filter -->
<div class="bg-white py-3 border-bottom">
    <div class="container d-flex flex-wrap gap-2">
        <a href="<?= BASE_URL; ?>/hospitals.php" class="btn btn-sm <?= empty($typeFilter) ? 'btn-primary' : 'btn-outline-secondary'; ?>">All Partners</a>
        <a href="<?= BASE_URL; ?>/hospitals.php?type=hospital" class="btn btn-sm <?= $typeFilter === 'hospital' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Hospitals & Clinics</a>
        <a href="<?= BASE_URL; ?>/hospitals.php?type=diagnostic_center" class="btn btn-sm <?= $typeFilter === 'diagnostic_center' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Diagnostic & Imaging Labs</a>
    </div>
</div>

<!-- Hospital Listing -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="row g-4">
            <?php foreach ($hospitals as $h): ?>
            <div class="col-lg-6">
                <div class="p-4 p-md-5 bg-white rounded-4 shadow-sm border h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary-subtle text-primary"><?= strtoupper(str_replace('_', ' ', $h['type'])); ?></span>
                        <?php if (!empty($h['mou_signed_date'])): ?>
                        <span class="small text-success"><i class="fas fa-file-contract me-1"></i> MOU Signed: <?= formatDate($h['mou_signed_date']); ?></span>
                        <?php endif; ?>
                    </div>
                    <h4 class="fw-bold mb-2"><?= e($h['name']); ?></h4>
                    <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= e($h['address']); ?>, <?= e($h['city']); ?>, <?= e($h['state']); ?></p>
                    <p class="text-muted small mb-3"><i class="fas fa-phone-alt text-primary me-1"></i> <?= e($h['phone']); ?> | <i class="fas fa-envelope text-primary me-1"></i> <?= e($h['email']); ?></p>
                    
                    <div class="p-3 bg-light rounded-3 mb-3 small">
                        <strong>Services & Diagnostic Facilities:</strong><br>
                        <span class="text-muted"><?= e($h['services_offered']); ?></span>
                    </div>

                    <?php if (!empty($h['partnership_details'])): ?>
                    <p class="small text-muted mb-4 flex-grow-1"><strong>MOU Scope:</strong> <?= e($h['partnership_details']); ?></p>
                    <?php endif; ?>

                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                        <?php if (!empty($h['website'])): ?>
                        <a href="<?= e($h['website']); ?>" target="_blank" class="small text-primary text-decoration-none"><i class="fas fa-external-link-alt me-1"></i> Visit Website</a>
                        <?php else: ?>
                        <span></span>
                        <?php endif; ?>
                        <a href="<?= BASE_URL; ?>/contact.php?subject=<?= urlencode('Referral Inquiry for ' . $h['name']); ?>" class="btn btn-sm btn-outline-primary">Referral Assistance</a>
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

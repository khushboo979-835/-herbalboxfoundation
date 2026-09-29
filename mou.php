<?php
/**
 * Public MOU Archive & Transparency Portal
 * Seva Foundation Governance
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$mous = [];

try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $stmt = $pdo->query("SELECT * FROM mous WHERE status = 'published' ORDER BY start_date DESC");
        $mous = $stmt ? $stmt->fetchAll() : [];
    }
} catch (Throwable $e) {
    error_log("MOU Query Notice: " . $e->getMessage());
}

$pageTitle = 'Public MOU Archive - Formal Accreditations & Partnerships';
$pageDesc = 'Review and download public Memorandums of Understanding (MOUs) signed between Seva Foundation, schools, hospitals, and corporate CSR entities.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-1"><i class="fas fa-file-contract me-1"></i> Transparent Governance</span>
        <h1 class="display-5 fw-bold text-white mb-3">Public Memorandums of Understanding (MOUs)</h1>
        <p class="lead text-white-50 max-w-700">In accordance with our commitment to transparency, published below are approved institutional partnership documents, scopes of work, and formal covenants.</p>
    </div>
</div>

<!-- MOUs Table / Cards -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <?php if (empty($mous)): ?>
        <div class="p-5 bg-white rounded-4 border text-center">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h5>No public MOUs available for viewing at this time.</h5>
            <p class="text-muted">Contact our compliance officer for institutional verification.</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($mous as $mou): ?>
            <div class="col-lg-6">
                <div class="p-4 bg-white rounded-4 shadow-sm border h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary-subtle text-primary"><?= strtoupper($mou['partner_type']); ?> MOU</span>
                        <small class="text-muted"><i class="fas fa-calendar-check text-success me-1"></i> Signed: <strong><?= formatDate($mou['signed_date']); ?></strong></small>
                    </div>
                    <h4 class="fw-bold fs-5 mb-2"><?= e($mou['title']); ?></h4>
                    <p class="text-primary fw-semibold small mb-2"><i class="fas fa-handshake me-1"></i> Partner: <?= e($mou['partner_name']); ?></p>
                    
                    <div class="p-3 bg-light rounded-3 mb-3 small flex-grow-1">
                        <strong>Key Objectives:</strong>
                        <p class="text-muted mb-2"><?= e($mou['key_objectives']); ?></p>
                        <?php if (!empty($mou['scope_of_work'])): ?>
                        <strong>Scope of Work:</strong>
                        <p class="text-muted mb-0"><?= e($mou['scope_of_work']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <small class="text-muted">Valid Upto: <?= !empty($mou['valid_upto']) ? formatDate($mou['valid_upto']) : 'Perpetual'; ?></small>
                        <a href="<?= e(getImageUrl($mou['document_file'])); ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i> View / Download Document
                        </a>
                    </div>
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

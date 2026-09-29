<?php
/**
 * SEO Settings Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $seo = $_POST['seo'] ?? [];

    $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_group) VALUES (?, ?, 'seo') ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    foreach ($seo as $k => $v) {
        $stmt->execute([sanitize($k), sanitize($v)]);
    }

    logActivity('Updated SEO Settings', 'seo');
    setFlashMessage('success', 'Search Engine Optimization (SEO) settings updated.');
    header('Location: ' . ADMIN_URL . '/seo/index.php');
    exit;
}

$adminTitle = 'Search Engine Optimization (SEO)';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">SEO & Social Meta Configuration</h4>
                <p class="text-muted small mb-0">Control meta titles, descriptions, focus keywords, Open Graph, and Google Search Console tags.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="card-admin max-w-850">
            <div class="card-header"><i class="fas fa-search-dollar text-primary me-2"></i> Global Search Engine Tags</div>
            <div class="p-4">
                <form action="<?= ADMIN_URL; ?>/seo/index.php" method="POST" class="form-custom">
                    <?= getCsrfInput(); ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Default Meta Title *</label>
                        <input type="text" name="seo[seo_meta_title]" class="form-control" required value="<?= e(getSetting('seo_meta_title')); ?>">
                        <small class="text-muted">Recommended length: 50 - 60 characters</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Default Meta Description *</label>
                        <textarea name="seo[seo_meta_description]" rows="3" class="form-control" required><?= e(getSetting('seo_meta_description')); ?></textarea>
                        <small class="text-muted">Recommended length: 140 - 160 characters</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Focus Meta Keywords (Comma separated)</label>
                        <input type="text" name="seo[seo_meta_keywords]" class="form-control" value="<?= e(getSetting('seo_meta_keywords')); ?>">
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-4">
                        <h6 class="fw-bold fs-6 mb-2"><i class="fas fa-check-circle text-success me-1"></i> Automatic SEO Enhancements Active:</h6>
                        <ul class="small text-muted mb-0 ps-3">
                            <li>Dynamic OpenGraph (OG) & Twitter Summary Card tags</li>
                            <li>Canonical URL auto-generation preventing duplicate indexing</li>
                            <li>Organization Schema.org JSON-LD structured data for Google Rich Snippets</li>
                            <li>Clean robots.txt and responsive XML sitemap readiness</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save me-2"></i> Save SEO Settings</button>
                </form>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

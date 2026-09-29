<?php
/**
 * Ayurvedic & Herbal Products Catalog
 * Seva Arogya Wellness Store
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$products = [];

try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN product_categories c ON p.category_id = c.id WHERE p.status = 'published' ORDER BY p.is_featured DESC, p.id ASC");
        $products = $stmt ? $stmt->fetchAll() : [];
    }
} catch (Throwable $e) {
    error_log("Products Query Notice: " . $e->getMessage());
}

$pageTitle = 'Ayurvedic Herbal Store - Pure Kwath, Oils & Supplements';
$pageDesc = 'Order authentic Ministry of AYUSH approved herbal remedies, Ayush Kwath, Triphala, and pain relief oils with 100% of proceeds supporting free medical camps.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%);">
    <div class="container py-4">
        <span class="badge bg-warning text-dark mb-2 px-3 py-1"><i class="fas fa-leaf me-1"></i> Pure AYUSH Wellness</span>
        <h1 class="display-5 fw-bold text-white mb-3">Authentic Ayurvedic & Herbal Formulations</h1>
        <p class="lead text-white-50 max-w-700">100% natural, ethically sourced Ayurvedic herbs and oils. All proceeds from our store directly fund free healthcare camps for underprivileged communities.</p>
    </div>
</div>

<!-- Product Grid -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="row g-4">
            <?php foreach ($products as $p): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden d-flex flex-column">
                    <img src="<?= e(getImageUrl($p['image'], 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=600&q=80')); ?>" alt="<?= e($p['name']); ?>" style="height: 220px; object-fit: cover;">
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="badge bg-success-subtle text-success align-self-start mb-2"><?= e($p['category_name'] ?? 'Herbal Supplement'); ?></span>
                        <h5 class="fw-bold mb-1 fs-6"><?= e($p['name']); ?></h5>
                        <small class="text-muted mb-2">SKU: <?= e($p['sku'] ?? 'AYUSH-001'); ?></small>
                        
                        <p class="small text-muted mb-3 flex-grow-1"><?= e($p['short_description']); ?></p>

                        <?php if (!empty($p['benefits'])): ?>
                        <div class="p-2 bg-light rounded small mb-3">
                            <strong class="text-success"><i class="fas fa-check-circle me-1"></i> Key Benefits:</strong><br>
                            <span class="text-muted"><?= e(truncateText($p['benefits'], 85)); ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-auto">
                            <div>
                                <span class="fs-5 fw-bold text-dark"><?= formatCurrency($p['price']); ?></span>
                                <?php if (!empty($p['mrp']) && $p['mrp'] > $p['price']): ?>
                                <small class="text-muted text-decoration-line-through ms-1"><?= formatCurrency($p['mrp']); ?></small>
                                <?php endif; ?>
                            </div>
                            <a href="<?= BASE_URL; ?>/contact.php?subject=<?= urlencode('Product Order: ' . $p['name']); ?>" class="btn btn-sm btn-ngo-primary">
                                <i class="fas fa-shopping-cart me-1"></i> Inquire / Order
                            </a>
                        </div>
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

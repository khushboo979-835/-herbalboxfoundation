<?php
/**
 * Single Blog Post Details Page
 * Seva Foundation Knowledge Hub
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$pdo = Database::getConnection();

$slug = sanitize($_GET['slug'] ?? '');
if (empty($slug)) {
    header('Location: ' . BASE_URL . '/blog.php');
    exit;
}

$stmt = $pdo->prepare("SELECT b.*, c.name as category_name, c.slug as category_slug FROM blog_posts b LEFT JOIN blog_categories c ON b.category_id = c.id WHERE b.slug = ? AND b.status = 'published'");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: ' . BASE_URL . '/blog.php');
    exit;
}

// Increment view count
$pdo->prepare("UPDATE blog_posts SET views_count = views_count + 1 WHERE id = ?")->execute([$post['id']]);

// Fetch related posts
$relatedStmt = $pdo->prepare("SELECT title, slug, featured_image, published_at FROM blog_posts WHERE category_id = ? AND id != ? AND status = 'published' ORDER BY id DESC LIMIT 3");
$relatedStmt->execute([$post['category_id'], $post['id']]);
$relatedPosts = $relatedStmt->fetchAll();

$pageTitle = $post['seo_title'] ?: $post['title'];
$pageDesc = $post['seo_description'] ?: $post['short_description'];
$pageKeywords = $post['seo_keywords'] ?: $post['tags'];
$canonicalUrl = $post['canonical_url'] ?: (BASE_URL . '/blog-details.php?slug=' . $post['slug']);
$ogImage = !empty($post['featured_image']) ? getImageUrl($post['featured_image']) : null;

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Article Header -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container py-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <a href="<?= BASE_URL; ?>/blog.php?category=<?= e($post['category_slug']); ?>" class="badge bg-primary text-white text-decoration-none px-3 py-1"><?= e($post['category_name'] ?? 'Healthcare'); ?></a>
            <span class="text-white-50">•</span>
            <span class="text-white-50 small"><i class="fas fa-calendar-day me-1"></i> <?= formatDate($post['published_at']); ?></span>
            <span class="text-white-50">•</span>
            <span class="text-white-50 small"><i class="fas fa-eye me-1"></i> <?= (int)$post['views_count'] + 1; ?> Views</span>
        </div>
        <h1 class="display-6 fw-bold text-white mb-3 max-w-850"><?= e($post['title']); ?></h1>
        <p class="lead text-white-50 max-w-750"><?= e($post['short_description']); ?></p>
    </div>
</div>

<!-- Main Article Content -->
<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <img src="<?= e(getImageUrl($post['featured_image'], 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?w=1000&q=80')); ?>" alt="<?= e($post['title']); ?>" class="img-fluid rounded-4 mb-4 shadow-sm w-100" style="max-height: 440px; object-fit: cover;">

                <!-- Article Body Content -->
                <article class="blog-post-content mb-5" style="line-height: 1.85; font-size: 16.5px; color: #334155;">
                    <?= $post['content']; ?>
                </article>

                <!-- Tags & Social Share -->
                <div class="p-4 bg-light rounded-4 border d-flex flex-wrap justify-content-between align-items-center gap-3 mb-5">
                    <div>
                        <strong class="d-block mb-1 small text-muted">Topic Tags:</strong>
                        <?php foreach (explode(',', (string)$post['tags']) as $t): if (trim($t)): ?>
                        <a href="<?= BASE_URL; ?>/blog.php?tag=<?= urlencode(trim($t)); ?>" class="badge bg-white text-dark border me-1 text-decoration-none">#<?= e(trim($t)); ?></a>
                        <?php endif; endforeach; ?>
                    </div>
                    <div>
                        <strong class="d-block mb-1 small text-muted">Share Article:</strong>
                        <div class="d-inline-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonicalUrl); ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode($canonicalUrl); ?>&text=<?= urlencode($post['title']); ?>" target="_blank" class="btn btn-sm btn-outline-info"><i class="fab fa-x-twitter"></i></a>
                            <a href="https://wa.me/?text=<?= urlencode($post['title'] . ' ' . $canonicalUrl); ?>" target="_blank" class="btn btn-sm btn-outline-success"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Author Box -->
                <div class="p-4 bg-white rounded-4 border shadow-sm d-flex align-items-center gap-4 mb-5">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 70px; height: 70px; flex-shrink: 0;">
                        <?= strtoupper(substr($post['author_name'] ?? 'E', 0, 1)); ?>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1"><?= e($post['author_name'] ?? 'Editorial Healthcare Team'); ?></h5>
                        <small class="text-muted d-block mb-2">Health & Education Research Cell, Seva Foundation</small>
                        <p class="small text-muted mb-0">Committed to publishing evidence-backed medical awareness, NCERT learning aids, and holistic community wellness guides.</p>
                    </div>
                </div>

                <!-- Related Posts -->
                <?php if (!empty($relatedPosts)): ?>
                <div class="pt-4 border-top">
                    <h4 class="fw-bold mb-4">Related Articles</h4>
                    <div class="row g-4">
                        <?php foreach ($relatedPosts as $rp): ?>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <img src="<?= e(getImageUrl($rp['featured_image'], 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?w=400&q=80')); ?>" alt="<?= e($rp['title']); ?>" style="height: 140px; object-fit: cover;">
                                <div class="card-body p-3 d-flex flex-column">
                                    <h6 class="fw-bold mb-2 fs-6"><a href="<?= BASE_URL; ?>/blog-details.php?slug=<?= e($rp['slug']); ?>" class="text-dark text-decoration-none"><?= e(truncateText($rp['title'], 55)); ?></a></h6>
                                    <small class="text-muted mt-auto"><?= formatDate($rp['published_at']); ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

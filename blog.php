<?php
/**
 * Blog & News Listing Page
 * Seva Foundation Knowledge Hub
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

$posts = [];
$categories = [];
$recentPosts = [];

try {
    $pdo = Database::getConnection();
    if ($pdo) {
        $catSlug = sanitize($_GET['category'] ?? '');
        $searchTerm = sanitize($_GET['search'] ?? '');
        $tag = sanitize($_GET['tag'] ?? '');

        $sql = "SELECT b.*, c.name as category_name, c.slug as category_slug FROM blog_posts b LEFT JOIN blog_categories c ON b.category_id = c.id WHERE b.status = 'published'";
        $params = [];

        if (!empty($catSlug)) {
            $sql .= " AND c.slug = ?";
            $params[] = $catSlug;
        }
        if (!empty($searchTerm)) {
            $sql .= " AND (b.title LIKE ? OR b.excerpt LIKE ? OR b.content LIKE ?)";
            $term = "%{$searchTerm}%";
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY b.published_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $posts = $stmt->fetchAll();

        // Fetch categories for sidebar
        $categories = $pdo->query("SELECT c.*, COUNT(b.id) as post_count FROM blog_categories c LEFT JOIN blog_posts b ON c.id = b.category_id AND b.status = 'published' WHERE c.status = 'published' GROUP BY c.id ORDER BY c.name ASC")->fetchAll() ?: [];

        // Fetch recent posts
        $recentPosts = $pdo->query("SELECT title, slug, published_at, featured_image FROM blog_posts WHERE status = 'published' ORDER BY published_at DESC LIMIT 4")->fetchAll() ?: [];
    }
} catch (Throwable $e) {
    error_log("Blog Query Notice: " . $e->getMessage());
}

$pageTitle = 'News & Health Articles - Healthcare Insights & Education Guides';
$pageDesc = 'Explore expert articles on preventive healthcare, NCERT exam strategies, Ayurvedic remedies, and updates on Seva Foundation community camps.';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Banner -->
<div class="bg-dark text-white py-5 position-relative" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container py-4">
        <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-1"><i class="fas fa-newspaper me-1"></i> Knowledge Hub</span>
        <h1 class="display-5 fw-bold text-white mb-3">Health Insights, NCERT Guides & NGO Updates</h1>
        <p class="lead text-white-50 max-w-700">Articles authored by certified physicians, senior education counselors, and AYUSH wellness acharyas.</p>
    </div>
</div>

<!-- Blog Content & Sidebar -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="row g-5">
            <!-- Left Main Posts Column -->
            <div class="col-lg-8">
                <?php if (empty($posts)): ?>
                <div class="p-5 bg-white rounded-4 border text-center">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>No articles found matching your criteria</h5>
                    <p class="text-muted">Try different search keywords or select another category.</p>
                    <a href="<?= BASE_URL; ?>/blog.php" class="btn btn-outline-primary">View All Articles</a>
                </div>
                <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($posts as $post): ?>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden d-flex flex-column">
                            <img src="<?= e(getImageUrl($post['featured_image'], 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?w=600&q=80')); ?>" alt="<?= e($post['title']); ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-2 small text-muted">
                                    <span class="badge bg-primary-subtle text-primary"><?= e($post['category_name'] ?? 'General'); ?></span>
                                    <span>•</span>
                                    <span><?= formatDate($post['published_at']); ?></span>
                                </div>
                                <h5 class="fw-bold mb-2 fs-6"><a href="<?= BASE_URL; ?>/blog-details.php?slug=<?= e($post['slug']); ?>" class="text-dark text-decoration-none"><?= e($post['title']); ?></a></h5>
                                <p class="small text-muted mb-3 flex-grow-1"><?= e($post['short_description']); ?></p>
                                <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="fas fa-user-edit text-primary me-1"></i> <?= e($post['author_name'] ?? 'Editorial Team'); ?></small>
                                    <a href="<?= BASE_URL; ?>/blog-details.php?slug=<?= e($post['slug']); ?>" class="text-primary fw-bold small text-decoration-none">Read More <i class="fas fa-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <!-- Search Box -->
                <div class="p-4 bg-white rounded-4 shadow-sm border mb-4">
                    <h5 class="fw-bold mb-3 fs-6">Search Knowledge Hub</h5>
                    <form action="<?= BASE_URL; ?>/blog.php" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search keywords..." value="<?= e($searchTerm); ?>">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>

                <!-- Categories -->
                <div class="p-4 bg-white rounded-4 shadow-sm border mb-4">
                    <h5 class="fw-bold mb-3 fs-6">Categories</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <a href="<?= BASE_URL; ?>/blog.php" class="d-flex justify-content-between text-decoration-none <?= empty($catSlug) ? 'text-primary fw-bold' : 'text-muted'; ?>">
                                <span>All Articles</span>
                                <span class="badge bg-light text-muted border"><?= count($posts); ?></span>
                            </a>
                        </li>
                        <?php foreach ($categories as $c): ?>
                        <li class="mb-2">
                            <a href="<?= BASE_URL; ?>/blog.php?category=<?= e($c['slug']); ?>" class="d-flex justify-content-between text-decoration-none <?= $catSlug === $c['slug'] ? 'text-primary fw-bold' : 'text-muted'; ?>">
                                <span><?= e($c['name']); ?></span>
                                <span class="badge bg-light text-muted border"><?= (int)$c['post_count']; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Recent Posts -->
                <div class="p-4 bg-white rounded-4 shadow-sm border">
                    <h5 class="fw-bold mb-3 fs-6">Recent Articles</h5>
                    <?php foreach ($recentPosts as $rp): ?>
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                        <img src="<?= e(getImageUrl($rp['featured_image'], 'https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?w=120&q=80')); ?>" alt="<?= e($rp['title']); ?>" class="rounded-3" style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="mb-1 fs-6"><a href="<?= BASE_URL; ?>/blog-details.php?slug=<?= e($rp['slug']); ?>" class="text-dark text-decoration-none"><?= e(truncateText($rp['title'], 55)); ?></a></h6>
                            <small class="text-muted"><?= formatDate($rp['published_at']); ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

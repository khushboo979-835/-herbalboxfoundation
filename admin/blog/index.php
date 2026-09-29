<?php
/**
 * Blog CMS & News Articles Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM blog_posts WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Blog Post', 'blog_posts', $delId);
    setFlashMessage('success', 'Article deleted successfully.');
    header('Location: ' . ADMIN_URL . '/blog/index.php');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $postId = (int)($_POST['post_id'] ?? 0);
    $title = sanitize($_POST['title'] ?? '');
    $slug = slugify($_POST['slug'] ?: $title);
    $catId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $shortDesc = sanitize($_POST['short_description'] ?? '');
    $content = $_POST['content'] ?? ''; // Keep HTML for rich articles
    $tags = sanitize($_POST['tags'] ?? '');
    $author = sanitize($_POST['author_name'] ?? 'Editorial Team');
    $status = sanitize($_POST['status'] ?? 'published');
    $seoTitle = sanitize($_POST['seo_title'] ?? '');
    $seoDesc = sanitize($_POST['seo_description'] ?? '');

    $featuredImage = null;
    if (!empty($_FILES['featured_image']['name'])) {
        $upload = uploadFile($_FILES['featured_image'], 'blogs', 'image');
        if ($upload['success']) $featuredImage = $upload['filename'];
    }

    if ($postId > 0) {
        if ($featuredImage) {
            $stmt = $pdo->prepare("UPDATE blog_posts SET category_id = ?, title = ?, slug = ?, short_description = ?, content = ?, tags = ?, author_name = ?, status = ?, seo_title = ?, seo_description = ?, featured_image = ? WHERE id = ?");
            $stmt->execute([$catId, $title, $slug, $shortDesc, $content, $tags, $author, $status, $seoTitle, $seoDesc, $featuredImage, $postId]);
        } else {
            $stmt = $pdo->prepare("UPDATE blog_posts SET category_id = ?, title = ?, slug = ?, short_description = ?, content = ?, tags = ?, author_name = ?, status = ?, seo_title = ?, seo_description = ? WHERE id = ?");
            $stmt->execute([$catId, $title, $slug, $shortDesc, $content, $tags, $author, $status, $seoTitle, $seoDesc, $postId]);
        }
        logActivity('Updated Blog Post', 'blog_posts', $postId);
        setFlashMessage('success', 'Article updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO blog_posts (category_id, author_id, title, slug, short_description, content, tags, author_name, status, seo_title, seo_description, featured_image) VALUES (?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$catId, $title, $slug, $shortDesc, $content, $tags, $author, $status, $seoTitle, $seoDesc, $featuredImage]);
        logActivity('Created Blog Post', 'blog_posts');
        setFlashMessage('success', 'New article published.');
    }

    header('Location: ' . ADMIN_URL . '/blog/index.php');
    exit;
}

$categories = $pdo->query("SELECT * FROM blog_categories WHERE status = 'active' ORDER BY name ASC")->fetchAll();
$posts = $pdo->query("SELECT b.*, c.name as category_name FROM blog_posts b LEFT JOIN blog_categories c ON b.category_id = c.id ORDER BY b.id DESC")->fetchAll();

$editPost = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $st->execute([$editId]);
    $editPost = $st->fetch();
}

$adminTitle = 'Blog & Health News CMS';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Blog Articles & Health CMS</h4>
                <p class="text-muted small mb-0">Publish educational articles, NCERT guides, AYUSH wellness tips, and news updates.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editPost ? 'Edit Article #' . $editPost['id'] : 'Write New Article'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/blog/index.php" method="POST" enctype="multipart/form-data" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="post_id" value="<?= (int)($editPost['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Article Title *</label>
                                <input type="text" id="titleInput" name="title" class="form-control" required value="<?= e($editPost['title'] ?? ''); ?>" placeholder="Article Title">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">URL Slug</label>
                                <input type="text" id="slugInput" name="slug" class="form-control" value="<?= e($editPost['slug'] ?? ''); ?>">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Category</label>
                                    <select name="category_id" class="form-select">
                                        <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c['id']; ?>" <?= ($editPost['category_id'] ?? 0) == $c['id'] ? 'selected' : ''; ?>><?= e($c['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Author Name</label>
                                    <input type="text" name="author_name" class="form-control" value="<?= e($editPost['author_name'] ?? 'Editorial Team'); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Short Excerpt / Summary *</label>
                                <textarea name="short_description" rows="2" class="form-control" required><?= e($editPost['short_description'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Full Content (HTML / Text) *</label>
                                <textarea name="content" rows="6" class="form-control" required><?= e($editPost['content'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Topic Tags (Comma separated)</label>
                                <input type="text" name="tags" class="form-control" value="<?= e($editPost['tags'] ?? 'healthcare, wellness'); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Featured Image</label>
                                <input type="file" name="featured_image" class="form-control" accept="image/*">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="published" <?= ($editPost['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                                        <option value="draft" <?= ($editPost['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">SEO Title</label>
                                    <input type="text" name="seo_title" class="form-control" value="<?= e($editPost['seo_title'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editPost ? 'Update Article' : 'Publish Article'; ?></button>
                                <?php if ($editPost): ?>
                                <a href="<?= ADMIN_URL; ?>/blog/index.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">All Articles (<?= count($posts); ?>)</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead><tr><th>Article</th><th>Category</th><th>Views</th><th>Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($posts as $p): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block"><?= e($p['title']); ?></strong>
                                        <small class="text-muted"><?= formatDate($p['published_at']); ?> • <?= e($p['author_name']); ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-primary border"><?= e($p['category_name'] ?? 'General'); ?></span></td>
                                    <td><?= (int)$p['views_count']; ?></td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/blog/index.php?edit=<?= $p['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/blog/index.php?delete=<?= $p['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

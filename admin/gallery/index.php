<?php
/**
 * Media Gallery & Photos Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete photo
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM gallery_images WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Gallery Photo', 'gallery_images', $delId);
    setFlashMessage('success', 'Photo removed from gallery.');
    header('Location: ' . ADMIN_URL . '/gallery/index.php');
    exit;
}

// Add Photo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $title = sanitize($_POST['title'] ?? '');
    $catId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $desc = sanitize($_POST['description'] ?? '');

    if (!empty($_FILES['image']['name'])) {
        $upload = uploadFile($_FILES['image'], 'gallery', 'image');
        if ($upload['success']) {
            $stmt = $pdo->prepare("INSERT INTO gallery_images (category_id, title, image_path, description, status) VALUES (?, ?, ?, ?, 'active')");
            $stmt->execute([$catId, $title, $upload['filename'], $desc]);
            logActivity('Uploaded Gallery Photo', 'gallery_images');
            setFlashMessage('success', 'New photo uploaded to gallery.');
        } else {
            setFlashMessage('danger', 'Upload error: ' . $upload['error']);
        }
    } else {
        setFlashMessage('danger', 'Please select an image file to upload.');
    }

    header('Location: ' . ADMIN_URL . '/gallery/index.php');
    exit;
}

$categories = $pdo->query("SELECT * FROM gallery_categories WHERE status = 'active' ORDER BY sort_order ASC")->fetchAll();
$images = $pdo->query("SELECT gi.*, gc.name as category_name FROM gallery_images gi LEFT JOIN gallery_categories gc ON gi.category_id = gc.id ORDER BY gi.id DESC")->fetchAll();

$adminTitle = 'Photo Gallery Manager';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Photo Gallery Management</h4>
                <p class="text-muted small mb-0">Upload on-ground photographs from medical camps, school sessions, and blood donation drives.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card-admin">
                    <div class="card-header"><i class="fas fa-upload text-primary me-2"></i> Upload New Photo</div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/gallery/index.php" method="POST" enctype="multipart/form-data" class="form-custom">
                            <?= getCsrfInput(); ?>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Photo Title *</label>
                                <input type="text" name="title" class="form-control" required placeholder="e.g. Free Eye Camp at Delhi School">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Category</label>
                                <select name="category_id" class="form-select">
                                    <?php foreach ($categories as $c): ?>
                                    <option value="<?= $c['id']; ?>"><?= e($c['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Select Image (JPG, PNG, WEBP) *</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Description</label>
                                <textarea name="description" rows="2" class="form-control" placeholder="Short caption about this activity"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus me-1"></i> Upload to Gallery</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-admin">
                    <div class="card-header">Uploaded Photos (<?= count($images); ?>)</div>
                    <div class="p-3">
                        <div class="row g-3">
                            <?php if (empty($images)): ?>
                            <div class="col-12 text-center py-4 text-muted">No uploaded gallery images</div>
                            <?php else: foreach ($images as $img): ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="card h-100 border rounded-3 overflow-hidden position-relative">
                                    <img src="<?= e(getImageUrl($img['image_path'])); ?>" alt="<?= e($img['title']); ?>" style="height: 140px; object-fit: cover;">
                                    <div class="p-2 bg-white">
                                        <h6 class="mb-1 small fw-bold text-truncate"><?= e($img['title']); ?></h6>
                                        <span class="badge bg-light text-primary border" style="font-size: 10px;"><?= e($img['category_name'] ?? 'General'); ?></span>
                                        <div class="mt-2 text-end">
                                            <a href="<?= ADMIN_URL; ?>/gallery/index.php?delete=<?= $img['id']; ?>" class="btn btn-sm btn-outline-danger py-0 px-2 btn-confirm-delete" title="Delete"><i class="fas fa-trash fa-xs"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</div>

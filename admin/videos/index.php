<?php
/**
 * Video Gallery Links CRUD Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM videos WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Video Link', 'videos', $delId);
    setFlashMessage('success', 'Video deleted.');
    header('Location: ' . ADMIN_URL . '/videos/index.php');
    exit;
}

// Add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $title = sanitize($_POST['title'] ?? '');
    $url = sanitize($_POST['youtube_url'] ?? '');
    $category = sanitize($_POST['category'] ?? 'Healthcare');
    $desc = sanitize($_POST['description'] ?? '');

    // Extract YouTube ID
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $match);
    $ytId = $match[1] ?? 'dQw4w9WgXcQ';

    $stmt = $pdo->prepare("INSERT INTO videos (title, youtube_url, youtube_id, category, description, status) VALUES (?, ?, ?, ?, ?, 'active')");
    $stmt->execute([$title, $url, $ytId, $category, $desc]);
    logActivity('Added Video Link', 'videos');
    setFlashMessage('success', 'Video added to gallery.');
    header('Location: ' . ADMIN_URL . '/videos/index.php');
    exit;
}

$videos = $pdo->query("SELECT * FROM videos ORDER BY id DESC")->fetchAll();

$adminTitle = 'Video Gallery Links';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">YouTube Video Links & Documentaries</h4>
                <p class="text-muted small mb-0">Embed camp documentaries and awareness videos on the public gallery page.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><i class="fab fa-youtube text-danger me-2"></i> Add YouTube Video</div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/videos/index.php" method="POST" class="form-custom">
                            <?= getCsrfInput(); ?>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Video Title *</label>
                                <input type="text" name="title" class="form-control" required placeholder="e.g. Free Eye Camp Highlights">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">YouTube URL *</label>
                                <input type="url" name="youtube_url" class="form-control" required placeholder="https://www.youtube.com/watch?v=...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Category</label>
                                <input type="text" name="category" class="form-control" value="Healthcare Camps">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Description</label>
                                <textarea name="description" rows="2" class="form-control" placeholder="Short description"></textarea>
                            </div>

                            <button type="submit" class="btn btn-danger w-100"><i class="fas fa-plus me-1"></i> Add Video Link</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">All Video Links (<?= count($videos); ?>)</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead><tr><th>Title</th><th>Category</th><th>Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($videos as $v): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block"><?= e($v['title']); ?></strong>
                                        <small class="text-muted"><?= e($v['youtube_id']); ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= e($v['category']); ?></span></td>
                                    <td><a href="<?= ADMIN_URL; ?>/videos/index.php?delete=<?= $v['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a></td>
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

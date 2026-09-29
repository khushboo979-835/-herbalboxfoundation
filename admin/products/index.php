<?php
/**
 * Ayurvedic Store Products CRUD Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$delId]);
    logActivity('Deleted Product', 'products', $delId);
    setFlashMessage('success', 'Product removed from store.');
    header('Location: ' . ADMIN_URL . '/products/index.php');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $prodId = (int)($_POST['prod_id'] ?? 0);
    $name = sanitize($_POST['name'] ?? '');
    $slug = slugify($_POST['slug'] ?: $name);
    $sku = sanitize($_POST['sku'] ?? 'AYUSH-' . time());
    $catId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $price = (float)($_POST['price'] ?? 0);
    $mrp = (float)($_POST['mrp'] ?? $price);
    $shortDesc = sanitize($_POST['short_description'] ?? '');
    $desc = sanitize($_POST['description'] ?? '');
    $benefits = sanitize($_POST['benefits'] ?? '');
    $stock = (int)($_POST['stock_quantity'] ?? 100);
    $status = sanitize($_POST['status'] ?? 'active');

    $imgPath = null;
    if (!empty($_FILES['image']['name'])) {
        $upload = uploadFile($_FILES['image'], 'products', 'image');
        if ($upload['success']) $imgPath = $upload['filename'];
    }

    if ($prodId > 0) {
        if ($imgPath) {
            $stmt = $pdo->prepare("UPDATE products SET category_id = ?, name = ?, slug = ?, sku = ?, price = ?, mrp = ?, short_description = ?, description = ?, benefits = ?, stock_quantity = ?, status = ?, image = ? WHERE id = ?");
            $stmt->execute([$catId, $name, $slug, $sku, $price, $mrp, $shortDesc, $desc, $benefits, $stock, $status, $imgPath, $prodId]);
        } else {
            $stmt = $pdo->prepare("UPDATE products SET category_id = ?, name = ?, slug = ?, sku = ?, price = ?, mrp = ?, short_description = ?, description = ?, benefits = ?, stock_quantity = ?, status = ? WHERE id = ?");
            $stmt->execute([$catId, $name, $slug, $sku, $price, $mrp, $shortDesc, $desc, $benefits, $stock, $status, $prodId]);
        }
        logActivity('Updated Product', 'products', $prodId);
        setFlashMessage('success', 'Product updated.');
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, sku, price, mrp, short_description, description, benefits, stock_quantity, status, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$catId, $name, $slug, $sku, $price, $mrp, $shortDesc, $desc, $benefits, $stock, $status, $imgPath]);
        logActivity('Created Product', 'products');
        setFlashMessage('success', 'New herbal product added.');
    }

    header('Location: ' . ADMIN_URL . '/products/index.php');
    exit;
}

$categories = $pdo->query("SELECT * FROM product_categories WHERE status = 'active' ORDER BY name ASC")->fetchAll();
$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN product_categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();

$editProd = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $st = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $st->execute([$editId]);
    $editProd = $st->fetch();
}

$adminTitle = 'Ayurvedic Store Products';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Ayurvedic Herbal Store Management</h4>
                <p class="text-muted small mb-0">Manage pure herbal supplements, pain relief oils, and wellness products whose proceeds support charity camps.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><?= $editProd ? 'Edit Product #' . $editProd['id'] : 'Add New Herbal Product'; ?></div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/products/index.php" method="POST" enctype="multipart/form-data" class="form-custom">
                            <?= getCsrfInput(); ?>
                            <input type="hidden" name="prod_id" value="<?= (int)($editProd['id'] ?? 0); ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Product Name *</label>
                                <input type="text" id="titleInput" name="name" class="form-control" required value="<?= e($editProd['name'] ?? ''); ?>" placeholder="e.g. Ayush Kwath Immunity 100g">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">SKU Code</label>
                                    <input type="text" name="sku" class="form-control" value="<?= e($editProd['sku'] ?? 'AYUSH-001'); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Category</label>
                                    <select name="category_id" class="form-select">
                                        <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c['id']; ?>" <?= ($editProd['category_id'] ?? 0) == $c['id'] ? 'selected' : ''; ?>><?= e($c['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Offer Price (₹) *</label>
                                    <input type="number" step="0.01" name="price" class="form-control" required value="<?= e((string)($editProd['price'] ?? '150.00')); ?>">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">MRP (₹)</label>
                                    <input type="number" step="0.01" name="mrp" class="form-control" value="<?= e((string)($editProd['mrp'] ?? '180.00')); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Short Excerpt</label>
                                <textarea name="short_description" rows="2" class="form-control"><?= e($editProd['short_description'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Key Benefits (One per line)</label>
                                <textarea name="benefits" rows="2" class="form-control"><?= e($editProd['benefits'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Product Photo</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100"><?= $editProd ? 'Update Product' : 'Save Product'; ?></button>
                                <?php if ($editProd): ?>
                                <a href="<?= ADMIN_URL; ?>/products/index.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">All Products (<?= count($products); ?>)</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead><tr><th>Product</th><th>Price</th><th>Category</th><th>Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($products as $p): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block text-dark"><?= e($p['name']); ?></strong>
                                        <small class="text-muted">SKU: <?= e($p['sku']); ?></small>
                                    </td>
                                    <td><strong class="text-success"><?= formatCurrency($p['price']); ?></strong></td>
                                    <td><span class="badge bg-light text-primary border"><?= e($p['category_name'] ?? 'Herbal'); ?></span></td>
                                    <td>
                                        <a href="<?= ADMIN_URL; ?>/products/index.php?edit=<?= $p['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                        <a href="<?= ADMIN_URL; ?>/products/index.php?delete=<?= $p['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete"><i class="fas fa-trash"></i></a>
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

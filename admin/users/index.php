<?php
/**
 * Admin Users & Permissions Manager
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth_guard.php';

$pdo = Database::getConnection();

// Delete Admin
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    if ($delId === (int)$_SESSION['admin_id']) {
        setFlashMessage('danger', 'You cannot delete your own active administrator account.');
    } else {
        $pdo->prepare("DELETE FROM admins WHERE id = ?")->execute([$delId]);
        logActivity('Deleted Admin User', 'admins', $delId);
        setFlashMessage('success', 'Admin user removed.');
    }
    header('Location: ' . ADMIN_URL . '/users/index.php');
    exit;
}

// Add Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = sanitize($_POST['role'] ?? 'admin');

    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        setFlashMessage('danger', 'Please complete all required fields.');
    } else {
        // Check uniqueness
        $check = $pdo->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);
        if ($check->fetch()) {
            setFlashMessage('danger', 'Username or email already registered.');
        } else {
            $hashedPass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO admins (name, email, username, password, role, status) VALUES (?, ?, ?, ?, ?, 'active')");
            $stmt->execute([$name, $email, $username, $hashedPass, $role]);
            logActivity('Created Admin User', 'admins', null, "New user: {$username} ({$role})");
            setFlashMessage('success', "New admin user '{$username}' created successfully.");
        }
    }

    header('Location: ' . ADMIN_URL . '/users/index.php');
    exit;
}

$admins = $pdo->query("SELECT id, name, email, username, role, status, last_login, created_at FROM admins ORDER BY id ASC")->fetchAll();

$adminTitle = 'Admin Users & Access Control';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="admin-main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Administrative Users & Access Control</h4>
                <p class="text-muted small mb-0">Manage staff access levels, superadministrators, and credentials.</p>
            </div>
        </div>

        <?= displayFlashMessage(); ?>

        <div class="row g-4">
            <!-- Add User Form -->
            <div class="col-lg-5">
                <div class="card-admin">
                    <div class="card-header"><i class="fas fa-user-plus text-primary me-2"></i> Create New Admin User</div>
                    <div class="p-4">
                        <form action="<?= ADMIN_URL; ?>/users/index.php" method="POST" class="form-custom">
                            <?= getCsrfInput(); ?>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Full Name *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Vikas Sharma">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Email Address *</label>
                                <input type="email" name="email" class="form-control" required placeholder="vikas@ngoseva.org">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Username *</label>
                                <input type="text" name="username" class="form-control" required placeholder="e.g. vikas_admin">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Password *</label>
                                <input type="password" name="password" class="form-control" required placeholder="••••••••">
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold">Role & Permissions</label>
                                <select name="role" class="form-select">
                                    <option value="admin">Administrator (Full Content Access)</option>
                                    <option value="superadmin">Super Administrator (All + User Control)</option>
                                    <option value="editor">Editor (Blog & Gallery)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-user-check me-1"></i> Create Admin Account</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Users List -->
            <div class="col-lg-7">
                <div class="card-admin">
                    <div class="card-header">Existing Administrators (<?= count($admins); ?>)</div>
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Last Login</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admins as $a): ?>
                                <tr>
                                    <td>
                                        <strong class="d-block text-dark"><?= e($a['name']); ?></strong>
                                        <small class="text-muted"><?= e($a['email']); ?></small>
                                    </td>
                                    <td><code><?= e($a['username']); ?></code></td>
                                    <td><span class="badge bg-primary-subtle text-primary"><?= strtoupper($a['role']); ?></span></td>
                                    <td><small class="text-muted"><?= formatDateTime($a['last_login']) ?: 'Never'; ?></small></td>
                                    <td>
                                        <?php if ($a['id'] !== (int)$_SESSION['admin_id']): ?>
                                        <a href="<?= ADMIN_URL; ?>/users/index.php?delete=<?= $a['id']; ?>" class="btn btn-sm btn-outline-danger btn-confirm-delete" title="Delete"><i class="fas fa-trash"></i></a>
                                        <?php else: ?>
                                        <span class="badge bg-light text-muted">Current</span>
                                        <?php endif; ?>
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

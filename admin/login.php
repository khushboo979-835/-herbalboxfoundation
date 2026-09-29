<?php
/**
 * Secure Admin Login Portal
 * Throttled Authentication with CSRF Protection
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

// If already authenticated, redirect to dashboard
if (isAdminLoggedIn()) {
    header('Location: ' . ADMIN_URL . '/dashboard.php');
    exit;
}

$error = '';
$pdo = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfToken();

    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username/email and password.';
    } else {
        // Query admin by username or email
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE (username = ? OR email = ?) AND status = 'active' LIMIT 1");
        $stmt->execute([$username, $username]);
        $admin = $stmt->fetch();

        if ($admin) {
            // Check lockout
            if (!empty($admin['lockout_until']) && strtotime($admin['lockout_until']) > time()) {
                $waitMinutes = ceil((strtotime($admin['lockout_until']) - time()) / 60);
                $error = "Account temporarily locked due to multiple failed attempts. Please retry after {$waitMinutes} minute(s).";
            } else {
                // Verify password hash
                if (password_verify($password, $admin['password'])) {
                    // Reset failed attempts & update last login
                    $update = $pdo->prepare("UPDATE admins SET login_attempts = 0, lockout_until = NULL, last_login = NOW() WHERE id = ?");
                    $update->execute([$admin['id']]);

                    // Regenerate session ID to prevent fixation
                    session_regenerate_id(true);

                    // Set session variables
                    $_SESSION['admin_id'] = (int)$admin['id'];
                    $_SESSION['admin_name'] = $admin['name'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['admin_role'] = $admin['role'];
                    $_SESSION['admin_avatar'] = $admin['avatar'];
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['last_activity'] = time();

                    logActivity('Admin Login Success', 'auth', (int)$admin['id'], 'Successful login to admin console');

                    $redirect = sanitize($_GET['redirect'] ?? ADMIN_URL . '/dashboard.php');
                    header('Location: ' . $redirect);
                    exit;
                } else {
                    // Password failed - Increment attempt
                    $attempts = (int)$admin['login_attempts'] + 1;
                    $lockout = null;
                    if ($attempts >= 5) {
                        $lockout = date('Y-m-d H:i:s', time() + (15 * 60)); // 15-minute lock
                        $error = 'Account locked for 15 minutes due to 5 consecutive failed login attempts.';
                    } else {
                        $remaining = 5 - $attempts;
                        $error = "Invalid credentials. {$remaining} attempt(s) remaining before lockout.";
                    }

                    $pdo->prepare("UPDATE admins SET login_attempts = ?, lockout_until = ? WHERE id = ?")->execute([$attempts, $lockout, $admin['id']]);
                    logActivity('Failed Login Attempt', 'auth', (int)$admin['id'], "Failed password attempt for user {$username}");
                }
            }
        } else {
            $error = 'Invalid credentials or inactive account.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Seva Foundation Console</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #042f2e 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .login-header {
            background: #0d9488;
            color: #ffffff;
            padding: 35px 30px;
            text-align: center;
        }
        .login-body {
            padding: 35px 30px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 14px;
            border: 1.5px solid #e2e8f0;
        }
        .form-control:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15);
        }
        .btn-login {
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: white;
            font-weight: 700;
            padding: 12px;
            border-radius: 8px;
            border: none;
            width: 100%;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #0f766e, #115e59);
            color: white;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="d-inline-flex p-3 rounded-circle bg-white text-dark mb-3">
            <i class="fas fa-shield-alt fa-2x text-primary"></i>
        </div>
        <h4 class="fw-bold mb-1">Administrative Console</h4>
        <p class="mb-0 small text-white-50">Seva Arogya & Shiksha Foundation</p>
    </div>

    <div class="login-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i> <?= e($error); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL; ?>/admin/login.php" method="POST">
            <?= getCsrfInput(); ?>

            <div class="mb-3">
                <label class="form-label small fw-bold">Username or Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="admin or admin@ngoseva.org" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-1"></i> Sign In to Dashboard
            </button>
        </form>

        <div class="mt-4 pt-3 border-top text-center">
            <small class="text-muted d-block mb-1">Default Demo Admin: <code>admin</code> / <code>admin123</code></small>
            <a href="<?= BASE_URL; ?>/index.php" class="text-primary small text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Back to Public Website</a>
        </div>
    </div>
</div>

</body>
</html>

<?php
/**
 * Hostinger Database Connection Setup & Diagnostic Tool
 * Herbalbox Foundation
 */

declare(strict_types=1);

$dbConfigFile = __DIR__ . '/config/database.php';
$testResult = null;
$error = '';
$success = '';

// Pre-fill existing values
$currentHost = 'localhost';
$currentDb = 'u467991428_ngo_management';
$currentUser = 'u467991428_ngo_user';
$currentPass = 'IXMwfvq6R&4';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = trim($_POST['db_host'] ?? 'localhost');
    $dbname = trim($_POST['db_name'] ?? '');
    $user = trim($_POST['db_user'] ?? '');
    $pass = $_POST['db_pass'] ?? '';

    if (empty($dbname) || empty($user)) {
        $error = 'Database Name and Database User cannot be empty.';
    } else {
        // Test PDO connection
        try {
            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            
            $pdo = new PDO($dsn, $user, $pass, $options);
            
            // Count tables
            $tablesStmt = $pdo->query("SHOW TABLES");
            $tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);
            $tableCount = count($tables);

            // Update config/database.php
            $safePass = addcslashes($pass, "'\\");
            $configContent = "<?php\n/**\n * Database Configuration & Connection Manager\n * NGO Website & Admin System - Hostinger Production Ready\n */\n\ndeclare(strict_types=1);\n\nclass Database {\n    private static string \$host = '{$host}';\n    private static string \$dbName = '{$dbname}';\n    private static string \$username = '{$user}';\n    private static string \$password = '{$safePass}';\n    private static string \$charset = 'utf8mb4';\n    private static ?PDO \$pdoInstance = null;\n\n    public static function getConnection(): PDO {\n        if (self::\$pdoInstance === null) {\n            \$dsn = \"mysql:host=\" . self::\$host . \";dbname=\" . self::\$dbName . \";charset=\" . self::\$charset;\n            \$options = [\n                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,\n                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n                PDO::ATTR_EMULATE_PREPARES   => false,\n                PDO::MYSQL_ATTR_INIT_COMMAND => \"SET NAMES \" . self::\$charset . \" COLLATE utf8mb4_unicode_ci\"\n            ];\n            try {\n                self::\$pdoInstance = new PDO(\$dsn, self::\$username, self::\$password, \$options);\n            } catch (PDOException \$e) {\n                error_log(\"Database Error: \" . \$e->getMessage());\n                die(\"<div style='font-family:sans-serif;padding:30px;max-width:600px;margin:50px auto;border:1px solid #fecaca;background:#fff1f2;color:#991b1b;border-radius:12px;'><h3>Database Connection Notice</h3><p>\" . htmlspecialchars(\$e->getMessage()) . \"</p><p><a href='/db_check.php' style='color:#2563eb;font-weight:bold;'>Click here to configure Database Connection</a></p></div>\");\n            }\n        }\n        return self::\$pdoInstance;\n    }\n}\n";

            file_put_contents($dbConfigFile, $configContent);

            $success = "Database Connection Successful! Config updated. Found {$tableCount} tables in database.";
            $currentHost = $host;
            $currentDb = $dbname;
            $currentUser = $user;
            $currentPass = $pass;

        } catch (PDOException $e) {
            $error = 'MySQL Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup & Diagnostics - Herbalbox Foundation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; font-family: 'Segoe UI', system-ui, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .setup-card { max-width: 580px; width: 100%; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); padding: 35px; margin: 20px; }
        .logo-box { width: 64px; height: 64px; object-fit: contain; }
    </style>
</head>
<body>

<div class="setup-card">
    <div class="text-center mb-4">
        <img src="assets/images/logo.png" alt="Herbalbox Foundation" class="logo-box mb-2">
        <h3 class="fw-bold text-dark mb-1">Hostinger Database Connector</h3>
        <p class="text-muted small">Verify & Link Your MySQL Database for <strong>herbalboxfoundation.com</strong></p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success d-flex align-items-center gap-3 p-3 rounded-3 mb-4">
            <i class="fas fa-check-circle fa-2x text-success"></i>
            <div>
                <strong class="d-block">Success!</strong>
                <span class="small"><?= htmlspecialchars($success); ?></span>
            </div>
        </div>
        <div class="d-grid gap-2 mb-4">
            <a href="index.php" class="btn btn-success btn-lg fw-bold"><i class="fas fa-globe me-2"></i> Visit Live Website</a>
            <a href="admin/login.php" class="btn btn-outline-dark fw-bold"><i class="fas fa-lock me-2"></i> Admin Panel Login</a>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger d-flex align-items-center gap-3 p-3 rounded-3 mb-4">
            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
            <div>
                <strong class="d-block">Connection Failed</strong>
                <span class="small"><?= htmlspecialchars($error); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" action="db_check.php">
        <div class="mb-3">
            <label class="form-label small fw-bold">MySQL Host</label>
            <input type="text" name="db_host" class="form-control" value="<?= htmlspecialchars($currentHost); ?>" required>
            <small class="text-muted">Usually <code>localhost</code> on Hostinger</small>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">MySQL Database Name</label>
            <input type="text" name="db_name" class="form-control" value="<?= htmlspecialchars($currentDb); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">MySQL Username</label>
            <input type="text" name="db_user" class="form-control" value="<?= htmlspecialchars($currentUser); ?>" required>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold">MySQL User Password</label>
            <div class="input-group">
                <input type="password" name="db_pass" id="db_pass" class="form-control" value="<?= htmlspecialchars($currentPass); ?>" required placeholder="Enter the exact password set in Hostinger">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePass()"><i class="fas fa-eye" id="eyeIcon"></i></button>
            </div>
            <small class="text-muted">Enter the password you created in Hostinger for this user.</small>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                <i class="fas fa-plug me-2"></i> Test & Save Connection
            </button>
        </div>
    </form>

    <div class="text-center mt-4 pt-3 border-top">
        <small class="text-muted">Herbalbox Foundation &bull; CIN: U86901BR2026NPL087665</small>
    </div>
</div>

<script>
function togglePass() {
    const input = document.getElementById('db_pass');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

</body>
</html>

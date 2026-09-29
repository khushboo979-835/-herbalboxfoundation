<?php
/**
 * Database Configuration & Connection Manager
 * NGO Website & Admin System - Hostinger & Production Resilient
 */

declare(strict_types=1);

class Database {
    // Primary Hostinger Credentials
    private static string $host = 'localhost';
    private static string $dbName = 'u467991428_ngo_management';
    private static string $username = 'u467991428_ngo_user';
    private static string $password = 'IXMwfvq6R&4';
    private static string $charset = 'utf8mb4';
    private static ?PDO $pdoInstance = null;

    /**
     * Get active PDO database connection instance with multi-fallback logic
     */
    public static function getConnection(): ?PDO {
        if (self::$pdoInstance === null) {
            $dbName = getenv('DB_NAME') ?: self::$dbName;
            $username = getenv('DB_USER') ?: self::$username;
            
            // Passwords to attempt
            $passwordsToTry = array_unique(array_filter([
                getenv('DB_PASS') !== false ? getenv('DB_PASS') : null,
                self::$password,         // 'IXMwfvq6R&4'
                'News@Portal2026#',       // Alternative Hostinger password
                ''
            ]));

            // On Hostinger shared hosting, always prioritize 'localhost'
            $hostsToTry = array_unique(array_filter([
                getenv('DB_HOST') ?: null,
                'localhost',
                '127.0.0.1'
            ]));

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::$charset . " COLLATE utf8mb4_unicode_ci"
            ];

            $lastException = null;

            foreach ($hostsToTry as $host) {
                foreach ($passwordsToTry as $password) {
                    try {
                        $dsn = "mysql:host={$host};dbname={$dbName};charset=" . self::$charset;
                        self::$pdoInstance = new PDO($dsn, $username, $password, $options);
                        return self::$pdoInstance;
                    } catch (PDOException $e) {
                        $lastException = $e;
                    }
                }
            }

            // Diagnostic error box with 1-click configuration launcher
            $errorMsg = $lastException ? $lastException->getMessage() : 'Unknown connection error';
            error_log("Database Connection Error: " . $errorMsg);
            
            // If requested directly or in admin, display the setup helper
            $currentPage = basename($_SERVER['PHP_SELF'] ?? '');
            if ($currentPage !== 'db_check.php') {
                echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Database Setup - Herbalbox Foundation</title>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' rel='stylesheet'>
                </head><body style='background:#0f172a;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;font-family:sans-serif;'>
                <div style='background:#ffffff;border-radius:18px;max-width:620px;width:100%;padding:35px;box-shadow:0 25px 50px rgba(0,0,0,0.35);'>
                    <div style='text-align:center;margin-bottom:25px;'>
                        <img src='/assets/images/logo.png' style='width:60px;height:60px;object-fit:contain;margin-bottom:10px;' alt='Herbalbox Foundation'>
                        <h4 style='color:#0f172a;font-weight:700;margin-bottom:4px;'>Herbalbox Foundation</h4>
                        <p style='color:#64748b;font-size:14px;margin-bottom:0;'>Hostinger Database Configuration</p>
                    </div>
                    
                    <div class='alert alert-danger p-3 rounded-3 mb-4'>
                        <div class='fw-bold mb-1'><i class='fas fa-exclamation-triangle me-2'></i> MySQL Connection Error:</div>
                        <small style='word-break:break-all;'>" . htmlspecialchars($errorMsg) . "</small>
                    </div>

                    <div class='bg-light p-3 rounded-3 mb-4 small'>
                        <p class='mb-1'><strong>Host:</strong> <code>localhost</code></p>
                        <p class='mb-1'><strong>Database:</strong> <code>" . htmlspecialchars($dbName) . "</code></p>
                        <p class='mb-0'><strong>User:</strong> <code>" . htmlspecialchars($username) . "</code></p>
                    </div>

                    <div class='d-grid gap-2 mb-3'>
                        <a href='/db_check.php' class='btn btn-primary btn-lg fw-bold shadow-sm'>
                            <i class='fas fa-key me-2'></i> Click Here to Enter Password & Connect
                        </a>
                    </div>
                    
                    <div class='text-center'>
                        <small class='text-muted'>Or change the user password in Hostinger hPanel &rarr; Databases &rarr; Change Password.</small>
                    </div>
                </div></body></html>";
                exit;
            }
        }

        return self::$pdoInstance;
    }
}

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
    public static function getConnection(): PDO {
        if (self::$pdoInstance === null) {
            $dbName = getenv('DB_NAME') ?: self::$dbName;
            $username = getenv('DB_USER') ?: self::$username;
            
            // Passwords to attempt (New updated password vs Initial created password)
            $passwordsToTry = array_unique(array_filter([
                getenv('DB_PASS') !== false ? getenv('DB_PASS') : null,
                self::$password,         // 'IXMwfvq6R&4'
                'News@Portal2026#',       // Alternative Hostinger password
                ''
            ]));

            // Hosts to attempt (localhost socket vs TCP 127.0.0.1)
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

            // If all attempts failed, log and render descriptive diagnostic notice
            $errorMsg = $lastException ? $lastException->getMessage() : 'Unknown connection error';
            error_log("Database Connection Error: " . $errorMsg);
            
            die("<div style='font-family:Segoe UI,sans-serif;padding:35px;max-width:650px;margin:50px auto;border:1px solid #fecaca;background:#fff1f2;color:#991b1b;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.08);'>
                <h3 style='margin-top:0;color:#b91c1c;font-size:20px;'><i style='margin-right:8px;'>⚠️</i> Database Connection Notice</h3>
                <p style='color:#374151;font-size:15px;line-height:1.6;'>Unable to establish a connection with the MySQL database on Hostinger.</p>
                <div style='background:#ffffff;border:1px solid #fee2e2;padding:15px;border-radius:8px;margin:15px 0;font-size:13px;color:#1f2937;'>
                    <p style='margin:4px 0;'><strong>Database Name:</strong> <code>" . htmlspecialchars($dbName) . "</code></p>
                    <p style='margin:4px 0;'><strong>Database User:</strong> <code>" . htmlspecialchars($username) . "</code></p>
                    <p style='margin:4px 0;'><strong>MySQL Response:</strong> <code style='color:#dc2626;'>" . htmlspecialchars($errorMsg) . "</code></p>
                </div>
                <p style='font-size:13px;color:#6b7280;margin-bottom:0;'>Please ensure the database user is assigned to the database in Hostinger hPanel with <strong>All Privileges</strong>.</p>
            </div>");
        }

        return self::$pdoInstance;
    }
}

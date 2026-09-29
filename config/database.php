<?php
/**
 * Database Configuration & Connection Manager
 * NGO Website & Admin System - Hostinger & Production Ready
 */

declare(strict_types=1);

class Database {
    // Hostinger Production Credentials
    private static string $host = 'localhost';
    private static string $dbName = 'u467991428_ngo_management';
    private static string $username = 'u467991428_ngo_user';
    private static string $password = 'IXMwfvq6R&4';
    private static string $charset = 'utf8mb4';
    private static ?PDO $pdoInstance = null;

    /**
     * Get active PDO database connection instance
     */
    public static function getConnection(): PDO {
        if (self::$pdoInstance === null) {
            // Environment override if available, otherwise use defaults
            $host = getenv('DB_HOST') ?: self::$host;
            $dbName = getenv('DB_NAME') ?: self::$dbName;
            $username = getenv('DB_USER') ?: self::$username;
            $password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : self::$password;
            $port = getenv('DB_PORT') ?: '3306';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=" . self::$charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::$charset . " COLLATE utf8mb4_unicode_ci"
            ];

            try {
                self::$pdoInstance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                // In production, log error safely and show user-friendly message
                error_log("Database Connection Error: " . $e->getMessage());
                die("<div style='font-family:sans-serif;padding:30px;max-width:600px;margin:50px auto;border:1px solid #f5c6cb;background:#f8d7da;color:#721c24;border-radius:8px;'>
                    <h3 style='margin-top:0;'>Database Connection Notice</h3>
                    <p>Unable to connect to the database. Please ensure MySQL is running and database schema is imported.</p>
                    <p><strong>Database:</strong> " . htmlspecialchars($dbName) . "</p>
                    <p><small>Check credentials in <code>config/database.php</code>.</small></p>
                </div>");
            }
        }

        return self::$pdoInstance;
    }
}

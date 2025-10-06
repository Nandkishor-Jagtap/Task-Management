<?php
declare(strict_types=1);

namespace App\Config;

class Database
{
    private static ?\PDO $instance = null;
    
    public static function getConnection(): \PDO
    {
        if (self::$instance === null) {
            // Load environment variables (simple approach)
            $config = self::loadConfig();
            
            $host = $config['DB_HOST'] ?? 'localhost';
            $dbname = $config['DB_NAME'] ?? 'task_manager';
            $username = $config['DB_USER'] ?? 'root';
            $password = $config['DB_PASS'] ?? '';
            $port = $config['DB_PORT'] ?? '3306';
            
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            
            try {
                self::$instance = new \PDO($dsn, $username, $password, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (\PDOException $e) {
                throw new \PDOException("Database connection failed: " . $e->getMessage());
            }
        }
        
        return self::$instance;
    }
    
    private static function loadConfig(): array
    {
        $config = [];
        $envFile = __DIR__ . '/../../.env';
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    [$key, $value] = explode('=', $line, 2);
                    $config[trim($key)] = trim($value);
                }
            }
        }
        
        return $config;
    }
    
    public static function testConnection(): bool
    {
        try {
            $pdo = self::getConnection();
            $pdo->query('SELECT 1');
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}

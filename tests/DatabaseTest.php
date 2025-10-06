<?php
declare(strict_types=1);

use App\Config\Database;
use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase
{
    public function testGetConnection(): void
    {
        $pdo = Database::getConnection();
        
        $this->assertInstanceOf(\PDO::class, $pdo);
    }
    
    public function testConnectionIsSingleton(): void
    {
        $pdo1 = Database::getConnection();
        $pdo2 = Database::getConnection();
        
        $this->assertSame($pdo1, $pdo2);
    }
    
    public function testConnectionWorks(): void
    {
        $pdo = Database::getConnection();
        $result = $pdo->query('SELECT 1 as test');
        
        $this->assertNotFalse($result);
        $row = $result->fetch();
        $this->assertEquals(1, $row['test']);
    }
    
    public function testTestConnection(): void
    {
        $isConnected = Database::testConnection();
        
        $this->assertIsBool($isConnected);
        // Note: This test might fail if database is not properly configured
        // In a real test environment, you'd mock the database connection
    }
}

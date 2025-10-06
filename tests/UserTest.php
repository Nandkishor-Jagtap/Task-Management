<?php
declare(strict_types=1);

use App\Models\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    private User $userModel;
    
    protected function setUp(): void
    {
        $this->userModel = new User();
    }
    
    public function testGetAllUsers(): void
    {
        $users = $this->userModel->getAll();
        
        $this->assertIsArray($users);
        $this->assertGreaterThanOrEqual(0, count($users));
    }
    
    public function testGetUserById(): void
    {
        // Test with existing user ID 1
        $user = $this->userModel->getById(1);
        
        if ($user !== null) {
            $this->assertIsArray($user);
            $this->assertArrayHasKey('id', $user);
            $this->assertArrayHasKey('name', $user);
            $this->assertArrayHasKey('email', $user);
            $this->assertEquals(1, $user['id']);
        }
    }
    
    public function testGetUserByEmail(): void
    {
        // Test with existing email
        $user = $this->userModel->getByEmail('john@example.com');
        
        if ($user !== null) {
            $this->assertIsArray($user);
            $this->assertArrayHasKey('email', $user);
            $this->assertEquals('john@example.com', $user['email']);
        }
    }
    
    public function testCreateUser(): void
    {
        $name = 'Test User ' . time();
        $email = 'test' . time() . '@example.com';
        
        $userId = $this->userModel->create($name, $email);
        
        $this->assertIsInt($userId);
        $this->assertGreaterThan(0, $userId);
        
        // Clean up - delete the test user
        $this->userModel->delete($userId);
    }
    
    public function testGetTaskCount(): void
    {
        $taskCount = $this->userModel->getTaskCount(1);
        
        $this->assertIsInt($taskCount);
        $this->assertGreaterThanOrEqual(0, $taskCount);
    }
    
    public function testGetTasksByStatus(): void
    {
        $pendingTasks = $this->userModel->getTasksByStatus(1, 'pending');
        $inProgressTasks = $this->userModel->getTasksByStatus(1, 'in_progress');
        $completedTasks = $this->userModel->getTasksByStatus(1, 'completed');
        
        $this->assertIsInt($pendingTasks);
        $this->assertIsInt($inProgressTasks);
        $this->assertIsInt($completedTasks);
        $this->assertGreaterThanOrEqual(0, $pendingTasks);
        $this->assertGreaterThanOrEqual(0, $inProgressTasks);
        $this->assertGreaterThanOrEqual(0, $completedTasks);
    }
}

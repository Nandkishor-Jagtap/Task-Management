<?php
declare(strict_types=1);

use App\Models\Task;
use PHPUnit\Framework\TestCase;

final class TaskTest extends TestCase
{
    private Task $taskModel;
    
    protected function setUp(): void
    {
        $this->taskModel = new Task();
    }
    
    public function testGetAllTasks(): void
    {
        $tasks = $this->taskModel->getAll();
        
        $this->assertIsArray($tasks);
        $this->assertGreaterThanOrEqual(0, count($tasks));
    }
    
    public function testGetTaskById(): void
    {
        // Test with existing task ID 1
        $task = $this->taskModel->getById(1);
        
        if ($task !== null) {
            $this->assertIsArray($task);
            $this->assertArrayHasKey('id', $task);
            $this->assertArrayHasKey('title', $task);
            $this->assertArrayHasKey('status', $task);
            $this->assertEquals(1, $task['id']);
        }
    }
    
    public function testGetTasksByUserId(): void
    {
        $tasks = $this->taskModel->getByUserId(1);
        
        $this->assertIsArray($tasks);
        $this->assertGreaterThanOrEqual(0, count($tasks));
    }
    
    public function testGetTasksByStatus(): void
    {
        $pendingTasks = $this->taskModel->getByStatus('pending');
        $inProgressTasks = $this->taskModel->getByStatus('in_progress');
        $completedTasks = $this->taskModel->getByStatus('completed');
        
        $this->assertIsArray($pendingTasks);
        $this->assertIsArray($inProgressTasks);
        $this->assertIsArray($completedTasks);
    }
    
    public function testCreateTask(): void
    {
        $title = 'Test Task ' . time();
        $description = 'Test task description';
        $userId = 1;
        
        $taskId = $this->taskModel->create($title, $description, $userId);
        
        $this->assertIsInt($taskId);
        $this->assertGreaterThan(0, $taskId);
        
        // Clean up - delete the test task
        $this->taskModel->delete($taskId);
    }
    
    public function testUpdateTaskStatus(): void
    {
        // Create a test task first
        $title = 'Test Task for Status Update ' . time();
        $description = 'Test task for status update';
        $userId = 1;
        
        $taskId = $this->taskModel->create($title, $description, $userId);
        
        // Test status update
        $result = $this->taskModel->updateStatus($taskId, 'completed');
        
        $this->assertTrue($result);
        
        // Verify the status was updated
        $task = $this->taskModel->getById($taskId);
        $this->assertEquals('completed', $task['status']);
        
        // Clean up
        $this->taskModel->delete($taskId);
    }
    
    public function testGetTaskStats(): void
    {
        $stats = $this->taskModel->getStats();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_tasks', $stats);
        $this->assertArrayHasKey('pending_tasks', $stats);
        $this->assertArrayHasKey('in_progress_tasks', $stats);
        $this->assertArrayHasKey('completed_tasks', $stats);
        
        $this->assertIsInt($stats['total_tasks']);
        $this->assertIsInt($stats['pending_tasks']);
        $this->assertIsInt($stats['in_progress_tasks']);
        $this->assertIsInt($stats['completed_tasks']);
        
        $this->assertGreaterThanOrEqual(0, $stats['total_tasks']);
        $this->assertGreaterThanOrEqual(0, $stats['pending_tasks']);
        $this->assertGreaterThanOrEqual(0, $stats['in_progress_tasks']);
        $this->assertGreaterThanOrEqual(0, $stats['completed_tasks']);
    }
    
    public function testGetRecentTasks(): void
    {
        $recentTasks = $this->taskModel->getRecentTasks(3);
        
        $this->assertIsArray($recentTasks);
        $this->assertLessThanOrEqual(3, count($recentTasks));
    }
}

<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Config\Database;

class ApiController
{
    private Task $taskModel;
    private User $userModel;
    
    public function __construct()
    {
        $this->taskModel = new Task();
        $this->userModel = new User();
    }
    
    public function health(): void
    {
        $this->setJsonHeaders();
        
        $dbConnected = Database::testConnection();
        
        $response = [
            'status' => 'ok',
            'timestamp' => gmdate('c'),
            'database' => $dbConnected ? 'connected' : 'disconnected',
            'version' => '1.0.0'
        ];
        
        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
    
    public function getTasks(): void
    {
        $this->setJsonHeaders();
        
        try {
            $tasks = $this->taskModel->getAll();
            
            // Add additional data for API response
            $response = [
                'status' => 'success',
                'data' => $tasks,
                'count' => count($tasks),
                'timestamp' => gmdate('c')
            ];
            
            echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $this->sendErrorResponse('Failed to fetch tasks', 500);
        }
    }
    
    public function getUsers(): void
    {
        $this->setJsonHeaders();
        
        try {
            $users = $this->userModel->getAll();
            
            $response = [
                'status' => 'success',
                'data' => $users,
                'count' => count($users),
                'timestamp' => gmdate('c')
            ];
            
            echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $this->sendErrorResponse('Failed to fetch users', 500);
        }
    }
    
    public function getTaskStats(): void
    {
        $this->setJsonHeaders();
        
        try {
            $stats = $this->taskModel->getStats();
            
            $response = [
                'status' => 'success',
                'data' => $stats,
                'timestamp' => gmdate('c')
            ];
            
            echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $this->sendErrorResponse('Failed to fetch task statistics', 500);
        }
    }
    
    public function getRecentTasks(): void
    {
        $this->setJsonHeaders();
        
        $limit = (int) ($_GET['limit'] ?? 5);
        $limit = max(1, min(50, $limit)); // Limit between 1 and 50
        
        try {
            $tasks = $this->taskModel->getRecentTasks($limit);
            
            $response = [
                'status' => 'success',
                'data' => $tasks,
                'count' => count($tasks),
                'limit' => $limit,
                'timestamp' => gmdate('c')
            ];
            
            echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $this->sendErrorResponse('Failed to fetch recent tasks', 500);
        }
    }
    
    private function setJsonHeaders(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }
    
    private function sendErrorResponse(string $message, int $statusCode = 400): void
    {
        http_response_code($statusCode);
        
        $response = [
            'status' => 'error',
            'message' => $message,
            'timestamp' => gmdate('c')
        ];
        
        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}

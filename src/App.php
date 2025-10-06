<?php
declare(strict_types=1);

namespace App;

use App\Controllers\{HomeController, TaskController, UserController, ApiController};

class App
{
    private Router $router;
    
    public function __construct()
    {
        $this->router = new Router();
        $this->setupRoutes();
    }
    
    private function setupRoutes(): void
    {
        // Web routes
        $this->router->get('/', [HomeController::class, 'index']);
        
        // Task routes
        $this->router->get('/tasks', [TaskController::class, 'index']);
        $this->router->get('/tasks/create', [TaskController::class, 'create']);
        $this->router->post('/tasks/create', [TaskController::class, 'create']);
        $this->router->post('/tasks/update-status', [TaskController::class, 'updateStatus']);
        
        // User routes
        $this->router->get('/users', [UserController::class, 'index']);
        $this->router->get('/users/create', [UserController::class, 'create']);
        $this->router->post('/users/create', [UserController::class, 'create']);
        
        // API routes
        $this->router->get('/api/health', [ApiController::class, 'health']);
        $this->router->get('/api/tasks', [ApiController::class, 'getTasks']);
        $this->router->get('/api/users', [ApiController::class, 'getUsers']);
        $this->router->get('/api/task-stats', [ApiController::class, 'getTaskStats']);
        $this->router->get('/api/recent-tasks', [ApiController::class, 'getRecentTasks']);
    }
    
    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        
        $route = $this->router->match($method, $uri ?? '/');
        
        if ($route === null) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Not Found'], JSON_UNESCAPED_SLASHES);
            return;
        }
        
        [$class, $action] = $route->handler;
        
        $controller = new $class();
        $result = $controller->$action();
        
        if (is_string($result)) {
            echo $result;
        }
    }
}

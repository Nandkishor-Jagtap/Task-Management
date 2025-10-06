<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Utils\Validator;

class TaskController
{
    private Task $taskModel;
    private User $userModel;
    
    public function __construct()
    {
        $this->taskModel = new Task();
        $this->userModel = new User();
    }
    
    public function index(): string
    {
        $tasks = $this->taskModel->getAll();
        $users = $this->userModel->getAll();
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Task Manager - All Tasks</title>
            <link rel="stylesheet" href="/css/style.css">
        </head>
        <body>
            <header>
                <div class="container">
                    <h1>Task Manager</h1>
                    <p>Manage all your tasks</p>
                    <nav>
                        <a href="/">Dashboard</a>
                        <a href="/tasks">Tasks</a>
                        <a href="/tasks/create">Create Task</a>
                    </nav>
                </div>
            </header>

            <div class="container">
                <div class="card">
                    <h2>All Tasks (<?= count($tasks) ?>)</h2>
                    
                    <?php if (empty($tasks)): ?>
                        <p>No tasks found. <a href="/tasks/create">Create your first task</a>!</p>
                    <?php else: ?>
                        <div class="tasks-grid">
                            <?php foreach ($tasks as $task): ?>
                                <div class="task-item <?= $task['status'] === 'completed' ? 'completed' : '' ?>">
                                    <div class="task-header">
                                        <h3 class="task-title"><?= htmlspecialchars($task['title']) ?></h3>
                                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                                            <span class="status <?= $task['status'] ?>"><?= ucfirst($task['status']) ?></span>
                                            <?php if ($task['status'] !== 'completed'): ?>
                                                <form method="POST" action="/tasks/update-status" style="display: inline;">
                                                    <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="btn-success" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Complete</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if ($task['description']): ?>
                                        <p class="task-description"><?= htmlspecialchars($task['description']) ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="task-meta">
                                        <span><strong>Assigned to:</strong> <?= $task['user_name'] ?? 'Unassigned' ?></span>
                                        <span><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($task['created_at'])) ?></span>
                                    </div>
                                    
                                    <div style="margin-top: 1rem;">
                                        <form method="POST" action="/tasks/update-status" style="display: inline;">
                                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                            <select name="status" style="width: auto; margin-right: 0.5rem;">
                                                <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                                <option value="completed" <?= $task['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                            </select>
                                            <button type="submit" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Update Status</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <script src="/js/app.js"></script>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    public function create(): string
    {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $userId = (int) ($_POST['user_id'] ?? 0);
            
            if (Validator::validateTask($title, $userId)) {
                try {
                    $this->taskModel->create($title, $description, $userId);
                    $success = 'Task created successfully!';
                    // Clear form data
                    $title = $description = '';
                    $userId = 0;
                } catch (\Exception $e) {
                    $error = 'Error creating task: ' . $e->getMessage();
                }
            } else {
                $error = 'Please fill in all required fields correctly.';
            }
        }
        
        $users = $this->userModel->getAll();
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Task Manager - Create Task</title>
            <link rel="stylesheet" href="/css/style.css">
        </head>
        <body>
            <header>
                <div class="container">
                    <h1>Task Manager</h1>
                    <p>Create a new task</p>
                    <nav>
                        <a href="/">Dashboard</a>
                        <a href="/tasks">Tasks</a>
                        <a href="/tasks/create">Create Task</a>
                    </nav>
                </div>
            </header>

            <div class="container">
                <div class="card">
                    <h2>Create New Task</h2>
                    
                    <?php if ($error): ?>
                        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="title">Task Title *</label>
                            <input type="text" id="title" name="title" required value="<?= htmlspecialchars($title) ?>" 
                                   placeholder="Enter task title">
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4" 
                                      placeholder="Enter task description"><?= htmlspecialchars($description) ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="user_id">Assign to User *</label>
                            <select id="user_id" name="user_id" required>
                                <option value="">Select a user</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>" <?= $userId === $user['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button type="submit">Create Task</button>
                        <a href="/tasks" style="margin-left: 1rem; text-decoration: none;">
                            <button type="button" style="background: #6c757d;">Cancel</button>
                        </a>
                    </form>
                </div>
            </div>

            <script src="/js/app.js"></script>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    public function updateStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? '';
            
            if ($id > 0 && in_array($status, ['pending', 'in_progress', 'completed'])) {
                try {
                    $this->taskModel->updateStatus($id, $status);
                } catch (\Exception $e) {
                    // Log error or handle as needed
                }
            }
        }
        
        header('Location: /tasks');
        exit;
    }
}

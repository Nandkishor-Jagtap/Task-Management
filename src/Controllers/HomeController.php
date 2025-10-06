<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Task;
use App\Models\User;

class HomeController
{
    public function index(): string
    {
        $taskModel = new Task();
        $userModel = new User();
        
        $tasks = $taskModel->getAll();
        $users = $userModel->getAll();
        $stats = $taskModel->getStats();
        $recentTasks = $taskModel->getRecentTasks(5);
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Task Manager - Dashboard</title>
            <link rel="stylesheet" href="/css/style.css">
        </head>
        <body>
            <header>
                <div class="container">
                    <h1>Task Manager</h1>
                    <p>Efficiently manage your tasks and projects</p>
                    <nav>
                        <a href="/">Dashboard</a>
                        <a href="/tasks">Tasks</a>
                        <a href="/tasks/create">Create Task</a>
                    </nav>
                </div>
            </header>

            <div class="container">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['total_tasks'] ?></div>
                        <div class="stat-label">Total Tasks</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['pending_tasks'] ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['in_progress_tasks'] ?></div>
                        <div class="stat-label">In Progress</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $stats['completed_tasks'] ?></div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>

                <div class="card">
                    <h2>Recent Tasks</h2>
                    <?php if (empty($recentTasks)): ?>
                        <p>No tasks found. <a href="/tasks/create">Create your first task</a>!</p>
                    <?php else: ?>
                        <div class="tasks-grid">
                            <?php foreach ($recentTasks as $task): ?>
                                <div class="task-item <?= $task['status'] === 'completed' ? 'completed' : '' ?>">
                                    <div class="task-header">
                                        <h3 class="task-title"><?= htmlspecialchars($task['title']) ?></h3>
                                        <span class="status <?= $task['status'] ?>"><?= ucfirst($task['status']) ?></span>
                                    </div>
                                    <?php if ($task['description']): ?>
                                        <p class="task-description"><?= htmlspecialchars($task['description']) ?></p>
                                    <?php endif; ?>
                                    <div class="task-meta">
                                        <span>Assigned to: <?= $task['user_name'] ?? 'Unassigned' ?></span>
                                        <span>Created: <?= date('M j, Y', strtotime($task['created_at'])) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <h2>Quick Actions</h2>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <a href="/tasks/create" style="text-decoration: none;">
                            <button>Create New Task</button>
                        </a>
                        <a href="/tasks" style="text-decoration: none;">
                            <button style="background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);">View All Tasks</button>
                        </a>
                        <a href="/api/health" style="text-decoration: none;">
                            <button style="background: linear-gradient(135deg, #ffd43b 0%, #fab005 100%); color: #333;">API Health</button>
                        </a>
                    </div>
                </div>
            </div>

            <script src="/js/app.js"></script>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}

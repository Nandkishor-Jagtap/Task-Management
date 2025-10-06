<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Utils\Validator;

class UserController
{
    private User $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    public function index(): string
    {
        $users = $this->userModel->getAll();
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Task Manager - Users</title>
            <link rel="stylesheet" href="/css/style.css">
        </head>
        <body>
            <header>
                <div class="container">
                    <h1>Task Manager</h1>
                    <p>Manage users</p>
                    <nav>
                        <a href="/">Dashboard</a>
                        <a href="/tasks">Tasks</a>
                        <a href="/tasks/create">Create Task</a>
                        <a href="/users">Users</a>
                    </nav>
                </div>
            </header>

            <div class="container">
                <div class="card">
                    <h2>All Users (<?= count($users) ?>)</h2>
                    
                    <?php if (empty($users)): ?>
                        <p>No users found. <a href="/users/create">Add your first user</a>!</p>
                    <?php else: ?>
                        <div class="tasks-grid">
                            <?php foreach ($users as $user): ?>
                                <div class="task-item">
                                    <div class="task-header">
                                        <h3 class="task-title"><?= htmlspecialchars($user['name']) ?></h3>
                                    </div>
                                    
                                    <p class="task-description">
                                        <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
                                    </p>
                                    
                                    <div class="task-meta">
                                        <span><strong>Member since:</strong> <?= date('M j, Y', strtotime($user['created_at'])) ?></span>
                                        <span><strong>Tasks:</strong> <?= $this->userModel->getTaskCount($user['id']) ?></span>
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
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            
            if (Validator::validateUser($name, $email)) {
                try {
                    $this->userModel->create($name, $email);
                    $success = 'User created successfully!';
                    // Clear form data
                    $name = $email = '';
                } catch (\Exception $e) {
                    $error = 'Error creating user: ' . $e->getMessage();
                }
            } else {
                $error = 'Please fill in all required fields correctly.';
            }
        }
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Task Manager - Create User</title>
            <link rel="stylesheet" href="/css/style.css">
        </head>
        <body>
            <header>
                <div class="container">
                    <h1>Task Manager</h1>
                    <p>Create a new user</p>
                    <nav>
                        <a href="/">Dashboard</a>
                        <a href="/tasks">Tasks</a>
                        <a href="/tasks/create">Create Task</a>
                        <a href="/users">Users</a>
                    </nav>
                </div>
            </header>

            <div class="container">
                <div class="card">
                    <h2>Create New User</h2>
                    
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
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($name) ?>" 
                                   placeholder="Enter full name">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email) ?>" 
                                   placeholder="Enter email address">
                        </div>
                        
                        <button type="submit">Create User</button>
                        <a href="/users" style="margin-left: 1rem; text-decoration: none;">
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
}

<?php
declare(strict_types=1);

// Simple Task Manager - No Database Required
$pageTitle = 'Task Manager';
$tasks = [
    ['id' => 1, 'title' => 'Complete project documentation', 'completed' => false, 'priority' => 'high'],
    ['id' => 2, 'title' => 'Review code changes', 'completed' => true, 'priority' => 'medium'],
    ['id' => 3, 'title' => 'Update dependencies', 'completed' => false, 'priority' => 'low'],
    ['id' => 4, 'title' => 'Plan next sprint', 'completed' => false, 'priority' => 'high'],
];

$stats = [
    'total' => count($tasks),
    'completed' => count(array_filter($tasks, fn($task) => $task['completed'])),
    'pending' => count(array_filter($tasks, fn($task) => !$task['completed'])),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
        }

        .stat-card.total .icon { color: #667eea; }
        .stat-card.completed .icon { color: #10b981; }
        .stat-card.pending .icon { color: #f59e0b; }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stat-card p {
            color: #6b7280;
            font-weight: 500;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            align-items: start;
        }

        .task-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-left: 12px;
        }

        .add-task-form {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .add-task-form input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .add-task-form input:focus {
            outline: none;
            border-color: #667eea;
        }

        .add-task-form select {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            background: white;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.875rem;
        }

        .task-list {
            space-y: 12px;
        }

        .task-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .task-item:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }

        .task-item.completed {
            opacity: 0.6;
            background: #f0fdf4;
        }

        .task-item.completed .task-title {
            text-decoration: line-through;
            color: #6b7280;
        }

        .task-checkbox {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid #d1d5db;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .task-checkbox.checked {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .task-content {
            flex: 1;
        }

        .task-title {
            font-weight: 500;
            margin-bottom: 4px;
        }

        .task-priority {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .priority-high {
            background: #fef2f2;
            color: #dc2626;
        }

        .priority-medium {
            background: #fefce8;
            color: #ca8a04;
        }

        .priority-low {
            background: #f0f9ff;
            color: #0284c7;
        }

        .task-actions {
            display: flex;
            gap: 8px;
        }

        .sidebar {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .filter-section {
            margin-bottom: 24px;
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .filter-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .quick-actions h3 {
            margin-bottom: 16px;
            color: #374151;
        }

        .quick-actions .btn {
            width: 100%;
            justify-content: center;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .add-task-form {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1><i class="fas fa-tasks"></i> Task Manager success</h1>
            <p>Stay organized and productive with your daily tasks</p>
        </header>

        <div class="stats-grid">
            <div class="stat-card total">
                <div class="icon">
                    <i class="fas fa-list-check"></i>
                </div>
                <h3><?= $stats['total'] ?></h3>
                <p>Total Tasks</p>
            </div>
            <div class="stat-card completed">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3><?= $stats['completed'] ?></h3>
                <p>Completed</p>
            </div>
            <div class="stat-card pending">
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3><?= $stats['pending'] ?></h3>
                <p>Pending</p>
            </div>
        </div>

        <div class="main-content">
            <div class="task-section">
                <div class="section-header">
                    <i class="fas fa-plus-circle" style="color: #667eea;"></i>
                    <h2>Add New Task</h2>
                </div>

                <form class="add-task-form" id="addTaskForm">
                    <input type="text" id="taskInput" placeholder="Enter task description..." required>
                    <select id="prioritySelect">
                        <option value="low">Low Priority</option>
                        <option value="medium" selected>Medium Priority</option>
                        <option value="high">High Priority</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Task
                    </button>
                </form>

                <div class="section-header">
                    <i class="fas fa-list" style="color: #667eea;"></i>
                    <h2>Your Tasks</h2>
                </div>

                <div class="task-list" id="taskList">
                    <?php foreach ($tasks as $task): ?>
                    <div class="task-item <?= $task['completed'] ? 'completed' : '' ?>" data-task-id="<?= $task['id'] ?>">
                        <div class="task-checkbox <?= $task['completed'] ? 'checked' : '' ?>" onclick="toggleTask(<?= $task['id'] ?>)">
                            <?php if ($task['completed']): ?>
                                <i class="fas fa-check"></i>
                            <?php endif; ?>
                        </div>
                        <div class="task-content">
                            <div class="task-title"><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></div>
                            <span class="task-priority priority-<?= $task['priority'] ?>"><?= $task['priority'] ?></span>
                        </div>
                        <div class="task-actions">
                            <button class="btn btn-danger btn-sm" onclick="deleteTask(<?= $task['id'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="sidebar">
                <div class="filter-section">
                    <h3>Filter Tasks</h3>
                    <div class="filter-buttons">
                        <button class="filter-btn active" onclick="filterTasks('all')">All</button>
                        <button class="filter-btn" onclick="filterTasks('pending')">Pending</button>
                        <button class="filter-btn" onclick="filterTasks('completed')">Completed</button>
                    </div>
                </div>

                <div class="quick-actions">
                    <h3>Quick Actions</h3>
                    <button class="btn btn-success" onclick="markAllComplete()">
                        <i class="fas fa-check-double"></i> Mark All Complete
                    </button>
                    <button class="btn btn-danger" onclick="clearCompleted()">
                        <i class="fas fa-broom"></i> Clear Completed
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let tasks = <?= json_encode($tasks) ?>;
        let nextId = <?= max(array_column($tasks, 'id')) + 1 ?>;

        // Add new task
        document.getElementById('addTaskForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const input = document.getElementById('taskInput');
            const priority = document.getElementById('prioritySelect').value;
            const title = input.value.trim();
            
            if (title) {
                addTask(title, priority);
                input.value = '';
            }
        });

        function addTask(title, priority = 'medium') {
            const newTask = {
                id: nextId++,
                title: title,
                completed: false,
                priority: priority
            };
            
            tasks.push(newTask);
            renderTasks();
            updateStats();
        }

        function toggleTask(id) {
            const task = tasks.find(t => t.id === id);
            if (task) {
                task.completed = !task.completed;
                renderTasks();
                updateStats();
            }
        }

        function deleteTask(id) {
            tasks = tasks.filter(t => t.id !== id);
            renderTasks();
            updateStats();
        }

        function filterTasks(filter) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const taskItems = document.querySelectorAll('.task-item');
            taskItems.forEach(item => {
                const taskId = parseInt(item.dataset.taskId);
                const task = tasks.find(t => t.id === taskId);
                
                if (filter === 'all') {
                    item.style.display = 'flex';
                } else if (filter === 'completed' && !task.completed) {
                    item.style.display = 'none';
                } else if (filter === 'pending' && task.completed) {
                    item.style.display = 'none';
                } else {
                    item.style.display = 'flex';
                }
            });
        }

        function markAllComplete() {
            tasks.forEach(task => task.completed = true);
            renderTasks();
            updateStats();
        }

        function clearCompleted() {
            tasks = tasks.filter(task => !task.completed);
            renderTasks();
            updateStats();
        }

        function renderTasks() {
            const taskList = document.getElementById('taskList');
            taskList.innerHTML = '';
            
            tasks.forEach(task => {
                const taskElement = document.createElement('div');
                taskElement.className = `task-item ${task.completed ? 'completed' : ''}`;
                taskElement.dataset.taskId = task.id;
                
                taskElement.innerHTML = `
                    <div class="task-checkbox ${task.completed ? 'checked' : ''}" onclick="toggleTask(${task.id})">
                        ${task.completed ? '<i class="fas fa-check"></i>' : ''}
                    </div>
                    <div class="task-content">
                        <div class="task-title">${escapeHtml(task.title)}</div>
                        <span class="task-priority priority-${task.priority}">${task.priority}</span>
                    </div>
                    <div class="task-actions">
                        <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                
                taskList.appendChild(taskElement);
            });
        }

        function updateStats() {
            const total = tasks.length;
            const completed = tasks.filter(t => t.completed).length;
            const pending = total - completed;
            
            document.querySelector('.stat-card.total h3').textContent = total;
            document.querySelector('.stat-card.completed h3').textContent = completed;
            document.querySelector('.stat-card.pending h3').textContent = pending;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>

<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class Task
{
    private \PDO $db;
    
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT t.*, u.name as user_name 
            FROM tasks t 
            LEFT JOIN users u ON t.user_id = u.id 
            ORDER BY t.created_at DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, u.name as user_name 
            FROM tasks t 
            LEFT JOIN users u ON t.user_id = u.id 
            WHERE t.id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM tasks 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getByStatus(string $status): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, u.name as user_name 
            FROM tasks t 
            LEFT JOIN users u ON t.user_id = u.id 
            WHERE t.status = ? 
            ORDER BY t.created_at DESC
        ");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }
    
    public function create(string $title, string $description, int $userId): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (title, description, user_id) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$title, $description, $userId]);
        return (int) $this->db->lastInsertId();
    }
    
    public function update(int $id, string $title, string $description, int $userId, string $status): bool
    {
        $stmt = $this->db->prepare("
            UPDATE tasks 
            SET title = ?, description = ?, user_id = ?, status = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$title, $description, $userId, $status, $id]);
    }
    
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getStats(): array
    {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_tasks,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_tasks,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_tasks,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks
            FROM tasks
        ");
        return $stmt->fetch();
    }
    
    public function getRecentTasks(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT t.*, u.name as user_name 
            FROM tasks t 
            LEFT JOIN users u ON t.user_id = u.id 
            ORDER BY t.created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}

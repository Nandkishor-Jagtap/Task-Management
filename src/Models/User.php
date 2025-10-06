<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

class User
{
    private \PDO $db;
    
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    public function getByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    public function create(string $name, string $email): int
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->execute([$name, $email]);
        return (int) $this->db->lastInsertId();
    }
    
    public function update(int $id, string $name, string $email): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $id]);
    }
    
    public function delete(int $id): bool
    {
        // First, update tasks to remove user reference
        $stmt = $this->db->prepare("UPDATE tasks SET user_id = NULL WHERE user_id = ?");
        $stmt->execute([$id]);
        
        // Then delete the user
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getTaskCount(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }
    
    public function getTasksByStatus(int $userId, string $status): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = ?");
        $stmt->execute([$userId, $status]);
        return (int) $stmt->fetchColumn();
    }
}

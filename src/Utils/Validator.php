<?php
declare(strict_types=1);

namespace App\Utils;

class Validator
{
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function validateTask(string $title, int $userId): bool
    {
        return !empty(trim($title)) && $userId > 0;
    }
    
    public static function validateUser(string $name, string $email): bool
    {
        return !empty(trim($name)) && self::validateEmail($email);
    }
    
    public static function validateTaskStatus(string $status): bool
    {
        return in_array($status, ['pending', 'in_progress', 'completed']);
    }
    
    public static function sanitizeString(string $input): string
    {
        return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
    }
    
    public static function validateId(int $id): bool
    {
        return $id > 0;
    }
    
    public static function validateLimit(int $limit): bool
    {
        return $limit > 0 && $limit <= 100;
    }
}

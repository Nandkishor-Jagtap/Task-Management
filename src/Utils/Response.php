<?php
declare(strict_types=1);

namespace App\Utils;

class Response
{
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
    
    public static function success(array $data = [], string $message = 'Success'): void
    {
        self::json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'timestamp' => gmdate('c')
        ]);
    }
    
    public static function error(string $message, int $statusCode = 400, array $errors = []): void
    {
        $response = [
            'status' => 'error',
            'message' => $message,
            'timestamp' => gmdate('c')
        ];
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        self::json($response, $statusCode);
    }
    
    public static function notFound(string $message = 'Resource not found'): void
    {
        self::error($message, 404);
    }
    
    public static function unauthorized(string $message = 'Unauthorized'): void
    {
        self::error($message, 401);
    }
    
    public static function forbidden(string $message = 'Forbidden'): void
    {
        self::error($message, 403);
    }
    
    public static function serverError(string $message = 'Internal server error'): void
    {
        self::error($message, 500);
    }
}

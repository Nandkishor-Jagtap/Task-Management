<?php
declare(strict_types=1);

// Minimal standalone PHP page that requires no database
// Visit this directly: http://localhost:8000/hello.php

$appName = 'Task Manager - Hello';
$now = (new DateTimeImmutable('now'))->format('Y-m-d H:i:s');

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        body { font-family: -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; padding: 24px; line-height: 1.5; }
        .card { max-width: 640px; margin: 0 auto; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; box-shadow: 0 6px 20px rgba(0,0,0,0.06); }
        h1 { margin: 0 0 8px; font-size: 24px; }
        p { margin: 6px 0; color: #374151; }
        code { background: #f3f4f6; padding: 2px 6px; border-radius: 6px; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
    </head>
<body>
    <div class="card">
        <h1><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p>It works! This page is independent of the database.</p>
        <p><strong>Server time:</strong> <code><?= htmlspecialchars($now, ENT_QUOTES, 'UTF-8'); ?></code></p>
        <p>Next steps:</p>
        <ul>
            <li>Home page (requires DB): <a href="/">/</a></li>
            <li>This minimal page: <a href="/hello.php">/hello.php</a></li>
        </ul>
    </div>
</body>
</html>




<?php
$host     = 'localhost';
$dbname   = 'kavian';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // اگر این فایل به عنوان API صدا زده شده JSON برگردان، وگرنه die
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
    }
    $isApi = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
        || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/api/') !== false);

    if ($isApi) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'خطا در اتصال به دیتابیس']);
        exit;
    }
    die("خطا در اتصال به دیتابیس: " . $e->getMessage());
}

// session را اینجا شروع نمی‌کنیم تا header قبلاً ست نشه
// هر فایل خودش session_start می‌زند

// $pdo را در global scope قرار می‌دهیم
// تا require_once ساده هم بدون $pdo = کار کند
$GLOBALS['pdo'] = $pdo;

return $pdo;
